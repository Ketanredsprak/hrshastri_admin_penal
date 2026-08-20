<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class NewJoineeReportExport implements FromArray, WithHeadings
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
                         $row->name ?? "",
                         Auth::user()->dateFormat($row->company_doj),
                         Auth::user()->dateFormat($row->probation_period_date),
                         $row->branch->name ?? '',
                         $row->department->name ?? '',
                         $row->designation->name ?? ''
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
               "Joining Date",
               "End of Probation",
               "Branch",
               "Department",
               "Designation",
          ];
     }
}
