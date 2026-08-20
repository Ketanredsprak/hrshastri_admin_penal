<?php

namespace App\Exports;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EmployeeAttendanceGeofencingExcelExport implements FromView, WithEvents
{
     protected $data;

     public function __construct(array $data)
     {
          $this->data = $data;
     }

     public function view(): View
     {
          $start_date = $this->data["start_date"];
          $end_date = $this->data["end_date"];
          $data = $this->data["data"];

          $monthDates = $this->data["monthDates"];

          return view('report.employee-attendance-geofencing-excel-export', [
               'data' => $data,
               'monthDates' => $monthDates,
          ]);
     }

     public function registerEvents(): array
     {
          return [
               AfterSheet::class => function (AfterSheet $event)
               {
                    // Merge columns B and C
                    // $event->sheet->getDelegate()->mergeCells('B1:C1');
               }
          ];
     }
}
