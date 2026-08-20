<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\PaySlip;
use App\Models\Utility;

use App\Models\AttendanceEmployee;
use App\Models\PayslipType;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;
use Illuminate\Support\Facades\Crypt;

class WageReportsExport implements FromArray, WithHeadings, WithStyles
{

     protected $columns;
     protected $data;
     protected $companyName;
     protected $salaryMonth;

     public function __construct($exportData = [])
     {
          // dd("constructor", $exportData);
          $this->columns = $exportData['columns'];
          $this->data = $exportData['data'];
          $this->companyName = $exportData['companyName'];
          $this->salaryMonth = $exportData['salaryMonth'];
     }

     public function array(): array
     {
          $data = [];

          foreach ($this->data as $row)
          {
               $dataRow = [];
               $dataRow[] = $row["sr_no"];
               $dataRow[] = $row["pay_slap_type"];
               $dataRow[] = $row["employee_id"];
               $dataRow[] = $row["name"];
               $dataRow[] = $row["designation"];
               $dataRow[] = $row["department"];
               $dataRow[] = $row["basic_salary"];

               // earnings columns
               foreach (($row["allowances"] ?? []) as $allowancesRow)
               {
                    $dataRow[] = $allowancesRow;
               }

               foreach (($row["commission"] ?? []) as $commissionRow)
               {
                    $dataRow[] = $commissionRow;
               }

               foreach (($row["otherPayment"] ?? []) as $otherPaymentRow)
               {
                    $dataRow[] = $otherPaymentRow;
               }

               // earnings columns
               $dataRow[] = $row["grossEarnings"];

               // deduction columns
               foreach (($row["deduction"] ?? []) as $deductionRow)
               {
                    $dataRow[] = $deductionRow;
               }

               // other deductions
               $dataRow[] = $row["pf"];
               $dataRow[] = $row["esic"];
               // other deductions

               $dataRow[] = $row["loanAmount"];
               $dataRow[] = $row["leaveAmount"];
               // deduction columns

               $dataRow[] = $row["grossDeductions"];
               $dataRow[] = $row["netPayableSalary"];
               $dataRow[] = $row["paySlipStatus"];

               $data[] = $dataRow;
          }

          return $data;
     }

     public function headings(): array
     {
          return [
               [$this->companyName],
               ["Salary report for the month of " . $this->salaryMonth],
               $this->columns,
          ];
     }

     public function styles(Worksheet $sheet)
     {
          //first row
          $sheet->mergeCells('A1:Z1');
          $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
          $sheet->getStyle('A1')->getFont()->setBold(true);

          //secound row
          $sheet->mergeCells('A2:Z2');
          $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
          $sheet->getStyle('A2')->getFont()->setBold(true);

          // Style for headers
          $sheet->getStyle('A3:AB3')->getFont()->setBold(true);
     }
}
