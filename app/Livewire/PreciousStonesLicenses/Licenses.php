<?php

namespace App\Livewire\PreciousStonesLicenses;

use App\Models\PSPLicense;
use Livewire\Component;

class Licenses extends Component
{
    public $perPage = 5;
    public $currentPage = 1;
    public $isDataLoaded = false;
    public $noData = false;
    public $sortField = 'psp_licenses.created_at';
    public $sortDirection = 'asc';


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
        $query = PSPLicense::query();

        // Apply search filter if the search input is not empty
        // if (!empty($this->search)) {
        //     $columns = ['name_dr', 'f_name', 'tin_num', 'tazkira_num']; // Replace with your visible column names
        //     $query->where(function ($q) use ($columns) {
        //         foreach ($columns as $column) {
        //             $q->orWhere($column, 'like', $this->search . '%');
        //         }
        //     });
        // }

        if ($query->get()->isEmpty()) {
            $this->noData = true;
        } else {
            $this->noData = false;
        }

        // Pagination logic
        if ($this->perPage) {
            $data = $query->where('psp_licenses.is_deleted', false)
                ->join('individuals', 'psp_licenses.individual_id', 'individuals.id')
                ->join('companies', 'psp_licenses.company_id', 'companies.id')
                ->join('precious_semi_precious_stones', 'precious_semi_precious_stones.id', 'psp_licenses.stone_id')
                ->select(
                    'psp_licenses.*',
                    'individuals.name_dr as individual_name',
                    'companies.name_dr as company_name',
                    'individuals.tin_num as tin_num',
                    'companies.license_num as license_num',
                    'precious_semi_precious_stones.name as stone'
                )
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
        return view('livewire.precious-stones-licenses.licenses', [
            'licenses' => $this->isDataLoaded ? $this->tableData() : collect(),
        ]);
    }
}
