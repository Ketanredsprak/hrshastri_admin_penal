<?php

namespace App\Exports;

use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LateComingExport implements FromView, WithEvents
{
     protected $data;

     public function __construct(array $data)
     {
          $this->data = $data;
     }

     public function view(): View
     {
          $month = $this->data["month"];
          $year = $this->data["year"];

          $data = $this->data["data"];
          $employeeLateDataFinal = $this->data["employeeLateDataFinal"];

          $monthDates = $this->data["monthDates"];

          return view('report.late-coming-export', [
               'data' => $data,
               'monthDates' => $monthDates,
               'employeeLateDataFinal' => $employeeLateDataFinal,
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
