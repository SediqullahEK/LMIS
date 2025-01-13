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
        $this->resetForm();

        $stone = PSStone::find($id);

        $this->stone = $stone->name;
        $this->stoneId = $stone->id;

        $this->isOpen = true;
    }

    public function updateStone()
    {
        $validatedData = $this->validate([
            'name' => 'required|unique:precious_semi_precious_stones,name',

        ]);
        $stone = PSStone::find($this->stoneId);
        $beforeState = $stone->toArray();
        $stone->name = $validatedData['name'];
        $stone->updated_by = auth()->user()->id;
        $done = $stone->save();
        logActivity('update', 'app\Models\PSStone', $stone->id, [
            'قبلا' => $beforeState,
            'بعدا' => $stone->toArray()
        ]);
        if ($done) {
            session()->flash('message', 'صلاحیت موفقانه ویرایش گردید');
            $this->resetForm();
            $this->isOpen = false;
            // $this->dispatch('recordUpdate');
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

    public function deleteStone()
    {
        $stone = PSStone::find($this->idToDelete);
        if ($stone) {
            $stone->delete();
            logActivity('delete', 'app\Models\PSStone', $stone->id);
            session()->flash('message', 'صلاحیت موفقانه حذف شد');
            $this->confirm = false;
        } else {
            session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
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
