<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Holiday;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HolidayExport implements FromCollection, WithHeadings
{

     private $startDate, $endDate;
     function __construct($startDate, $endDate)
     {
          $this->startDate = $startDate;
          $this->endDate = $endDate;
     }

     public function collection()
     {
          $holidays = Holiday::where('created_by', '=', Auth::user()->creatorId())
               ->where('start_date', '>=', $this->startDate)
               ->where('end_date', '<=', $this->endDate)
               ->get();


          $exportData = [];

          $emp = Employee::where('user_id', '=', Auth::user()->id)->first();

          foreach ($holidays as $row)
          {
               $branch_ids = json_decode($row->branch_id) ?? [];
               $department_ids = json_decode($row->department_id) ?? [];

               // scenarios
               // scenarios 1 : if all branched selected, all departments selected and all employees selected | then show announcement to employee
               // scenarios 2 : if not all branches selected, but for all departments are selected | then show announcement to employee
               // scenarios 2.1 : if all employees are selected or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch

               $rowData = [];

               if (Auth::user()->type == 'employee')
               {
                    // if all branchs, all departments
                    if (in_array(0, $branch_ids) && in_array(0, $department_ids))
                    {
                         $rowData = $row;
                    }

                    // if not all branches, but for all departments
                    else if (!in_array(0, $branch_ids) && in_array(0, $department_ids))
                    {
                         // check if all employees are allowed or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch
                         if (in_array($emp->branch_id, $branch_ids))
                         {
                              $rowData = $row;
                         }
                    }

                    // if not all branches and not all departments
                    else if (!in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         if (in_array($emp->branch_id, $branch_ids) && in_array($emp->department_id, $department_ids))
                         {
                              $rowData = $row;
                         }
                    }
                    // if all branches and not all departments
                    else if (in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         if (in_array($emp->department_id, $department_ids))
                         {
                              $rowData = $row;
                         }
                    }
               }
               else
               {
                    $rowData = $row;
               }


               if (!empty($rowData) && $rowData != null)
               {
                    $branchIds = json_decode($rowData->branch_id);

                    $branchName = "";
                    if (in_array(0, $branchIds))
                    {
                         $branchName = "All";
                    }
                    else
                    {
                         $branchNames = Branch::select("id", "name")->whereIn("id", $branchIds)->get()->pluck("name")->toArray();
                         $branchName = implode(", ", $branchNames);
                    }


                    $exportData[] = [
                         $branchName,
                         $rowData->start_date,
                         $rowData->end_date,
                         $rowData->occasion,
                         Employee::login_user($rowData->created_by)
                    ];
               }
          }

          if (empty($exportData) || count($exportData) <= 0)
          {
               throw new Exception("No Data to export");
          }

          return collect($exportData);
     }

     public function headings(): array
     {
          return [
               "Branch",
               "Start Date",
               "End Date",
               "Occasion",
               "Created By"
          ];
     }
}
