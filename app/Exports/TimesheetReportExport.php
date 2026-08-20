<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\TimeSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class TimesheetReportExport implements FromArray, WithHeadings
{

     protected $data;

     public function __construct($data = [])
     {
          $this->data = $data;
     }

     public function array(): array
     {
          $exportData = [];


          foreach ($this->data as $row)
          {
               try
               {
                    $exportData[] = [
                         // Auth::user()->employeeIdFormat($row->employee_id),
                         $row->employee_id,
                         $row->employees->name ?? "",
                         Auth::user()->dateFormat($row->date),
                         $row->hours,
                         $row->remark
                    ];
               }
               catch (\Exception $ex)
               {
                    Log::error($ex);
               }
               catch (\Throwable $ex)
               {
                    Log::error($ex);
               }
          }

          return $exportData;
     }
     public function headings(): array
     {
          return [
               "ID",
               "Employee Name",
               "Date",
               "Hour",
               "Remark",
          ];
     }
}
