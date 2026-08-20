<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GradeType;
use App\Models\CommuteType;
use App\Models\CategoriesType;
use Illuminate\Support\Facades\Auth;

class MyClaimModel extends Model
{
     use HasFactory;
     protected $table = 'my_claim';
     protected $fillable = [
          'id',
          'visit_type',
          'name',
          'commuting_type',
          'city_id',
          'expenses_data',
          'amount',
          'fileupload',
          'expens_name',
          'expens_amount',
          'hidden_expense_is_actual',
          'misc',
          'status',
          'userid',
          'status_changed_by',
          'reject_reason',
          'created_by',
          'created_at',
          'updated_at',
          'visit_type',
          'expense_ids',
     ];

     public function GradeName()
     {
          return $this->belongsTo(GradeType::class, 'name');
     }

     public function commutingData()
     {
          return $this->belongsTo(CommuteType::class, 'commuting_type');
     }

     public function myvisitData()
     {
          return $this->belongsTo(MyVisitModel::class, 'my_visit_id');
     }

     public function employee()
     {
          return $this->hasOne(Employee::class, 'id', 'userid');
     }

     public function getExpenseIdsAttribute($value)
     {
          if ($value == null)
          {
               return [];
          }
          return json_decode($value) ?? [];
     }

     public function getImageLinkAttribute($value)
     {
          if (!empty($value) && file_exists(public_path('/fileupload/' . $value)))
          {
               return asset('fileupload/' . $value);
          }
          return "";
     }


     public function getCreatedAtFormattedAttribute($value)
     {
          return Auth::user()->dateFormat($this->created_at);
     }
}
