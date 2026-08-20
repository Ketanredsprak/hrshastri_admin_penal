<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanInstallments extends Model
{
     protected $fillable = [
          "loans_id",
          "installment_date",
          "installment_amount",
          "installment_status",
          "created_by",
          "created_at",
          "updated_at",
     ];

     public function loan()
     {
          return $this->hasOne('App\Models\Loan', 'id', 'loans_id')->first();
     }
}
