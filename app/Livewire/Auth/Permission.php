<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission as permissions;

class Permission extends Component
{
    use WithPagination;
    public $permission;
    public $currentPage = 1;
    public $search;
    public $perPage = 5;
    public $permissionId;
    public $isDataLoaded = false;
    public $confirm = false;
    public $isOpen = false;
    public $isEditing = false;
    public $idToDelete;

    //Permission CRUD section
    public function addPermission(Request $request)
    {
        $validatedData = $this->validate([
            'permission' => 'required|unique:permissions,name',
        ], [
            'permission.unique' => 'صلاحیت ذیل موجود است!'
        ]);
        $permission = permissions::create([
            'name' => $validatedData['permission'],
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        logActivity('create', 'Spatie\Permission\Models\Permission', $permission->id, $permission->toArray());

        if ($permission) {
            $request->session()->flash('message', 'صلاحیت موفقانه ایجاد گردید');
            $this->resetForm();
            // $this->isOpen = false;
            $this->dispatch('recordCreate');
        }
    }
    public function editPermission($id)
    {
        $this->isEditing = true;
        $this->resetForm();

        $permission = permissions::find($id);

        $this->permission = $permission->name;
        $this->permissionId = $permission->id;

        $this->isOpen = true;
    }

    public function updatePermission(Request $request)
    {
        $validatedData = $this->validate([
            'permission' => 'required|unique:permissions,name',

        ]);
        $permission = permissions::find($this->permissionId);
        $beforeState = $permission->toArray();
        $permission->name = $validatedData['permission'];
        $permission->updated_by = auth()->user()->id;
        $done = $permission->save();
        logActivity('update', 'Spatie\Permission\Models\Permission', $permission->id, [
            'before' => $beforeState,
            'after' => $permission->toArray()
        ]);
        if ($done) {
            $request->session()->flash('message', 'صلاحیت موفقانه ویرایش گردید');
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
        $this->permission = '';
    }

    public function deletePermission(Request $request)
    {
        $permission = permissions::find($this->idToDelete);
        if ($permission) {
            $permission->delete();
            logActivity('delete', 'Spatie\Permission\Models\Permission', $permission->id);
            $request->session()->flash('message', 'صلاحیت موفقانه حذف شد');
            $this->confirm = false;
        } else {
            $request->session()->flash('error', 'خطا در پروسه حذف');
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
        $query = permissions::query();

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
        return view('livewire.auth.permission', [
            'permissions' => $this->isDataLoaded ? $this->tableData() : collect(),
        ]);
    }
}
