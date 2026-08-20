<?php

namespace App\Exports;

use App\Models\AttendanceEmployee;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EmployeesSalarySlipPreviewExcelFileDownload implements FromView, WithEvents
{
     protected $data;

     public function __construct(array $data)
     {
          $this->data = $data;
     }

     public function view(): View
     {
          return view('report.employees-salary-slip-preview-excel-file-download', [
               'data' => $this->data,
          ]);
     }

     public function registerEvents(): array
     {
          return [
               AfterSheet::class => function (AfterSheet $event)
               {
                    $showDecimals = $this->data['showDecimalPoints'] ?? true;
                    $decimalPoints = (int) ($this->data['decimalPoints'] ?? 2);
                    $decimalPoints = max(0, min(4, $decimalPoints));

                    $formatCode = $showDecimals
                         ? '#,##0.' . str_repeat('0', $decimalPoints)
                         : '#,##0';

                    $event->sheet->getDelegate()
                         ->getStyle('C3:AZ500')
                         ->getNumberFormat()
                         ->setFormatCode($formatCode);
               }
          ];
     }
}
