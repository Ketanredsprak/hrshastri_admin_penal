<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthelyAttendanceReportExport implements FromView
{
     protected $data;

     public function __construct(array $data)
     {
          $this->data = $data;
     }

     public function view(): View
     {
          return view('report.monthely-attendance-report-export', ['data' => $this->data]);
     }
}
