<?php

namespace App\Livewire\PreciousStonesLicenses;

use App\Models\Company;
use App\Models\Individual;
use App\Models\Province;
use App\Models\PSStone;
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
    public $stoneColorDr;
    public $stoneColorEn;
    public $stoneAmount;

    public function generateMaktoobs()
    {

        $validatedData = $this->validate([
            'letterNumber' => 'required|numeric',
            'stone' => 'required',
            'stoneColorDr' => 'required|regex:/^[\p{Script=Arabic}\s]+$/u|max:255',
            'stoneColorEn' => 'required|regex:/^[A-Za-z ]+$/|max:255',
            'stoneAmount' => 'required|string',

        ], [
            'tin_num.unique' => 'نمبر تشخیصه ذیل در سیستم موجود است.',
            'license_num.unique' => 'نمبر جواز ذیل در سیستم موجود است.',

        ]);
        if ($individualDetails) {
            $this->validate(['tazkiraNumber' => 'required']);
        }
        if ($companyDetails) {
            $this->validate(['licenseNumber' => 'required']);
        }
        if (!$companyDetails && !$individualDetails) {
            $this->validate(['tazkiraNumber' => 'required']);
        }

        $licenseDetaisl = psp::where('tazkira_num', $this->tazkiraNumber)->first();
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
                $this->companyId = DB::connection('LMIS')->table('company_shareholders')
                    ->select('company_id')->where('individual_id', $individual->id)->first();
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
            $company = Company::find($this->companyId->company_id);

            if ($company) {
                $this->companyId = $company->id;
                $this->companyName = $company->name;
                $this->companyTINNumber = $company->tin_num;
                $this->address = $company->tin_num;
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
                $this->address = $company->tin_num;
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
    public function resetIndividualData($flag = 0)
    {
        //$flags are used for switches from blade file as the form fields for data search shouldn't get reset
        if ($flag) {
            $this->tazkiraNumber = null; //data search field
            $this->province = '';
            $this->name = '';
            $this->fathersName = '';
            $this->tinNumber = '';
        } else {
            $this->province = '';
            $this->name = '';
            $this->fathersName = '';
            $this->tinNumber = '';
        }
    }
    public function resetCompanyData($flag = 0)
    {
        //$flags are used for switches from blade file as the form fields for data search shouldn't get reset
        if ($flag) {
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
    public function render()
    {
        if (($this->individualDetails && ! $this->letterSubject) || ($this->companyDetails && ! $this->letterSubject)) {
            $this->individualDetails = false;
            $this->companyDetails = false;
            $this->addError('letterNumber', 'نخست معلومات عریضه را وارد کنید');
        } else {
            $this->resetErrorBag('letterNumber');
        }
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
        return view('livewire.precious-stones-licenses.maktoobs', [
            'stones' => PSStone::all()
        ]);
    }
}
