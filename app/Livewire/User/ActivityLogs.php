<?php

namespace App\Livewire\User;

use App\Models\ActivityLog;
use Livewire\Component;

use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActivityLogs extends Component
{
    use WithPagination;


    public $perPage = 5;
    public $currentPage = 1;
    public $showMore = false;
    public $isDataLoaded = false;
    public $sortField = 'activity_logs.created_at';
    public $sortDirection = 'desc';

    public $search;
    public $noData = false;
    public $details;
    public $more = false;

    public function toggleMore($id)
    {
        if ($id) {

            $this->details = ActivityLog::find($id);

            $this->more = true;
        } else {
            $this->more = false;
        }
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

    public function showModal($logId)
    {
        // Fetch the log details based on the ID
        $log = ActivityLog::find($logId); // Replace `Log` with your actual model
        if ($log) {
            $this->logDetails = [
                'os' => $log->os,
                'browser' => $log->browser,
                'device_type' => $log->device_type,
                'city' => $log->city,
                'country' => $log->country,
                'description' => $log->description,
            ];
        }
        $this->showMore;
    }
    //Table Data Rendering section
    public function tableData()
    {
        $data;
        $dataCount;

        $query = ActivityLog::query()->join('users', 'users.id', '=', 'activity_logs.user_id')
            ->select('activity_logs.*', 'users.full_name', 'activity_logs.created_at as created_at');

        if (!empty($this->search)) {
            $columns = ['users.full_name', 'activity_logs.action', 'activity_logs.ip_address', 'activity_logs.device_type'];
            $query->where(function ($q) use ($columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $this->search . '%');
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
    public function refresh()
    {
        $this->loadTableData();
    }

    public function render()
    {
        return view('livewire.user.activity-logs', [
            'logs' => $this->isDataLoaded ? $this->tableData() : collect(),
        ]);
    }
}
