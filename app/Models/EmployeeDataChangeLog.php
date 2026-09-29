<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDataChangeLog extends Model
{
    public $timestamps = false;

    protected $table = 'employee_data_change_logs';

    protected $fillable = [
        'employee_id',
        'company_id',
        'changed_by_user_id',
        'actor_type',
        'source',
        'action',
        'snapshot_before',
        'snapshot_after',
        'modules_touched',
        'request_meta',
        'created_at',
    ];

    protected $casts = [
        'snapshot_before' => 'array',
        'snapshot_after' => 'array',
        'modules_touched' => 'array',
        'request_meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    public static function sourceLabels(): array
    {
        return [
            'web_admin' => __('Company / HR (Web)'),
            'web_ess' => __('Employee Self-Service (Web)'),
            'mobile_api' => __('Mobile App'),
        ];
    }

    public function getSourceLabelAttribute(): string
    {
        $labels = self::sourceLabels();

        return $labels[$this->source] ?? ucfirst(str_replace('_', ' ', (string) $this->source));
    }
}
