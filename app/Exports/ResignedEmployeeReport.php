<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ResignedEmployeeReport implements FromArray, WithHeadings
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
                    $status = '';
                    if ($row->status == 0) {
                        $status = 'Pending';
                    } else if($row->status == 1) {
                        $status = 'Approve';
                    } else if($row->status == 2) {
                        $status = 'Rejected';
                    }
                    $exportData[] = [
                         // Auth::user()->employeeIdFormat($row->employee_id),
                         $row->employee()->employee_id,
                         !empty($row->employee()) ? $row->employee()->name : '',
                         $row->employee()->branch->name ?? '',
                         $row->employee()->department->name ?? '',
                         $row->employee()->designation->name ?? '',
                         Auth::user()->dateFormat($row->employee()->company_doj),
                         Auth::user()->dateFormat($row->resignation_date),
                         Auth::user()->dateFormat($row->notice_date),
                         $row->description ?? '',
                         $status
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
               "Department",
               "Designation",
               "Joining Date",
               "Resignation Date",
               "Last Working Date",
               "Reason for Resignation",
               "Status",
          ];
     }
}
