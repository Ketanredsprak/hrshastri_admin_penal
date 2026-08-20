<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Percentage;
use App\Models\Leave;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserLeaveBalanceYear extends Model
{
     protected $table = 'user_leave_balance_year';
     protected $fillable = [
          'user_id',
          "leave_type_id",
          "created_by",
          "pending_leaves",
          "leaves_year",
          "created_at",
          "updated_at",
     ];


     public function User()
     {
          return $this->hasOne(Employee::class, 'user_id');
     }
}
