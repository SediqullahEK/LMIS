<?php

namespace App\Livewire\User;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class Register extends Component
{
    use WithFileUploads;

    public $perPage = 5;
    public $isDataLoaded = false;
    public $user = true;
    public $role = false;
    public $permission = false;
    public $selectAllRoles = false;
    public $full_name;
    public $selectedUser;
    public $user_name;
    public $department = 0;
    public $email;
    public $password;
    public $password_confirmation;

    public $userId;
    public $confirm = false;
    public $isOpen = false;
    public $showRoles = false;
    public $isEditing = false;
    public $idToDelete;
    public $profile_image;
    public $existing_image_path;
    public $passwordUpdate;

    public $allRoles = [];
    public $roles = [];
    public $userRoles = [];

    //User CRUD section
    public function addUser()
    {
        // Validate form data
        $validatedData = $this->validate([
            'full_name' => 'required|string|max:255',
            'user_name' => 'required|string|unique:users,user_name|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'required',
            'profile_image' => 'nullable|image|max:1024',
        ]);

        // Hash the password
        $validatedData['password'] = bcrypt($validatedData['password']);

        $imagePath = '';
        if ($this->profile_image != '') {
            try {
                $imageName = $this->user_name . time() . '.' . $this->profile_image->getClientOriginalExtension();
                $imagePath = $this->profile_image->storeAs('user_profiles', $imageName, 'public');
            } catch (\Exception $e) {
                dd($e->getMessage());  // Catch any errors
            }
        } else {
            $imagePath = 'user_profiles/profileIcon.png';
        }
        $done = User::create([
            'full_name' => $validatedData['full_name'],
            'user_name' => $validatedData['user_name'],
            'profile_photo_path' =>  $imagePath ?? null,
            'password' => $validatedData['password'],
            'department_id' => $validatedData['department'],
            'email' => $validatedData['email'],
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);
        logActivity('create', 'app\Models\User', $done->id, $done->toArray());

        // Flash a success message and reset the form
        if ($done) {
            session()->flash('message', 'کاربر موفقانه ایجاد گردید');
            $this->resetForm();
            $this->loadTableData();
            $this->isOpen = false;
        }
    }

    public function editUser($id)
    {
        $this->isEditing = true;
        $this->resetForm();

        $user = User::find($id);

        $this->full_name = $user->full_name;
        $this->user_name = $user->user_name;
        $this->email = $user->email;
        $this->existing_image_path = $user->profile_photo_path;
        $this->department = $user->department_id;
        $this->userId = $user->id;
    }

    public function updateUser()
    {
        // Find the user by ID
        $user = User::find($this->userId);

        $validationRules = [];

        if ($this->full_name !== $user->full_name) {
            $validationRules['full_name'] = 'required|string|max:255';
        }

        if ($this->user_name !== $user->user_name) {
            $validationRules['user_name'] = 'required|string|unique:users,user_name|max:45';
        }

        if ($this->email !== $user->email) {
            $validationRules['email'] = 'required|email|unique:users,email';
        }

        if ($this->department !== $user->department_id) {
            $validationRules['department'] = 'required';
        }


        if ($this->profile_image) {
            $validationRules['profile_image'] = 'nullable|image|max:1024';
        }

        if ($this->passwordUpdate && $this->password) {
            $validationRules['password'] = 'required|string|min:8|confirmed';
        }

        // Only perform validation if there are rules
        if (!empty($validationRules)) {
            $validatedData = $this->validate($validationRules);
        } else {
            // No fields changed, nothing to validate or update
            return;
        }

        $beforeState = $user->toArray();

        // Handle profile image if changed
        if ($this->profile_image) {
            // Delete the old profile photo if it exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            try {
                // Generate a unique name for the new image
                $uniqueSuffix = uniqid(); // Generate a unique ID
                $imageName = $this->user_name . '_' . $uniqueSuffix . '.' . $this->profile_image->getClientOriginalExtension();
                $imagePath = $this->profile_image->storeAs('user_profiles', $imageName, 'public');

                // Update the user's profile photo path
                $user->profile_photo_path = $imagePath;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        // Update only changed attributes
        if (isset($validatedData['full_name'])) {
            $user->full_name = $validatedData['full_name'];
        }

        if (isset($validatedData['user_name'])) {
            $user->user_name = $validatedData['user_name'];
        }

        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        if (isset($validatedData['department'])) {
            $user->department_id = $validatedData['department'];
        }

        if ($this->passwordUpdate && $this->password) {
            $user->password = Hash::make($this->password);
        }

        // Save the user and reset the form
        $user->updated_by = auth()->user()->id;
        $done = $user->save();

        logActivity('update', 'app\Models\User', $user->id, [
            'before' => $beforeState,
            'after' => $user->toArray()
        ]);
        if ($done) {
            $this->isOpen = false;
            session()->flash('message', 'کاربر موفقانه ویرایش گردید');
            $this->resetForm();
            $this->loadTableData();
            $this->dispatch('profilePhotoUpdated', $user->profile_photo_path);
        }
    }
    public function deleteUser(Request $request)
    {
        $user = User::find($this->idToDelete);
        if ($user) {
            $user->delete();
            logActivity('delete', 'app\Models\User', $user->id);
            $request->session()->flash('message', 'کاربر موفقانه حذف شد');
            $this->confirm = false;
            $this->loadTableData();
        } else {
            $request->session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
    }
    public function resetForm()
    {
        $this->full_name = '';
        $this->user_name = '';
        $this->email = '';
        $this->department = 0;
        $this->password = '';
        $this->password_confirmation = '';
        $this->profile_image = '';
        $this->existing_image_path = '';
    }


    //alerts sections
    public function toggleConfirm($id)
    {
        if ($id) {
            $this->idToDelete = $id;
            $this->confirm = true;
        } else {
            $this->confirm = false;
        }
    }

    public function openForm($fs)
    {
        $this->passwordUpdate = false;
        if ($fs) {
            $this->isEditing = false;
            $this->isOpen = true;
        } else {
            $this->isEditing = true;
            $this->isOpen = true;
        }
    }

    public function closeAlert()
    {
        session()->forget('message');
    }

    public function toggle($component)
    {

        if ($component === 'user') {
            // dd('user', $component);
            $this->user = true;
            $this->role = false;
            $this->permission = false;
        } elseif ($component === 'role') {
            // dd('role', $component);
            $this->user = false;
            $this->role = true;
            $this->permission = false;
        } else {
            // dd('permission', $component);
            $this->user = false;
            $this->role = false;
            $this->permission = true;
        }
    }

    public function openRoles($id)
    {
        $this->selectAllRoles = false;
        $user = User::find($id);
        $this->allRoles = Role::all();

        if ($user) {
            $this->userRoles = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->pluck('roles.name') // Get permission names instead of IDs
                ->toArray();
            $this->userId = $user->id;
            $this->selectedUser = $user->full_name;
            $this->roles = $this->userRoles;
            $this->showRoles = true;
        }
    }

    public function addRolesToUser(Request $request)
    {
        $user = User::find($this->userId);
        if ($user) {
            $user->syncRoles($this->roles);
            logActivity('add roles to user', 'app\Models\User', $user->id, $this->roles);

            $request->session()->flash('message', 'وظایف کاربر موفقانه ویرایش گردید');
            $this->showRoles = false;
        }
    }

    public function toggleSelectAllRoles()
    {
        if ($this->selectAllRoles) {
            $this->roles = $this->allRoles->pluck('name')->toArray();
        } else {
            $this->roles = [];
        }
    }

    public function updatedRoles()
    {
        $this->selectAllRoles = count($this->roles) === $this->allRoles->count();
    }

    //Table data section
    public function tableData()
    {
        $data;
        $dataCount;

        if ($this->perPage) {
            $data = User::paginate($this->perPage);
            $dataCount = $data->total();
        } else {
            $data = User::all();
        }

        return $data;
    }
    //life cycle hooks
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function loadTableData()
    {
        $this->isDataLoaded = true;
    }
    public function render()
    {

        return view('livewire.user.register', [

            'users' => $this->isDataLoaded ? $this->tableData() : collect(),
            'departments' => $this->isDataLoaded ? Department::all() : collect(),

        ]);
    }
}
