<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutoryDeductionsProfessionalTaxRange extends Model
{
     protected $table = "statutory_deductions_pt_range";

     protected $fillable = [
          "salary_start_range",
          "salary_end_range",
          "deduction_amount",
          "work_location",
          "status",
          "created_by",
          "created_at",
          "updated_at",
     ];

     public function account($account)
     {
          $account = AccountList::where('id', '=', $account)->first();
          return $account;
     }
     public static function payee($payee)
     {
          $payee = Payees::where('id', '=', $payee)->first();
          return $payee;
     }

     public function expense_category($category)
     {
          $category = ExpenseType::where('id', '=', $category)->first();

          return $category;
     }

     public function payment_type($payment)
     {
          $payment = PaymentType::where('id', '=', $payment)->first();
          return $payment;
     }

     public function accounts()
     {
          return $this->hasOne('App\Models\AccountList', 'id', 'account_id');
     }
}
