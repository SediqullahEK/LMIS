<?php

namespace App\Livewire\PreciousStonesLicenses;

use App\Models\Company;
use App\Models\Individual;
use App\Models\Province;
use App\Models\PSPLicense;
use App\Models\PSStone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use function Laravel\Prompts\select;

class Maktoobs extends Component
{
    public $requestId;
    public $letterNumber;
    public $letterSubject;
    public $individualDetails = false;
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
    public $individualId;
    public $stone = 0;
    public $stoneColorDr;
    public $quantity;
    public $stoneColorEn;
    public $stoneAmount;

    public $id;


    public function generateMaktoobs()
    {
        // dd('called');
        $validatedData = $this->validate([
            'letterNumber' => 'required|numeric',
            'stone' => 'required',
            'stoneColorDr' => 'required|regex:/^[\p{Script=Arabic}\s]+$/u|max:255',
            'stoneColorEn' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
            'stoneAmount' => 'required|numeric',

        ], [
            'stone.required' => 'انتخاب سنگ لازمی میباشد',
            'stoneColorDr.regex' => 'رنگ سنگ را به زبان دری وارد کنید',
            'stoneColorEn.regex' => 'رنگ سنگ را به زبان انگلیسی وارد کنید',
        ]);

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
            $this->validate(['tazkiraNumber' => 'required'], [
                'tazkiraNumber.required' => 'مشخصات متقاضی لازمی میباشد',
            ]);
        }
        // dd(auth()->user()->id);
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
        if ($license) {
            logActivity('create', 'app\Models\PSPLicense', $license->id);
            session()->flash('message', 'مکاتیب موفقانه ایجاد گردید.');
            $this->resetForm();
        }
    }

    public function loadQauntity()
    {
        if ($this->stone) {
            $stone = PSStone::find($this->stone);
            if ($stone) {
                $this->quantity = $stone->quantity;
            } else {
                session()->flash('error', 'خطا در پروسه جستجوی معلومات سنگ');
            }
        }
    }

    public function loadIndividualData()
    {
        if ($this->tazkiraNumber) {
            $individual = Individual::where('tazkira_num', $this->tazkiraNumber)->first();
            if ($individual) {
                $this->province = Province::where('id', $individual->province_id)->first()->name;
                $this->name = $individual->name_dr;
                $this->fathersName = $individual->f_name;
                $this->tinNumber = $individual->tin_num;
                $this->individualId = $individual->id;
                $this->companyId = DB::connection('LMIS')
                    ->table('company_shareholders')
                    ->where('individual_id', $individual->id)
                    ->value('company_id');
                $this->loadCompanyData();
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
                $this->companyId = $company->id;
                $this->companyName = $company->name;
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
                $this->companyName = $company->name;
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
            $this->resetErrorBag('letterNumber');
        } else {
            $this->addError('letterNumber', 'عریضه ذیل در سیستم موجود نیست!');
            $this->letterSubject = '';
        }
    }

    public function resetForm()
    {

        $this->letterNumber = null;
        $this->letterSubject = '';
        $this->stone = 0;
        $this->individualDetails = false;
        $this->companyDetails = false;
    }

    public function mount(Request $request)
    {
        $this->id = session('license_id');
    }
    public function render()
    {

        return view('livewire.precious-stones-licenses.maktoobs', [
            'stones' => PSStone::all()
        ]);
    }
}
