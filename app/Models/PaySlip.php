<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
     protected $fillable = [
          'employee_id',
          'net_payble',
          'play_slab_id',
          'basic_salary',
          'salary_month',
          'status',
          'allowance',
          'commission',
          'loan',
          'saturation_deduction',
          'other_payment',
          'overtime',
          'created_by',

          'other_payments_deductions',
          'other_payments_deductions_total_amount',

          "pay_slip_data_type",
          "pay_slip_data",
     ];


     public static function employee($id)
     {
          return Employee::find($id);
     }

     public function employees()
     {
          return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
     }

     public function getPaySlipDownloadLinkAttribute()
     {
          return asset('payslip/pdf_api/' . $this->employee_id . '/' . $this->salary_month);
     }


     public function getPaySlipRawDataAttribute()
     {
          $pay_slip_data = (object)json_decode($this->pay_slip_data);
          return $pay_slip_data;
     }


     public function getPaySlipStatusAttribute()
     {
          if ($this->status == 1)
          {
               return 'Paid';
          }
          else
          {
               return 'UnPaid';
          }
     }

     public function getPaySlabNameAttribute()
     {
          $playslapdata = PayslipType::where("id", $this->play_slab_id)->first();

          if ($playslapdata != null)
          {
               return $playslapdata->name;
          }
          else
          {
               return $this->id;
          }
     }
}
