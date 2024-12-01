<?php

namespace App\Exports;

use App\Models\Accident;
use \Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccidentsExport implements FromView
{
    protected $query;
    protected $columns;
    protected $startDate;
    protected $endDate;
    
    public function __construct(array $columns, $startDate, $endDate, array $selectedRowKeys) {
        $this->columns = $columns;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->query = Accident::query()->with('injuredPerson', 'reportingUser');

        if(count($selectedRowKeys) > 0) {
            $this->query = $this->query->whereIn('id', $selectedRowKeys);
        }
    }

    public function view(): View
    {
        return view('exports.accidents', [
            'accidents' => $this->query->get(),
            'columns' => $this->columns,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
