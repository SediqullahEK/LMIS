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
    public $noData;
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $is_precious = '';

    protected $rules = [

        'name' => 'required|unique:precious_semi_precious_stones|regex:/^[\p{Script=Arabic}\s]+$/u|max:255',
        'latin_name' => 'required|unique:precious_semi_precious_stones|regex:/^[A-Za-z ]+$/|max:255',
        'quantity' => 'required',
        'is_precious' => 'required',
        'image_path' => 'nullable|image|max:1024',
        'estimated_extraction' => 'required|numeric|min:0',
        'estimated_price_from' => 'required|numeric|min:0',
        'estimated_price_to' => 'required|numeric|min:0',
        'offered_royality_by_private_sector' => 'required|numeric|min:0',
        'final_royality_after_negotiations' => 'required|numeric|min:0',
        'estimated_revenue_based_on_ORPS' => 'required|numeric|min:0',
        'estimated_revenue_based_on_FRAN' => 'required|numeric|min:0',
    ];

    protected $messages = [

        'name.required' => 'نام سنګ ضروری است',
        'latin_name.required' => 'نام لاتین سنګ ضروری است',
        'quantity.required' => 'انتخاب مقیاس ضروری است',
        'is_precious.requirde' => 'نوع سنګ ضروری است',
        'estimated_extraction.required' => 'مقدار تخمینی استخراج ضروری است',
        'estimated_price_from.required' => 'نرخ تخمینی حد اقل ضروی است',
        'estimated_price_to.required' => 'نرخ تخمینی  حد اکثر ضروری است',
        'offered_royality_by_private_sector.required' => 'رویالیتی پیشنهادی ضروری است',
        'final_royality_after_negotiations.required' => 'رویالیتی نهایی ضروری است',
        'estimated_revenue_based_on_ORPS.required' => 'عواید تخمینی پیشنهادی ضروری است',
        'estimated_revenue_based_on_FRAN.required' => 'عواید تخمینی به اساس رویالیتی ضروری است',
        'name.regex' => 'نام مروج به دری وارد کنید',
        'latin_name.regex' => 'نام لاتین سنګ به انګلسی وارد کنید',
        'name.unique' => 'نام سنګ موجود است',
        'latin_name.unique' => 'نام لاتین سنګ موجود است',
    ];

    // Real-time validation for individual fields
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    //PSStone CRUD section
    public function addStone()
    {

        // Validate all fields
        $validatedData = $this->validate();

        $imagPath = '';

        if ($this->photo != '') {
            try {
                $imageName = $this->latin_name . time() . '.' . $this->photo->getClientOriginalExtension();
                $imagePath = $this->photo->storeAs('stones_photos', $imageName, 'public');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }



        $done = PSStone::create([
            'image_path' =>  $imagePath ?? null,
            'is_precious' => (int)$this->is_precious,
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

        logActivity('create', 'app\Models\PSStone', $done->id, $done);
        // Flash a success message and reset the form
        session()->flash('message', 'سنک موفقانه اضافه گردید');
        $this->resetForm();
        $this->isOpen = false;
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
        $this->is_precious = $stone->is_precious;
        $this->estimated_extraction = $stone->estimated_extraction;
        $this->estimated_price_from = $stone->estimated_price_from;
        $this->estimated_price_to = $stone->estimated_price_to;
        $this->offered_royality_by_private_sector = $stone->offered_royality_by_private_sector;
        $this->final_royality_after_negotiations = $stone->final_royality_after_negotiations;
        $this->estimated_revenue_based_on_ORPS = $stone->estimated_revenue_based_on_ORPS;
        $this->estimated_revenue_based_on_FRAN = $stone->estimated_revenue_based_on_FRAN;
        $this->existing_photo_path = $stone->image_path;

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

        if ($this->latin_name !== $stone->latin_name) {
            $validationRules['latin_name'] = 'required|string|max:255';
        }

        if ($this->quantity !== $stone->quantity) {
            $validationRules['quantity'] = 'required';
        }

        if ($this->is_precious !== $stone->is_precious) {
            $validationRules['is_precious'] = 'required';
        }

        if ($this->estimated_extraction !== $stone->estimated_extraction) {
            $validationRules['estimated_extraction'] = 'required|numeric';
        }

        if ($this->estimated_price_from !== $stone->estimated_price_from) {
            $validationRules['estimated_price_from'] = 'required|numeric';
        }

        if ($this->estimated_price_to !== $stone->estimated_price_to) {
            $validationRules['estimated_price_to'] = 'required|numeric';
        }

        if ((float)$this->offered_royality_by_private_sector !== $stone->offered_royality_by_private_sector) {

            $validationRules['offered_royality_by_private_sector'] = 'required|numeric';
        }

        if ((float)$this->final_royality_after_negotiations !== $stone->final_royality_after_negotiations) {
            $validationRules['final_royality_after_negotiations'] = 'required|numeric';
        }

        if ($this->estimated_revenue_based_on_ORPS !== $stone->estimated_revenue_based_on_ORPS) {

            $validationRules['estimated_revenue_based_on_ORPS'] = 'required|numeric';
        }

        if ($this->estimated_revenue_based_on_FRAN !== $stone->estimated_revenue_based_on_FRAN) {

            $validationRules['estimated_revenue_based_on_FRAN'] = 'required|numeric';
        }

        if ($this->existing_photo_path !== $stone->image_path) {

            $validationRules['photo'] = 'nullable|image|max:1024';
        }


        // Only perform validation if there are rules
        if (!empty($validationRules)) {

            $validatedData = $this->validate($validationRules);
        } else {
            session()->flash('error', 'هیچ تغییر جدید در معلومات ایجاد نشده!');
            return;
        }

        $beforeState = $stone->toArray();

        // Handle profile image if changed
        if ($this->photo) {
            // Delete the old profile photo if it exists
            if ($stone->image_path) {
                Storage::disk('public')->delete($stone->image_path);
            }
            try {
                // Generate a unique name for the new image
                $uniqueSuffix = uniqid(); // Generate a unique ID
                $imageName = $this->name . '_' . $uniqueSuffix . '.' . $this->photo->getClientOriginalExtension();
                $imagePath = $this->photo->storeAs('stones_photos', $imageName, 'public');

                // Update the user's profile photo path
                $stone->image_path = $imagePath;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        // Update only changed attributes
        if (isset($validatedData['name'])) {
            $stone->name = $validatedData['name'];
        }

        if (isset($validatedData['latin_name'])) {
            $stone->latin_name = $validatedData['latin_name'];
        }

        if (isset($validatedData['quantity'])) {
            $stone->quantity = $validatedData['quantity'];
        }

        if (isset($validatedData['is_precious'])) {
            $stone->is_precious = $validatedData['is_precious'];
        }

        if (isset($validatedData['estimated_extraction'])) {
            $stone->estimated_extraction = $validatedData['estimated_extraction'];
        }

        if (isset($validatedData['estimated_price_from'])) {
            $stone->estimated_price_from = $validatedData['estimated_price_from'];
        }
        if (isset($validatedData['estimated_price_to'])) {
            $stone->estimated_price_to = $validatedData['estimated_price_to'];
        }
        if (isset($validatedData['offered_royality_by_private_sector'])) {
            $stone->offered_royality_by_private_sector = $validatedData['offered_royality_by_private_sector'];
        }

        if (isset($validatedData['final_royality_after_negotiations'])) {
            $stone->final_royality_after_negotiations = $validatedData['final_royality_after_negotiations'];
        }

        if (isset($validatedData['estimated_revenue_based_on_ORPS'])) {
            $stone->estimated_revenue_based_on_ORPS = $validatedData['estimated_revenue_based_on_ORPS'];
        }

        if (isset($validatedData['estimated_revenue_based_on_FRAN'])) {
            $stone->estimated_revenue_based_on_FRAN = $validatedData['estimated_revenue_based_on_FRAN'];
        }


        // $stone->updated_by = auth()->user()->id;
        $done = $stone->save();

        logActivity('update', 'App\Models\PSStone', $stone->id, [
            'قبلا' => $beforeState,
            'بعدا' => $stone,
        ]);
        if ($done) {
            $this->isOpen = false;
            session()->flash('message', 'سنګ موفقانه ویرایش گردید');
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
        $this->latin_name = '';
        $this->quantity = '';
        $this->estimated_extraction = '';
        $this->estimated_price_from = '';
        $this->estimated_price_to = '';
        $this->offered_royality_by_private_sector = '';
        $this->final_royality_after_negotiations = '';
        $this->estimated_revenue_based_on_ORPS = '';
        $this->estimated_revenue_based_on_FRAN = '';
        $this->resetValidation();
    }


    // sort data
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
        $this->tableData();
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
            $columns = ['name', 'latin_name']; // Replace with your visible column names
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('latin_name', 'like', '%' . $this->search . '%');
        }

        if ($query->get()->isEmpty()) {
            $this->noData = true;
        } else {
            $this->noData = false;
        }

        // Pagination logic
        if ($this->perPage) {
            $data = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
            $this->currentPage = $data->currentPage();
            $dataCount = $data->total();
        } else {
            $data = $query->orderBy($this->sortField, $this->sortDirection)->get();
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
