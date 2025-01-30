<?php

namespace App\Livewire\PreciousStonesLicenses;

use App\Models\Company;
use App\Models\Individual;
use App\Models\Province;
use App\Models\PSPLicense;
use App\Models\PSStone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Licenses extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $perPage = 5;
    public $modalPerPage = 5;
    public $currentPage = 1;
    public $isDataLoaded = false;
    public $noData = false;
    public $sortField = 'psp_licenses.created_at';
    public $sortDirection = 'desc';
    public $isEditing = false;
    public $isOpen = false;
    public $maktoobModal = false;
    public $loadMaktoobs = false;
    public $maktoobScan = false;
    public $confirm = false;
    public $individualId;
    public $search;
    public $searchedMaktoob;
    public $idToDelete;
    public $selectedMaktoobs = [];
    public $quantity;
    public $finalRoyalityPerQuantity;
    public $stone;
    public $stoneColorDr;
    public $stoneColorEn;
    public $stoneAmount;
    public $requestId;
    public $licenseId;
    public $letterNumber;
    public $letterNumberError;
    public $error;
    public $letterSubject;
    public $maktoobsScans;
    public $individualDetails = false;
    public $noLicenseMaktoob = false;
    public $maktoobModalState = false;
    public $name;
    public $province;
    public $tinNumber;
    public $fathersName;
    public $tazkiraNumber;
    public $companyDetails = false;
    public $companyName;
    public $companyTINNumber;
    public $licenseNumber;
    public $address;
    public $companyId;

    //Individual CRUD section
    protected $rules = [
        'letterNumber' => 'required|numeric|unique:psp_licenses,letter_id',
        'stone' => 'required|not_in:0',
        'stoneColorDr' => 'required|regex:/^[\p{Script=Arabic}\s]+$/u|max:255',
        'stoneColorEn' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
        'stoneAmount' => 'required|numeric',

    ];

    protected $messages = [
        'stone.*' => 'انتخاب سنگ لازمی میباشد',
        'letterNumber.required' => 'نمبر عریضه لازمی میباشد',
        'letterNumber.unique' => 'مشخصات ذیل در سیستم از قبل موجود است',
        'stoneColorDr.required' => 'رنگ سنگ دری لازمی میباشد',
        'stoneColorEn.required' => 'رنگ سنگ انگلیسی لازمی میباشد',
        'stoneAmount.required' => 'مقدار سنگ لازمی میباشد',
        'stoneColorDr.regex' => 'رنگ سنگ را به زبان دری وارد کنید',
        'stoneColorEn.regex' => 'رنگ سنگ را به زبان انگلیسی وارد کنید',
    ];

    // Real-time validation for individual fields
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    //CRUD section
    public function addLicense()
    {
        // dd('called');
        $validatedData = $this->validate();



        if ($this->individualDetails) {
            $this->resetErrorBag('tazkiraNumber');
            $this->validate(['tazkiraNumber' => 'required'], [
                'tazkiraNumber.required' => 'نمبر تذکره متقاضی لازمی میباشد',
            ]);
        }
        if ($this->companyDetails) {
            $this->validate(['licenseNumber' => 'required'], [
                'licenseNumber.required' => 'نمبر جواز متقاضی لازمی میباشد',
            ]);
        }
        if (!$this->companyDetails && !$this->individualDetails) {
            $this->validate(['individualDetails' => 'required|accepted'], [
                'individualDetails.*' => 'مشخصات متقاضی لازمی میباشد',
            ]);
        }


        DB::transaction(
            function () {
                $license = PSPLicense::create([
                    'created_by' => auth()->user()->id,
                    'letter_id' => $this->letterNumber,
                    'individual_id' => $this->individualId,
                    'company_id' => $this->companyId ?? null,
                    'stone_color_dr' => $this->stoneColorDr,
                    'stone_color_en' => $this->stoneColorEn,
                    'stone_id' => $this->stone,
                    'stone_amount' => $this->stoneAmount,
                ]);
                $license->update([
                    'serial_number' => 'momplcs0' . $license->id,
                ]);
                logActivity('create', 'app\Models\PSPLicense', $license->id);
                session()->flash('message', 'مکاتیب موفقانه ایجاد گردید.');
                $this->isOpen = false;
                $this->resetForm();
            }
        );
    }
    public function editLicense($id)
    {
        $this->isEditing = true;
        $this->licenseId = $id;
        $this->resetForm();

        $license = PSPLicense::find($id);
        $this->letterNumber = $license->letter_id;
        $this->checkLetterData();

        $this->stone = $license->stone_id;
        $this->loadStoneDetails();
        $this->stoneColorDr = $license->stone_color_dr;
        $this->stoneColorEn = $license->stone_color_en;
        $this->stoneAmount = $license->stone_amount;
        $this->individualId = $license->individual_id;
        $this->loadIndividualData();
        $this->individualDetails = true;
    }


    public function updateLicense()
    {
        $license = PSPLicense::findOrFail($this->licenseId);

        $validationRules = [];
        $messages = [];
        $changedFields = [];

        if ($this->individualDetails && $this->individualId != $license->individual_id) {
            $this->resetErrorBag('tazkiraNumber');
            $validationRules['tazkiraNumber'] = 'required|numeric';
            $messages['tazkiraNumber.required'] = 'نمبر تذکره متقاضی لازمی میباشد';

            $changedFields['individual_id'] = $this->individualId;
        }
        if ($this->companyDetails && $this->companyId != $license->company_id) {
            $validationRules['licenseNumber'] = 'required|numeric';
            $messages['licenseNumber.required'] = 'نمبر جواز متقاضی لازمی میباشد';
            $changedFields['company_id'] = $this->companyId;
        } else {
            $changedFields['company_id'] = $this->companyId;
        }
        if (!$this->companyDetails && !$this->individualDetails) {
            $validationRules['individualDetails'] = 'required|accepted';
            $messages['individualDetails.required'] = 'مشخصات متقاضی لازمی میباشد';
            $changedFields['company_id'] = $this->companyId;
        }

        if ($this->letterNumber !== $license->letter_id) {
            $validationRules['letterNumber'] = [
                'required',
                'numeric',
                Rule::unique('psp_licenses', 'letter_id')->ignore($this->licenseId)
            ];
            $messages['letterNumber.required'] = 'نمبر عریضه لازمی میباشد';
            $messages['letterNumber.unique'] = 'مشخصات ذیل در سیستم از قبل موجود است';
            $changedFields['letter_id'] = $this->letterNumber;
        }

        if ($this->stone !== $license->stone_id) {
            $validationRules['stone'] = 'required|not_in:0';
            $messages['stone.*'] = 'انتخاب سنگ لازمی میباشد';
            $changedFields['stone_id'] = $this->stone;
        }

        if ($this->stoneColorDr !== $license->stone_color_dr) {
            $validationRules['stoneColorDr'] = 'required|regex:/^[\p{Script=Arabic}\s]+$/u|max:255';
            $messages['stoneColorDr.required'] = 'رنگ سنگ دری لازمی میباشد';
            $messages['stoneColorDr.regex'] = 'رنگ سنگ را به زبان دری وارد کنید';
            $changedFields['stone_color_dr'] = $this->stoneColorDr;
        }


        if ($this->stoneColorEn !== $license->stone_color_en) {
            $validationRules['stoneColorEn'] = 'required|regex:/^[A-Za-z\s]+$/|max:255';
            $messages['stoneColorEn.required'] = 'رنگ سنگ انگلیسی لازمی میباشد';
            $messages['stoneColorEn.regex'] = 'رنگ سنگ را به زبان انگلیسی وارد کنید';
            $changedFields['stone_color_en'] = $this->stoneColorEn;
        }

        if ($this->stoneAmount !== $license->stone_amount) {
            $validationRules['stoneAmount'] = 'required|numeric';
            $messages['stoneAmount.required'] = 'مقدار سنگ لازمی میباشد';
            $changedFields['stone_amount'] = $this->stoneAmount;
        }

        if (empty($changedFields)) {
            session()->flash('error', 'هیچ تغییر جدید در معلومات ایجاد نشده!');
            return;
        } else {

            $validatedData = $this->validate($validationRules, $messages);
        }

        $beforeState = $license->toArray();

        $license->fill($changedFields);
        $license->updated_by = auth()->id();
        // dd($license);
        $done = $license->save();

        logActivity('update', 'App\Models\PSPLicense', $license->id, [
            'قبلا' => $beforeState,
            'بعدا' => $license->toArray(),
        ]);

        if ($done) {
            $this->isOpen = false;
            session()->flash('message', 'جواز موفقانه ویرایش گردید');
            $this->resetForm();
        }
    }

    public function deleteLicense()
    {
        $license = PSPLicense::findOrFail($this->idToDelete);
        if ($license) {
            $license->is_deleted = true;
            $license->deleted_by = auth()->user()->id;
            $license->deleted_at = now();
            $license->save();
            logActivity('delete', 'App\Models\PSPLicense', $license->id);
            session()->flash('message', 'جواز موفقانه حذف شد');
            $this->confirm = false;
            $this->loadTableData();
        } else {
            session()->flash('error', 'خطا در پروسه حذف');
            $this->confirm = false;
        }
    }


    public function loadStoneDetails()
    {
        if ($this->stone) {
            $stone = PSStone::find($this->stone);
            if ($stone) {
                $this->quantity = $stone->quantity;
                $this->finalRoyalityPerQuantity = $stone->final_royality_after_negotiations;
            } else {
                session()->flash('error', 'خطا در پروسه جستجوی معلومات سنگ');
            }
        }
    }

    public function loadIndividualData()
    {
        if ($this->tazkiraNumber || $this->individualId) {
            $individual = Individual::when($this->tazkiraNumber, function ($query) {
                $query->where('tazkira_num', $this->tazkiraNumber);
            })->when(!$this->tazkiraNumber && $this->individualId, function ($query) {
                $query->where('id', $this->individualId);
            })->first();
            if ($individual) {
                $this->province = Province::where('id', $individual->province_id)->first()->name;
                $this->name = $individual->name_dr;
                $this->fathersName = $individual->f_name;
                $this->tinNumber = $individual->tin_num;
                $this->individualId = $individual->id;
                $this->tazkiraNumber = $individual->tazkira_num;
                $this->companyId = DB::connection('LMIS')
                    ->table('company_shareholders')
                    ->where('individual_id', $individual->id)
                    ->value('company_id');

                if ($this->companyId) {
                    $this->loadCompanyData();
                } else {
                    $this->resetCompanyData(2);
                }

                $this->resetErrorBag('tazkiraNumber');
            } else {
                $this->addError('tazkiraNumber', 'معلومات تذکره نمبر ذیل موجود نیست');
                $this->companyId = null;
                $this->resetIndividualData();
                return;
            }
        }
    }
    public function loadCompanyData()
    {

        if ($this->companyId) {
            // dd('called', $this->companyId);
            $company = Company::find($this->companyId);

            if ($company) {
                $this->companyDetails = true;
                $this->companyId = $company->id;
                $this->companyName = $company->name_dr;
                $this->companyTINNumber = $company->tin_num;
                $this->address = $company->address;
                $this->licenseNumber = $company->license_num;
            } else {
                $this->addError('licenseNumber', 'معلومات جواز نمبر ذیل موجود نیست');
                $this->resetCompanyData();
                return;
            }
        } elseif ($this->licenseNumber) {
            $company = Company::where('license_num', $this->licenseNumber)->first();
            if ($company) {
                $this->companyName = $company->name_dr;
                $this->companyTINNumber = $company->tin_num;
                $this->address = $company->address;
                $this->resetErrorBag('licenseNumber');
            } else {
                $this->addError('licenseNumber', 'معلومات جواز نمبر ذیل موجود نیست');
                $this->resetCompanyData();
                return;
            }
        }
    }
    public function navigateToMaktoobs($id)
    {
        session(['license_id' => $id]);

        return redirect()->route('ps_maktoobs');
    }

    public function checkLetterData()
    {
        $letter = DB::connection('momp_mis')
            ->table('letters')
            ->where('department_operation.department_id', 30)
            ->where('letters.id', $this->letterNumber)
            ->join('operations', 'letters.id', '=', 'operations.letter_id')
            ->join('department_operation', 'operations.department_id', '=', 'department_operation.department_id')
            ->select('letters.id', 'letters.subject')
            ->first();
        // dd('called', $letter);
        if ($letter) {
            $this->letterSubject = $letter->subject;
            $this->letterNumberError = '';
        } else {
            if (!session()->has('errors') || !session('errors')->has('letterNumber')) {
                $this->letterNumberError = 'عریضه ذیل در سیستم موجود نیست!';
            } else {
                $this->letterNumberError = '';
            }
            $this->letterSubject = '';
        }
    }

    public function resetIndividualData($flag = 0)
    {
        if ($flag) {
            $this->tazkiraNumber = null; //data search field
            $this->province = '';
            $this->name = '';
            $this->individualId = null;
            $this->fathersName = '';
            $this->tinNumber = '';
        } else {
            $this->individualId = null;
            $this->province = '';
            $this->name = '';
            $this->fathersName = '';
            $this->tinNumber = '';
        }
    }
    public function resetCompanyData($flag = 0)
    {
        //$flags are used for switches from blade file as the form fields for data search shouldn't get reset
        if ($flag == 2) {
            $this->licenseNumber = null; //data search field
            $this->companyName = '';
            $this->companyTINNumber = '';
            $this->address = '';
            $this->companyDetails = false;
        } else if ($this->companyDetails) {
        } else if ($flag) {
            $this->licenseNumber = null; //data search field
            $this->companyName = '';
            $this->companyTINNumber = '';
            $this->address = '';
        } else {

            $this->companyName = '';
            $this->companyTINNumber = '';
            $this->address = '';
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
    public function openMaktoobsModal($id, $state)
    {
        $this->maktoobModal = true;
        $this->licenseId = $id;

        $this->maktoobModalState = $state;

        $this->loadMaktoobs = true;
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
        $this->resetCompanyData();
        $this->resetIndividualData();
        $this->stone = 0;
        $this->reset([
            'letterNumber',
            'letterSubject',
            'quantity',
            'stoneColorDr',
            'stoneColorEn',
            'stoneAmount'
        ]);
        $this->resetValidation();
        $this->resetPage('maktoobs');
        $this->individualDetails = false;
        $this->companyDetails = false;
        $this->error = '';
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
        // dd('called');
        $data;
        $dataCount;
        // $license = PSPLicense::withCount('maktoobs')->first();
        // dd($license->maktoobs_count, $license->maktoobs->isEmpty());
        $query = PSPLicense::query()
            ->where('psp_licenses.is_deleted', false)
            ->leftJoin('individuals', 'psp_licenses.individual_id', '=', 'individuals.id')
            ->leftJoin('companies', 'psp_licenses.company_id', '=', 'companies.id')
            ->leftJoin('precious_semi_precious_stones', 'precious_semi_precious_stones.id', '=', 'psp_licenses.stone_id')
            ->select(
                'psp_licenses.*',
                'individuals.name_dr as individual_name',
                'companies.name_dr as company_name',
                'individuals.tazkira_num as tazkira_num',
                'companies.license_num as license_num',
                'precious_semi_precious_stones.name as stone',
                DB::raw('(SELECT COUNT(*) FROM psp_licenses_maktoobs WHERE psp_licenses_maktoobs.license_id = psp_licenses.id) as maktoobs_count'),
                DB::raw(now()->timestamp . ' as cache_buster')
            );

        // Apply search filter if the search input is not empty
        if (!empty($this->search)) {
            $columns = ['individuals.name_dr', 'individuals.tazkira_num', 'psp_licenses.letter_id', 'psp_licenses.serial_number', 'companies.name_dr', 'companies.license_num', 'precious_semi_precious_stones.name']; // Columns to search
            $query->where(function ($q) use ($columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $this->search . '%');
                }
            });
        }

        // Check if query has any results
        $this->noData = $query->get()->isEmpty();

        // Pagination logic
        if ($this->perPage) {
            $data = $query->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage, ['*'], 'licenses');

            $this->currentPage = $data->currentPage();
        } else {
            $data = $query->orderBy($this->sortField, $this->sortDirection)
                ->get();
        }
        // dd($data);
        return $data;
    }
    public function loadTableData()
    {
        $this->isDataLoaded = true;
    }
    public function popMaktoobScan($id)
    {

        $this->maktoobScan = true;
    }

    public function maktoobsData()
    {
        $dataQuery = DB::connection('momp_mis')->table('makatebs')
            ->where('department_id', 30);

        if (!empty($this->searchedMaktoob)) {
            $columns = ['subject', 'source', 'type'];
            $dataQuery->where(function ($query) use ($columns) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $this->searchedMaktoob . '%');
                }
            });
        }

        $paginatedMakatebs = $this->modalPerPage ?
            $dataQuery->paginate($this->modalPerPage, ['*'], 'maktoobs')
            :
            $dataQuery->get();

        $licenseMaktoobs = DB::connection('LMIS')->table('psp_licenses_maktoobs')
            ->where('license_id', $this->licenseId)
            ->pluck('license_id', 'maktoob_id')
            ->toArray();

        // Handle transformation when data is paginated or a collection
        if ($paginatedMakatebs instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginatedMakatebs->getCollection()->transform(function ($maktoob) use ($licenseMaktoobs) {
                $maktoob->is_selected = isset($licenseMaktoobs[$maktoob->id]) ? 1 : 0;
                return $maktoob;
            });

            $paginatedMakatebs->setCollection(
                $paginatedMakatebs->getCollection()->sortByDesc('is_selected')->values()
            );

            $this->selectedMaktoobs = $paginatedMakatebs->getCollection()
                ->where('is_selected', 1)
                ->pluck('id')
                ->toArray();
        } else {
            // If all data is retrieved, transform as a collection
            $paginatedMakatebs = collect($paginatedMakatebs)->map(function ($maktoob) use ($licenseMaktoobs) {
                $maktoob->is_selected = isset($licenseMaktoobs[$maktoob->id]) ? 1 : 0;
                return $maktoob;
            });

            $paginatedMakatebs = $paginatedMakatebs->sortByDesc('is_selected')->values();

            $this->selectedMaktoobs = $paginatedMakatebs
                ->where('is_selected', 1)
                ->pluck('id')
                ->toArray();
        }

        $this->noData = $paginatedMakatebs->isEmpty();
        return $paginatedMakatebs;
    }


    public function addMaktoobsToLicenses()
    {

        $selectedMaktoobs = $this->selectedMaktoobs; // Maktoobs selected by the user
        $licenseId = $this->licenseId; // Current license ID

        $result = DB::transaction(function () use ($selectedMaktoobs, $licenseId) {
            // Fetch current maktoobs linked to the license
            $currentLicenseMaktoobs = DB::table('psp_licenses_maktoobs')
                ->where('license_id', $licenseId)
                ->pluck('maktoob_id')
                ->toArray();

            sort($selectedMaktoobs);
            sort($currentLicenseMaktoobs);
            if ($selectedMaktoobs == $currentLicenseMaktoobs) {
                return 0;
            } else {
                // Determine maktoobs to add and remove
                $maktoobsToRemove = array_diff($currentLicenseMaktoobs, $selectedMaktoobs);
                $maktoobsToAdd = array_diff($selectedMaktoobs, $currentLicenseMaktoobs);

                // Remove maktoobs that are no longer selected
                if (!empty($maktoobsToRemove)) {
                    DB::table('psp_licenses_maktoobs')
                        ->where('license_id', $licenseId)
                        ->whereIn('maktoob_id', $maktoobsToRemove)
                        ->delete();
                }

                // Add new maktoobs that are selected
                if (!empty($maktoobsToAdd)) {
                    $maktoobsToInsert = array_map(function ($maktoobId) use ($licenseId) {
                        return [
                            'license_id' => $licenseId,
                            'maktoob_id' => $maktoobId,
                        ];
                    }, $maktoobsToAdd);

                    DB::table('psp_licenses_maktoobs')->insert($maktoobsToInsert);
                }

                // Log activities for added and removed maktoobs
                if (!empty($maktoobsToAdd)) {
                    logActivity('Maktoobs Added', 'LicensesMaktoobs', $licenseId, $maktoobsToAdd);
                }

                if (!empty($maktoobsToRemove)) {
                    logActivity('Maktoobs Removed  from', 'LicensesMaktoobs', $licenseId, $maktoobsToRemove);
                }
                return 1;
            }
        });
        if ($result) {
            $this->maktoobModal = false;


            session()->flash('message', 'مکاتیب جواز موفقانه ویرایش گردید');
            $this->error = '';
        } else {
            $this->error = 'تغییر جدید ایجاد نگردیده است!';
        }
    }

    //life cycle hooks
    public function updatedIndividualDetails()
    {
        if (!$this->letterNumber) {
            $this->individualDetails = false;
            $this->addError('letterNumber', 'نخست معلومات عریضه را وارد کنید');
        }
    }
    public function updatedCompanyDetails()
    {
        if (!$this->letterNumber) {
            $this->companyDetails = false;
            $this->addError('letterNumber', 'نخست معلومات عریضه را وارد کنید');
        }
    }
    public function updatedPerPage()
    {
        $this->resetPage('licenses');
    }
    public function updatedSearch()
    {
        $this->resetPage('licenses');
    }
    public function updatedModalPerPage()
    {
        $this->resetPage('maktoobs');
    }
    public function updatedSearchedMaktoob()
    {
        $this->resetPage('maktoobs');
    }

    public function render()
    {
        if ($this->tazkiraNumber) {
            $this->loadIndividualData();
        } else {
            $this->resetIndividualData();
        }
        if ($this->licenseNumber) {
            $this->loadCompanyData();
        } else {
            $this->resetCompanyData();
        }
        if ($this->letterNumber) {
            $this->checkLetterData();
        } else {
            $this->letterSubject = '';
        }
        return view('livewire.precious-stones-licenses.licenses', [
            'licenses' => $this->isDataLoaded ? $this->tableData() : collect(),
            'maktoobs' => $this->loadMaktoobs ? $this->maktoobsData() : collect(),
            'stones' => PSStone::where('is_deleted', false)->get(),
        ]);
    }
}
