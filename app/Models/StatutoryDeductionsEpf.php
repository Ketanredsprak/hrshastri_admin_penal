<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutoryDeductionsEpf extends Model
{
     protected $table = "statutory_deductions_epf";

     protected $fillable = [
          "epf_number",
          "epf_limit",
          "employee_contribution_rate",
          "employer_contribution_rate",
          "override_employee_pf",
          "is_min_max_salary_limit",
          "epf_deductions_allowances_ids",
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
