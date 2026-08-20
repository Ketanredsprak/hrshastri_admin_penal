<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class BirthdayListReportExport implements FromArray, WithHeadings
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
                         $row->branch->name,
                         $row->email,
                         Auth::user()->dateFormat($row->dob)
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
               "Branch",
               "Email",
               "Birth Date",
          ];
     }
}
