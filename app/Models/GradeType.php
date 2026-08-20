<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Models\VisitType;
use App\Models\MyVisitModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GradeType extends Model
{
    use HasFactory;
    protected $table = 'grade_type';

    protected $fillable = [
        'id',
        'name',
        'status',
        
        'role_id',
        'in_city_two_wheeler',
        'in_city_four_wheeler',

        'commuting_id',
        'expenses_data',
        'city_tier_id',
        'created_by',
        'visit_type',
        'commuting_amount',
        'commuting_amount_status',
        'assign_grade_id',
        'created_at',
        'updated_at',
    ];

    protected function CommutingId(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function CategoryId(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function ExpensesData(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


    

    public function roleName()
    {
        return $this->hasOne(RolesManagment::class,'id', 'role_id');
    }
    public function role()
    {
        return $this->hasOne(Role::class,'id', 'role_id');
    }

    public function visitTypeData()
    {
        return $this->hasOne(VisitType::class,'id', 'assign_grade_id');
    }

    
    
   

   

}
