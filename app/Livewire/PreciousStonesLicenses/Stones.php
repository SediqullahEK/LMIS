<?php

namespace App\Livewire\PreciousStonesLicenses;

use App\Models\PSStone;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;



class Stones extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $name;
    public $latin_name;
    public $quantity;
    public $estimated_extraction;
    public $estimated_price_from;
    public $estimated_price_to;
    public $offered_royality_by_private_sector;
    public $final_royality_after_negotiations;
    public $estimated_revenue_based_on_ORPS;
    public $estimated_revenue_based_on_FRAN;
    public $image_path;
    public $photo;
    public $stone;
    public $currentPage = 1;
    public $search;
    public $perPage = 5;
    public $stoneId;
    public $isDataLoaded = false;
    public $confirm = false;
    public $isOpen = false;
    public $isEditing = false;
    public $idToDelete;
    public $existing_photo_path;
    public $sortField;
    public $noData;

    //PSStone CRUD section
    public function addStone()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'latin_name' => 'required',
            'quantity' => 'required',
            'image_path' => 'nullable|image|max:1024',
            'estimated_extraction' => 'required',
            'estimated_price_from' => 'required',
            'estimated_price_to' => 'required',
            'offered_royality_by_private_sector' => 'required',
            'final_royality_after_negotiations' => 'required',
            'estimated_revenue_based_on_ORPS' => 'required',
            'estimated_revenue_based_on_FRAN' => 'required',
        ]);

        $imagPath = '';

        $stone = PSStone::create([
            'image_path' =>  $imagPath,
            'name' => $validatedData['name'],
            'latin_name' => $validatedData['latin_name'],
            'quantity' => $validatedData['quantity'],
            'estimated_extraction' => $validatedData['estimated_extraction'],
            'estimated_price_from' => $validatedData['estimated_price_from'],
            'estimated_price_to' => $validatedData['estimated_price_to'],
            'offered_royality_by_private_sector' => $validatedData['offered_royality_by_private_sector'],
            'final_royality_after_negotiations' => $validatedData['final_royality_after_negotiations'],
            'estimated_revenue_based_on_ORPS' => $validatedData['estimated_revenue_based_on_ORPS'],
            'estimated_revenue_based_on_FRAN' => $validatedData['estimated_revenue_based_on_FRAN'],
        ]);
        logActivity('create', 'app\Models\PSStone', $stone->id, $stone->toArray());

        if ($stone) {
            session()->flash('message', 'صلاحیت موفقانه ایجاد گردید');
            $this->resetForm();
            // $this->isOpen = false;
            $this->dispatch('recordCreate');
        }
    }
    public function editStone($id)
    {
        $this->isEditing = true;
        $this->stoneId = $id;
        $this->resetForm();

        $stone = PSStone::find($id);

        $this->name = $stone->name;
        $this->latin_name = $stone->latin_name;
        $this->quantity = $stone->quantity;
        $this->estimated_extraction = $stone->estimated_extraction;
        $this->estimated_price_from = $stone->estimated_price_from;
        $this->estimated_price_to = $stone->estimated_price_to;
        $this->offered_royality_by_private_sector = $stone->offered_royality_by_private_sector;
        $this->final_royality_after_negotiations = $stone->final_royality_after_negotiations;
        $this->estimated_revenue_based_on_ORPS = $stone->estimated_revenue_based_on_ORPS;
        $this->estimated_revenue_based_on_FRAN = $stone->estimated_revenue_based_on_FRAN;

        $this->isOpen = true;
    }

    public function updateStone()
    {
        // Find the stone by ID
        $stone = PSStone::find($this->stoneId);

        $validationRules = [];

        if ($this->name !== $stone->name) {
            $validationRules['name'] = 'required|string|max:255';
        }

        if ($this->latin_name !== $stone->latin_name){
            $validationRules['latin_name'] = 'required|string|max:255';
        }

        if ($this->quantity !== $stone->quantity){
            $validationRules['quantity'] = 'required';
        }

        if ($this->estimated_extraction !==$stone->estimated_extraction){
            $validationRules['estimated_extraction'] = 'required';
        }

        if ($this->estimated_price_from !==$stone->estimated_price_from){
            $validatedData['estimated_price_from'] = 'required';
        }



        if ($this->photo) {
            $validationRules['photo'] = 'nullable|image|max:1024';
        }


        // Only perform validation if there are rules
        if (!empty($validationRules)) {
            $validatedData = $this->validate($validationRules);
        } else {
            session()->flash('error', 'هیچ تغییر جدید در معلومات ایجاد نشده!');
            return;
        }
        $beforeState = $individual->toArray();

        // Handle profile image if changed
        if ($this->photo) {
            // Delete the old profile photo if it exists
            if ($individual->photo_path) {
                Storage::disk('public')->delete($individual->photo_path);
            }
            try {
                // Generate a unique name for the new image
                $uniqueSuffix = uniqid(); // Generate a unique ID
                $imageName = $this->name . '_' . $uniqueSuffix . '.' . $this->photo->getClientOriginalExtension();
                $imagePath = $this->photo->storeAs('individuals_photos', $imageName, 'public');

                // Update the user's profile photo path
                $individual->photo_path = $imagePath;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        // Update only changed attributes
        if (isset($validatedData['name'])) {
            $individual->name = $validatedData['name'];
        }

        if (isset($validatedData['fathers_name'])) {
            $individual->f_name = $validatedData['fathers_name'];
        }

        if (isset($validatedData['tin_num'])) {
            $individual->tin_num = $validatedData['tin_num'];
        }

        if (isset($validatedData['tazkira_num'])) {
            $individual->tazkira_num = $validatedData['tazkira_num'];
        }

        if (isset($validatedData['province'])) {
            $individual->province_id = $validatedData['province'];
        }
        if (isset($validatedData['date_of_birth'])) {
            $individual->date_of_birth = $validatedData['date_of_birth'];
        }
        if (isset($validatedData['nationality'])) {
            $individual->nationality = $validatedData['nationality'];
        }

        if (isset($validatedData['district'])) {
            $individual->district = $validatedData['district'];
        }

        $individual->phone = $this->phone;
        $individual->updated_by = auth()->user()->id;
        $done = $individual->save();

        logActivity('update', 'App\Models\Individual', $individual->id, [
            'قبلا' => $beforeState,
            'بعدا' => $individual,
        ]);
        if ($done) {
            $this->isOpen = false;
            session()->flash('message', 'شخص موفقانه ویرایش گردید');
            $this->resetForm();
        }
    }

    public function deleteStone(Request $request)
    {
        $stone = PSStone::find($this->idToDelete);
        if ($stone) {
            $stone->delete();
            logActivity('delete', 'App\Models\Companies', $stone->id);
            $request->session()->flash('message', 'سنګ موفقانه حذف شد');
            $this->confirm = false;
            $this->loadTableData();
        } else {
            $request->session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
    }

    // alerts section
    public function openForm($fs)
    {
        if ($fs) {
            $this->isEditing = false;
            $this->isOpen = true;
        } else {
            $this->isEditing = true;
            $this->isOpen = true;
        }
    }

    public function toggleConfirm($id)
    {
        if ($id) {
            $this->idToDelete = $id;
            $this->confirm = true;
        } else {
            $this->confirm = false;
        }
    }
    public function resetForm()
    {
        $this->name = '';
    }



    public function closeAlert()
    {
        session()->forget('message');
    }
    //Table data section
    public function tableData()
    {
        $data;
        $dataCount;
        // Start building the query
        $query = PSStone::query();

        // Apply search filter if the search input is not empty
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($query->get()->isEmpty()) {
            $this->noData = true;
        } else {
            $this->noData = false;
        }

        // Pagination logic
        if ($this->perPage) {
            $data = $query->paginate($this->perPage);
            $this->currentPage = $data->currentPage();
            $dataCount = $data->total();
        } else {
            $data = $query->get();
        }

        // Handle invalid page number
        if (!$this->search && $this->perPage != 0 && isset($dataCount) && ($data->currentPage() > ceil($dataCount / $this->perPage))) {
            session()->flash('error', ' به این تعداد دیتا موجود نیست، صفحه/مقدار دیتا را درست انتخاب کنید!');
        }

        return $data;
    }
    //life cycle hooks
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function loadTableData()
    {
        $this->isDataLoaded = true;
    }
    public function render()
    {
        return view('livewire.precious-stones-licenses.stones', [
            'stones' => $this->isDataLoaded ? $this->tableData() : collect(),
        ]);
    }
}
