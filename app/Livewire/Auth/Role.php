<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as roles;

class Role extends Component
{

    public $perPage = 5;
    public $isDataLoaded = false;
    public $role;
    public $searchedPermission;
    public $rolePermissions = [];
    public $allPermissions = [];
    public $permissions = [];
    public $roleId;
    public $confirm = false;
    public $selectAllPermissions = false;
    public $showPermissions = false;
    public $isOpen = false;
    public $isEditing = false;
    public $idToDelete;

    //Role CRUD Section
    public function addRole(Request $request)
    {
        $validatedData = $this->validate([
            'role' => 'required|unique:roles,name',
        ], [
            'role.unique' => 'صلاحیت ذیل موجود است'
        ]);
        $role = roles::create([
            'name' => $validatedData['role'],
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        logActivity('create', 'Spatie\Permission\Models\Role', $role->id, $role->toArray());

        if ($role) {
            $request->session()->flash('message', 'وظیفه موفقانه ایجاد گردید');
            $this->resetForm();
            $this->isOpen = false;
            $this->dispatch('recordCreate');
        }
    }
    public function editRole($id)
    {
        $this->isEditing = true;
        $this->resetForm();

        $role = roles::find($id);

        $this->role = $role->name;
        $this->roleId = $role->id;

        $this->isOpen = true;
    }
    public function updateRole(Request $request)
    {
        $validatedData = $this->validate([
            'role' => 'required|unique:roles,name',

        ]);
        $role = roles::find($this->roleId);
        $beforeState = $role->toArray();
        $role->name = $validatedData['role'];
        $role->updated_by = auth()->user()->id;
        $done = $role->save();
        logActivity('update', 'Spatie\Permission\Models\Role', $permission->id, [
            'before' => $beforeState,
            'after' => $role->toArray()
        ]);
        if ($done) {
            $request->session()->flash('message', 'وظیفه موفقانه ویرایش گردید');
            $this->resetForm();
            $this->isOpen = false;
        }
    }
    public function deleteRole(Request $request)
    {
        $role = roles::find($this->idToDelete);
        if ($role) {
            $role->delete();
            logActivity('delete', 'Spatie\Permission\Models\Role', $role->id);
            $request->session()->flash('message', 'وظیفه موفقانه حذف شد');
            $this->confirm = false;
        } else {
            $request->session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
    }

    //Modal section
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
    public function resetForm()
    {
        $this->role = '';
    }
    public function closeAlert()
    {
        session()->forget('message');
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

    //add permission to role section 
    public function openPermissions($id)
    {
        $this->selectAllPermissions = false;
        $role = roles::find($id);
        $this->allPermissions = Permission::all();
        if ($role) {
            $this->rolePermissions = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->where('role_has_permissions.role_id', $role->id)
                ->pluck('permissions.name') // Get permission names instead of IDs
                ->toArray();
            $this->roleId = $role->id;
            $this->role = $role->name;
            $this->permissions = $this->rolePermissions;
            $this->showPermissions = true;
            $this->searchedPermission = '';
        }
    }
    public function updatedSearchedPermission()
    {
        $this->searchPermissions();
    }
    public function searchPermissions()
    {
        $this->allPermissions = Permission::when($this->searchedPermission, function ($query) {
            $query->where('name', 'like', '%' . $this->searchedPermission . '%');
        })->get();
    }
    public function addPermissionsToRole()
    {
        $role = roles::find($this->roleId);
        if ($role) {

            $role->syncPermissions($this->permissions);
            logActivity('add permissions to role', 'Spatie\Permission\Models\Role', $role->id, $this->permissions);
            session()->flash('message', 'صلاحیت ها موفقانه ویرایش گردید');
            $this->showPermissions = false;
        }
    }
    public function toggleSelectAllPermissions()
    {
        if ($this->selectAllPermissions) {
            $this->permissions = $this->allPermissions->pluck('name')->toArray();
        } else {
            $this->permissions = [];
        }
    }
    public function updatedPermissions()
    {

        $this->selectAllPermissions = count($this->permissions) === $this->allPermissions->count();
    }

    //Table data section
    public function tableData()
    {
        $data;
        $dataCount;
        if ($this->perPage) {
            $data = roles::paginate($this->perPage);
            $dataCount = $data->total();
        } else {
            $data = roles::all();
        }

        if ($this->perPage != 0 && ($data->currentPage() > ceil($dataCount / $this->perPage))) {

            session()->flash('error', ' به این تعداد دیتا موجود نیست، صفحه/مقدار دیتا را درست انتخاب کنید!');
        }

        return $data;
    }
    public function loadTableData()
    {
        $this->isDataLoaded = true;
    }

    public function render()
    {
        return view('livewire.auth.role', [
            'roles' => $this->isDataLoaded ? $this->tableData() : collect(),

        ]);
    }
}
