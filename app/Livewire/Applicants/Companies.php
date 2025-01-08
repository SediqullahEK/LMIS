<?php

namespace App\Livewire\Applicants;

use Livewire\Component;

use App\Models\Province;
use App\Models\Company;
use App\Models\Individual;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Companies extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $errorMessage;
    public $modalPerPage = 5;
    public $perPage = 5;
    public $currentPage = 1;
    public $isDataLoaded = false;
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isEditing = false;
    public $isOpen = false;
    public $confirm = false;
    public $showShareholders = false;
    public $companyId;
    public $company;
    public $companyShareholders = [];
    public $individuals = [];
    public $shareholders = []; //newly selected shareholders
    public $sharePercentages = [];
    public $search;
    public $searchedIndividual;
    public $idToDelete;

    public $noData = false;

    public $name;
    public $tin_num;
    public $license_num;
    public $province;
    public $address;

    protected $listeners = ['inputUpdated'];


    //company CRUD section
    public function addCompany()
    {
        // Validate form data
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'tin_num' => 'required|numeric|unique:companies,tin_num',
            'license_num' => 'required|numeric|unique:companies,license_num',
            'province' => 'required',
            'address' => 'required|string',

        ], [
            'tin_num.unique' => 'نمبر تشخیصه ذیل در سیستم موجود است.',
            'license_num.unique' => 'نمبر جواز ذیل در سیستم موجود است.',

        ]);


        $done = Company::create([
            'name' => $validatedData['name'],
            'tin_num' => $validatedData['tin_num'],
            'license_num' => $validatedData['license_num'],
            'province_id' => $validatedData['province'],
            'address' => $validatedData['address'],
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);

        logActivity('create', 'App\Models\Companies');
        // Flash a success message and reset the form
        session()->flash('message', 'شرکت موفقانه اضافه گردید');
        $this->resetForm();
        $this->isOpen = false;
    }

    public function editCompany($id)
    {
        $this->isEditing = true;
        $this->companyId = $id;
        $this->resetForm();

        $company = Company::find($id);

        $this->name = $company->name;
        $this->tin_num = $company->tin_num;
        $this->license_num = $company->license_num;
        $this->province = $company->province_id;
        $this->address = $company->address;
    }

    public function updateCompany()
    {
        // Find the user by ID
        $company = Company::find($this->companyId);

        $validationRules = [];

        if ($this->name !== $company->name) {
            $validationRules['name'] = 'required|string|max:255';
        }

        if ($this->tin_num !== $company->tin_num) {
            $validationRules['tin_num'] = 'required|string|unique:companies,tin_num';
        }

        if ($this->license_num !== $company->license_num) {
            $validationRules['license_num'] = 'required|string|unique:companies,license_num';
        }

        if ($this->province !== $company->province_id) {
            $validationRules['province'] = 'required';
        }
        if ($this->address !== $company->address) {
            $validationRules['address'] = 'required';
        }

        // Only perform validation if there are rules
        if (!empty($validationRules)) {
            $validatedData = $this->validate($validationRules);
        } else {
            session()->flash('error', 'هیچ تغییر جدید در معلومات ایجاد نشده!');
            return;
        }


        // Update only changed attributes
        if (isset($validatedData['name'])) {
            $company->name = $validatedData['name'];
        }

        if (isset($validatedData['tin_num'])) {
            $company->tin_num = $validatedData['tin_num'];
        }

        if (isset($validatedData['license_num'])) {
            $company->license_num = $validatedData['license_num'];
        }

        if (isset($validatedData['province'])) {
            $company->province_id = $validatedData['province'];
        }

        if (isset($validatedData['address'])) {
            $company->address = $validatedData['address'];
        }

        $company->updated_by = auth()->user()->id;
        $done = $company->save();
        logActivity('update', 'App\Models\Companies', $company->id, $company->toarray());
        if ($done) {
            $this->isOpen = false;
            session()->flash('message', 'شرکت موفقانه ویرایش گردید');
            $this->resetForm();
        }
    }
    public function deleteCompany(Request $request)
    {
        $company = Company::find($this->idToDelete);
        if ($company) {
            $company->delete();
            logActivity('delete', 'App\Models\Companies', $company->id);
            $request->session()->flash('message', 'شرکت موفقانه حذف شد');
            $this->confirm = false;
            $this->loadTableData();
        } else {
            $request->session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
    }

    //shareholders section
    public function showShareholders()
    {
        $this->showShareholders = true;
    }

    //on inputUpdated event, the listener is uptop and triggers on a JS event
    public function inputUpdated($shareholderId, $value)
    {

        if ($value === null) {
            // Remove the shareholder entry from sharePercentages
            unset($this->sharePercentages[$shareholderId]);
            $this->errorMessage = '';
        } else {
            // Ensure value is a valid number or string
            $this->sharePercentages[$shareholderId] = (float) $value;  // Ensure it's a number
        }
    }
    public function updatedSharePercentages()
    {
        $this->checkTotalPercentage();
    }

    public function checkTotalPercentage()
    {
        $totalPercentage = array_sum(array_values($this->sharePercentages));

        if ($totalPercentage > 100) {
            $this->errorMessage = 'فیصدی مجموعی سهام داران بالای 100 بوده نمیتواند!';
        } else if ($totalPercentage == 100 || $totalPercentage < 100) {
            $this->errorMessage = '';
        }
    }
    public function loadAllShareholders()
    {
        $query = DB::table('individuals')
            ->leftJoin('company_shareholders', 'individuals.id', '=', 'company_shareholders.individual_id')
            ->select(
                'individuals.*',
                'company_shareholders.shares_in_percentage',
                DB::raw('CASE WHEN company_shareholders.individual_id IS NOT NULL THEN 1 ELSE 0 END AS is_shareholder')
            )
            ->orderBy('is_shareholder', 'desc')
            ->orderBy('company_shareholders.shares_in_percentage', 'desc');

        if (!empty($this->searchedIndividual)) {
            $columns = ['individuals.name', 'individuals.tin_num', 'individuals.tazkira_num'];

            $query->where(function ($q) use ($columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', $this->searchedIndividual . '%');
                }
            });
        }

        $data = $this->modalPerPage
            ? $query->paginate($this->modalPerPage)
            : $query->get();

        $this->noData = $data->isEmpty();

        if (!$this->searchedIndividual && $this->modalPerPage && $data->isEmpty()) {
            session()->flash('error', 'به این تعداد دیتا موجود نیست، صفحه/مقدار دیتا را درست انتخاب کنید!');
        }

        // this is for the seach input field to get hidden when on other pages
        if ($this->modalPerPage) {
            $this->currentPage = $data->currentPage();
        }

        return $data;
    }

    //this is for the shareholders percentage input field on the table
    public function openShareholders($id)
    {
        $company = Company::find($id);

        if ($company) {
            $this->companyShareholders = DB::table('company_shareholders')
                ->join('individuals', 'company_shareholders.individual_id', '=', 'individuals.id')
                ->where('company_shareholders.company_id', $company->id)
                ->select('individuals.id', 'company_shareholders.shares_in_percentage')
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->sortByDesc(function ($item) {
                    return $item['shares_in_percentage'];
                })
                ->toArray();


            $this->companyId = $company->id;
            $this->company = $company->name;

            $this->shareholders = collect($this->companyShareholders)->pluck('id')->toArray();

            foreach ($this->companyShareholders as $shareholder) {
                $this->sharePercentages[$shareholder['id']] = $shareholder['shares_in_percentage'];
            }
            $this->showShareholders = true;

            $this->searchedIndividual = '';
        }
    }

    public function addShareholdersToCompany()
    {
        // Ensure sharePercentages is valid
        if (empty($this->sharePercentages) || !is_array($this->sharePercentages)) {
            $this->errorMessage = 'لیست فیصدی‌ها خالی یا نامعتبر است';
            return;
        }

        // Calculate total percentage
        $totalPercentage = array_sum(array_map('floatval', $this->sharePercentages));

        // Validate total percentage
        if ($totalPercentage > 100) {
            $this->errorMessage = 'فیصدی مجموع بالای 100 فیصد شده است';
            return;
        }

        // Fetch the company
        $company = Company::find($this->companyId);
        if (!$company) {
            $this->errorMessage = 'شرکت یافت نشد';
            return;
        }

        try {
            // Synchronize shareholders and their percentages
            $this->syncShareholders($company, $this->shareholders, $this->sharePercentages);

            // Flash success message
            session()->flash('message', 'سهامداران موفقانه ویرایش گردید');
            $this->showShareholders = false;
        } catch (\Exception $e) {
            // Handle unexpected errors
            $this->errorMessage = 'خطا در ویرایش سهامداران: ' . $e->getMessage();
        }
    }

    public function syncShareholders($company, $shareholders, $sharePercentages)
    {
        DB::transaction(function () use ($company, $shareholders, $sharePercentages) {
            // Fetch current shareholders
            $currentShareholders = DB::table('company_shareholders')
                ->where('company_id', $company->id)
                ->pluck('individual_id')
                ->toArray();

            $shareholdersToAddOrUpdate = [];
            $addedShareholders = [];
            $updatedShareholders = [];

            foreach ($shareholders as $shareholderId) {
                if (isset($sharePercentages[$shareholderId])) {
                    $data = [
                        'company_id' => $company->id,
                        'individual_id' => $shareholderId,
                        'shares_in_percentage' => $sharePercentages[$shareholderId],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];

                    // If shareholder already exists, it's an update
                    if (in_array($shareholderId, $currentShareholders)) {
                        $updatedShareholders[] = $data;
                    } else {
                        // Otherwise, it's a new shareholder
                        $addedShareholders[] = $data;
                    }

                    $shareholdersToAddOrUpdate[$shareholderId] = $data;
                }
            }

            // Determine shareholders to remove
            $shareholdersToRemove = array_diff($currentShareholders, $shareholders);

            // Insert or update shareholders with percentages
            foreach ($shareholdersToAddOrUpdate as $shareholderId => $data) {
                DB::table('company_shareholders')->updateOrInsert(
                    ['company_id' => $company->id, 'individual_id' => $shareholderId],
                    $data
                );
            }

            // Remove shareholders no longer in the list
            if (!empty($shareholdersToRemove)) {
                DB::table('company_shareholders')
                    ->where('company_id', $company->id)
                    ->whereIn('individual_id', $shareholdersToRemove)
                    ->delete();
            }

            // Log activity with detailed actions
            if (!empty($addedShareholders)) {
                logActivity('Shareholders Added', 'CompanyShareholders', $company->id, $addedShareholders);
            }

            if (!empty($updatedShareholders)) {
                logActivity('Shareholders Updated', 'CompanyShareholders', $company->id, $updatedShareholders);
            }

            if (!empty($shareholdersToRemove)) {
                logActivity('Shareholders Removed', 'CompanyShareholders', $company->id, $shareholdersToRemove);
            }
        });
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
        $this->name = '';
        $this->license_num = '';
        $this->province = 0;
        $this->address = '';
        $this->sharePercentages = [];
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
        $query = Company::query();

        // Apply search filter if the search input is not empty
        if (!empty($this->search)) {
            $columns = ['name', 'license_num', 'tin_num']; // Replace with your visible column names
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
            $data = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
            $this->currentPage = $data->currentPage();
            $dataCount = $data->total();
        } else {
            $data = $query->orderBy($this->sortField, $this->sortDirection)->get();
        }

        // Handle invalid page number
        if (!$this->search && $this->perPage != 0 && $dataCount && ($data->currentPage() > ceil($dataCount / $this->perPage))) {
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

        return view('livewire.applicants.companies', [
            'companies' => $this->isDataLoaded ? $this->tableData() : collect(),
            'allIndividuals' => $this->showShareholders ? $this->loadAllShareholders() : collect(),
            'provinces' => $this->isDataLoaded ? Province::all() : collect(),
        ]);
    }
}
