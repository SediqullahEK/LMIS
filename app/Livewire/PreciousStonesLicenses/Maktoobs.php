<?php

namespace App\Livewire\PreciousStonesLicenses;

use App\Models\Company;
use App\Models\Individual;
use App\Models\Province;
use App\Models\PSPLicense;
use App\Models\PSStone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use function Laravel\Prompts\select;

class Maktoobs extends Component
{
    public $serialNumber;
    public $licenseId;



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


    public function resetForm()
    {

        $this->letterNumber = null;
        $this->letterSubject = '';
        $this->stone = 0;
        $this->individualDetails = false;
        $this->companyDetails = false;
    }

    public function loadMaktoobs()
    {
        $this->licenseId = session('license_id');

        if ($this->licenseId) {

            $this->serialNumber = PSPLicense::findOrFail($this->licenseId)->serial_number;
        }
        session()->forget('license_id');
    }
    public function render()
    {

        return view('livewire.precious-stones-licenses.maktoobs');
    }
}
