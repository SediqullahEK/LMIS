<?php

namespace App\Livewire\Applicants;

use Livewire\Component;
use App\Models\Province;
use App\Models\Individual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Individuals extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $perPage = 5;
    public $currentPage = 1;
    public $isDataLoaded = false;
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isEditing = false;
    public $isOpen = false;
    public $confirm = false;
    public $individualId;
    public $search;
    public $photo;
    public $existing_photo_path;
    public $idToDelete;
    public $name_dr;
    public $name_en;
    public $noData = false;
    public $fathers_name;
    public $tin_num;
    public $date_of_birth;
    public $nationality;
    public $tazkira_num;
    public $phone;
    public $province;
    public $district;

    //Individual CRUD section
    protected $rules = [
        'name_dr' => 'required|regex:/^[\p{Script=Arabic}\s]+$/u|max:255',
        'name_en' => 'required|regex:/^[A-Za-z ]+$/|max:255',
        'fathers_name' => 'required|string|max:255',
        'tin_num' => 'required|numeric|unique:individuals,tin_num',
        'tazkira_num' => 'required|numeric|unique:individuals,tazkira_num',
        'province' => 'required',
        'date_of_birth' => 'required',
        'nationality' => 'nullable',
        'district' => 'required|string',
        'photo' => 'nullable|image|max:1024',
    ];

    protected $messages = [
        'name_dr.required' => 'نام دری لازمی میباشد.',
        'name_dr.regex' => 'نام را به زبان دری وارد کنید.',
        'name_en.required' => 'نام انگلیسی لازمی میباشد.',
        'name_en.regex' => 'نام را به زبان انگلیسی وارد کنید.',
        'tin_num.unique' => 'نمبر تشخیصه ذیل در سیستم موجود است.',
        'tazkira_num.unique' => 'نمبر تذکره ذیل در سیستم موجود است.',
    ];

    // Real-time validation for individual fields
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function addIndividual()
    {
        // Validate all fields
        $validatedData = $this->validate();

        $imagePath = '';
        if ($this->photo != '') {
            try {
                $imageName = $this->name_en . time() . '.' . $this->photo->getClientOriginalExtension();
                $imagePath = $this->photo->storeAs('individuals_photos', $imageName, 'public');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        $done = Individual::create([
            'name_dr' => $validatedData['name_dr'],
            'name_en' => $validatedData['name_en'],
            'f_name' => $validatedData['fathers_name'],
            'photo_path' =>  $imagePath ?? null,
            'tin_num' => $validatedData['tin_num'],
            'tazkira_num' => $validatedData['tazkira_num'],
            'province_id' => $validatedData['province'],
            'district' => $validatedData['district'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'nationality' => $validatedData['nationality'],
            'phone' => $this->phone ?? null,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);


        logActivity('create', 'App\Models\Individual', $done->id, $done);
        session()->flash('message', 'شخص موفقانه اضافه گردید');
        $this->resetForm();
        $this->isOpen = false;
    }


    public function editIndividual($id)
    {
        $this->isEditing = true;
        $this->individualId = $id;
        $this->resetForm();

        $individual = Individual::find($id);

        $this->name_dr = $individual->name_dr;
        $this->name_en = $individual->name_en;
        $this->fathers_name = $individual->f_name;
        $this->tin_num = $individual->tin_num;
        $this->tazkira_num = $individual->tazkira_num;
        $this->existing_photo_path = $individual->photo_path;
        $this->phone = $individual->phone;
        $this->date_of_birth = $individual->date_of_birth;
        $this->nationality = $individual->nationality;
        $this->province = $individual->province_id;
        $this->district = $individual->district;

        $this->isOpen = true;
    }

    public function updateIndividual()
    {
        // Find the user by ID
        $individual = Individual::find($this->individualId);

        $validationRules = [];

        if ($this->name_dr !== $individual->name_dr) {
            $validationRules['name_dr'] = 'required|required|regex:/^[\p{Script=Arabic}\s]+$/u|max:255|max:255';
        }
        if ($this->name_en !== $individual->name_en) {
            $validationRules['name_en'] = 'required|required|regex:/^[A-Za-z ]+$/|max:255|max:255';
        }

        if ($this->fathers_name !== $individual->f_name) {
            $validationRules['fathers_name'] = 'required|string|max:255';
        }

        if ($this->tin_num !== $individual->tin_num) {
            $validationRules['tin_num'] = 'required|string|unique:individuals,tin_num';
        }

        if ($this->tazkira_num !== $individual->tazkira_num) {
            $validationRules['tazkira_num'] = 'required|numeric|unique:individuals,tin_num';
        }
        if ($this->province !== $individual->province_id) {
            $validationRules['province'] = 'required';
        }
        if ($this->district !== $individual->district) {
            $validationRules['district'] = 'required|string';
        }
        if ($this->date_of_birth !== $individual->date_of_birth) {
            $validationRules['date_of_birth'] = 'required';
        }

        if ($this->nationality !== $individual->nationality) {
            $validationRules['nationality'] = 'nullable';
        }
        if ($this->phone !== $individual->phone) {
            $validationRules['phone'] = 'nullable';
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
                $imageName = $this->name_en . '_' . $uniqueSuffix . '.' . $this->photo->getClientOriginalExtension();
                $imagePath = $this->photo->storeAs('individuals_photos', $imageName, 'public');

                // Update the user's profile photo path
                $individual->photo_path = $imagePath;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        // Update only changed attributes
        if (isset($validatedData['name_dr'])) {
            $individual->name_dr = $validatedData['name_dr'];
        }
        if (isset($validatedData['name_en'])) {
            $individual->name_en = $validatedData['name_en'];
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
    public function deleteIndividual(Request $request)
    {
        $individual = Individual::find($this->idToDelete);
        if ($individual) {
            // if ($individual->photo_path) {
            //     Storage::disk('public')->delete($individual->photo_path);
            // }
            $individual->is_deleted = true;
            $individual->deleted_by = auth()->user()->id;
            $individual->deleted_at = now();
            $individual->save();
            logActivity('delete', 'App\Models\Companies', $individual->id);
            $request->session()->flash('message', 'شخص موفقانه حذف شد');
            $this->confirm = false;
            $this->loadTableData();
        } else {
            $request->session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
    }


    // alerts/modals section
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
        $this->name_dr = '';
        $this->name_en = '';
        $this->fathers_name = '';
        $this->tin_num = '';
        $this->tazkira_num = '';
        $this->phone = '';
        $this->date_of_birth = '';
        $this->nationality = '';
        $this->province = 0;
        $this->district = '';
        $this->photo = '';
        $this->existing_photo_path = '';
        $this->resetValidation();
    }
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
    //Table Data Rendering section
    public function tableData()
    {
        $data;
        $dataCount;

        // Start building the query
        $query = Individual::query();

        // Apply search filter if the search input is not empty
        if (!empty($this->search)) {
            $columns = ['name_dr', 'f_name', 'tin_num', 'tazkira_num']; // Replace with your visible column names
            $query->where(function ($q) use ($columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', $this->search . '%');
                }
            });
        }
        if ($query->get()->isEmpty()) {
            $this->noData = true;
        } else {
            $this->noData = false;
        }

        // Pagination logic
        if ($this->perPage) {
            $data = $query->where('is_deleted', false)
                ->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
            $this->currentPage = $data->currentPage();
            $dataCount = $data->total();
        } else {
            $data = $query->orderBy($this->sortField, $this->sortDirection)->get();
        }

        return $data;
    }

    public function loadTableData()
    {
        $this->isDataLoaded = true;
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

    public function render()
    {
        return view('livewire.applicants.individuals', [
            'individuals' => $this->isDataLoaded ? $this->tableData() : collect(),
            'provinces' => $this->isDataLoaded ? Province::all() : collect(),
        ]);
    }
}
