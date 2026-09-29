<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_users',
        'max_employees',
        'max_venders',
        'description',
        'image',
        'role_id',
    ];

    public static $arrDuration = [
        'Unlimited' => 'Unlimited',
        'month' => 'Per Month',
        'year' => 'Per Year',
    ];

    public function status()
    {
        return [
            __('Unlimited'),
            __('Per Month'),
            __('Per Year'),
        ];
    }

    public static function total_plan()
    {
        return Plan::count();
    }
        
    public static function most_purchese_plan()
    {
        $free_plan = Plan::where('price', '<=', 0)->first()->id;

        return User:: select('plans.name','plans.id', DB::raw('count(*) as total'))
                   ->join('plans', 'plans.id' ,'=', 'users.plan')
                   ->where('type', '=', 'company')
                   ->where('plan', '!=', $free_plan)
                   ->orderBy('total','Desc')
                   ->groupBy('plans.name','plans.id')
                   ->first();
    }

    public function plansModule(){
        return $this->hasMany(PlansModule::class,'plans_id','id');
    }

    /**
     * Check if the given company is eligible to subscribe to this plan.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isEligibleForCompany(User $user): bool
    {
        $activeEmployees = $user->countEmployees();
        $activeUsers = $user->countUsers();

        $empLimitOk = ($this->max_employees == -1) || ($activeEmployees <= (int)$this->max_employees);
        $userLimitOk = ($this->max_users == -1) || ($activeUsers <= (int)$this->max_users);

        return $empLimitOk && $userLimitOk;
    }

    /**
     * Get human-readable ineligibility reason if company exceeds plan limits.
     *
     * @param  \App\Models\User  $user
     * @return string|null
     */
    public function getIneligibilityReason(User $user): ?string
    {
        $activeEmployees = $user->countEmployees();
        $activeUsers = $user->countUsers();

        $empExceeded = ($this->max_employees != -1) && ($activeEmployees > (int)$this->max_employees);
        $userExceeded = ($this->max_users != -1) && ($activeUsers > (int)$this->max_users);

        if ($empExceeded && $userExceeded) {
            return "You have {$activeEmployees} active employees and {$activeUsers} active sub-users, which exceeds this plan's capacity ({$this->max_employees} employees, {$this->max_users} users).";
        } elseif ($empExceeded) {
            return "You have {$activeEmployees} active employees, which exceeds this plan's limit of {$this->max_employees} employees.";
        } elseif ($userExceeded) {
            return "You have {$activeUsers} active sub-users, which exceeds this plan's limit of {$this->max_users} users.";
        }

        return null;
    }

}
