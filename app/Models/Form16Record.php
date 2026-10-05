<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form16Record extends Model
{
    protected $table = 'form16_records';

    public const STATUSES = [
        'not_started' => 'Not Started',
        'data_pending' => 'Data Pending',
        'draft' => 'Draft',
        'ready_for_review' => 'Ready for Review',
        'verified' => 'Verified',
        'part_a_uploaded' => 'Part A Uploaded',
        'part_b_generated' => 'Part B Generated',
        'generated' => 'Generated',
        'issued' => 'Issued',
        'sent' => 'Sent',
        'failed' => 'Failed',
    ];

    protected $fillable = [
        'company_id',
        'employee_id',
        'financial_year',
        'assessment_year',
        'fy_start_date',
        'fy_end_date',
        'employment_period_from',
        'employment_period_to',
        'version',
        'previous_version_id',
        'is_latest',
        'regeneration_reason',
        'status',
        'tax_regime',
        'employee_name',
        'employee_code',
        'employee_pan',
        'employee_email',
        'employee_address',
        'employer_name',
        'employer_address',
        'employer_pan',
        'employer_tan',
        'employer_email',
        'part_a_source',
        'part_a_file_path',
        'part_a_original_name',
        'part_a_mime',
        'part_a_size',
        'part_a_uploaded_by',
        'part_a_uploaded_at',
        'part_a_certificate_number',
        'part_a_notes',
        'part_b_data',
        'part_b_pdf_path',
        'part_b_generated_at',
        'part_b_generated_by',
        'gross_salary',
        'exempt_allowances',
        'net_salary',
        'deduction_under_section_16',
        'income_chargeable_salaries',
        'other_income',
        'chapter_via_deductions',
        'taxable_income',
        'tax_on_total_income',
        'rebate',
        'surcharge',
        'cess',
        'tax_payable',
        'tds_deducted',
        'relief_under_89',
        'final_pdf_path',
        'email_status',
        'last_emailed_at',
        'last_emailed_to',
        'validation_errors',
        'missing_data_flags',
        'verified_by',
        'verified_at',
        'issued_at',
        'generated_by',
        'generated_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fy_start_date' => 'date',
        'fy_end_date' => 'date',
        'employment_period_from' => 'date',
        'employment_period_to' => 'date',
        'is_latest' => 'boolean',
        'part_a_uploaded_at' => 'datetime',
        'part_b_generated_at' => 'datetime',
        'verified_at' => 'datetime',
        'issued_at' => 'datetime',
        'generated_at' => 'datetime',
        'last_emailed_at' => 'datetime',
        'gross_salary' => 'float',
        'exempt_allowances' => 'float',
        'net_salary' => 'float',
        'deduction_under_section_16' => 'float',
        'income_chargeable_salaries' => 'float',
        'other_income' => 'float',
        'chapter_via_deductions' => 'float',
        'taxable_income' => 'float',
        'tax_on_total_income' => 'float',
        'rebate' => 'float',
        'surcharge' => 'float',
        'cess' => 'float',
        'tax_payable' => 'float',
        'tds_deducted' => 'float',
        'relief_under_89' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function emailLogs()
    {
        return $this->hasMany(Form16EmailLog::class, 'form16_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(Form16AuditLog::class, 'form16_id');
    }

    public function quarterlyTds()
    {
        return $this->hasMany(Form16QuarterlyTds::class, 'form16_id');
    }

    public function previousVersion()
    {
        return $this->belongsTo(self::class, 'previous_version_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function getPartBDataArrayAttribute(): array
    {
        if (empty($this->part_b_data)) {
            return [];
        }
        $decoded = json_decode($this->part_b_data, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getValidationErrorsArrayAttribute(): array
    {
        if (empty($this->validation_errors)) {
            return [];
        }
        $decoded = json_decode($this->validation_errors, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function blockingValidationErrors(): array
    {
        return array_values(array_filter($this->validation_errors_array, function ($err) {
            return ($err['level'] ?? 'error') !== 'warning';
        }));
    }

    public function hasPartA(): bool
    {
        return !empty($this->part_a_file_path);
    }

    /** True when Part B PDF file exists on disk path. */
    public function hasPartBPdf(): bool
    {
        return !empty($this->part_b_pdf_path);
    }

    /** True when calculated Part B JSON snapshot exists. */
    public function hasPartBData(): bool
    {
        return !empty($this->part_b_data);
    }

    /** @deprecated Prefer hasPartBPdf() for download actions */
    public function hasPartB(): bool
    {
        return $this->hasPartBPdf();
    }

    public function hasFinalPdf(): bool
    {
        return !empty($this->final_pdf_path);
    }

    public function isIssuable(): bool
    {
        return $this->hasPartBPdf() && count($this->blockingValidationErrors()) === 0;
    }
}
