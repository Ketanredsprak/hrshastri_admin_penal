<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Announcement extends Model
{
     protected $fillable = [
          'title',
          'start_date',
          'end_date',
          'branch_id',
          'department_id',
          'description',
          'created_by',
          'employee_id',
     ];

     public function getStartDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }

     public function getEndDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }

     public function checkAnnouncementEmployee()
     {
          // dd($this->title);
          $attributeValue = $this->getAttribute('title');
          // dd($attributeValue);
          return User::where('type', '!=', 'super admin')->where('type', '!=', 'company')->where('type', '!=', 'employee')->where('created_by', '=', $this->creatorId())->count();
     }

     public function branch()
     {
          return $this->hasOne(Branch::class,  "id", "branch_id");
     }

     public function department()
     {
          return $this->hasOne(Department::class, "id", "department_id");
     }
}
