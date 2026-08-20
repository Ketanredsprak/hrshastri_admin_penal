<?php

namespace App\Exports;

use App\Models\AttendanceEmployee;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EmployeeAttendanceImportSampleExcelFileDownload implements FromView, WithEvents
{
     protected $data;

     public function __construct(array $data)
     {
          $this->data = $data;
     }

     public function view(): View
     {
          $monthDates = $this->data["monthDates"];


          return view('report.employee-attendance-import-sample-excel-file-download', [
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
