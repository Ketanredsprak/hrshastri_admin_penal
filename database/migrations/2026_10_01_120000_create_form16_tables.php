<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form16_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('financial_year', 9);
            $table->string('assessment_year', 9);
            $table->date('fy_start_date');
            $table->date('fy_end_date');
            $table->date('employment_period_from')->nullable();
            $table->date('employment_period_to')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('previous_version_id')->nullable();
            $table->boolean('is_latest')->default(true);
            $table->string('regeneration_reason', 500)->nullable();
            $table->string('status', 40)->default('not_started');
            $table->string('tax_regime', 20)->nullable();

            $table->string('employee_name')->nullable();
            $table->string('employee_code', 100)->nullable();
            $table->string('employee_pan', 20)->nullable();
            $table->string('employee_email')->nullable();
            $table->text('employee_address')->nullable();
            $table->string('employer_name')->nullable();
            $table->text('employer_address')->nullable();
            $table->string('employer_pan', 20)->nullable();
            $table->string('employer_tan', 20)->nullable();
            $table->string('employer_email')->nullable();

            $table->string('part_a_source', 30)->default('upload');
            $table->string('part_a_file_path', 500)->nullable();
            $table->string('part_a_original_name')->nullable();
            $table->string('part_a_mime', 100)->nullable();
            $table->unsignedInteger('part_a_size')->nullable();
            $table->unsignedBigInteger('part_a_uploaded_by')->nullable();
            $table->dateTime('part_a_uploaded_at')->nullable();
            $table->string('part_a_certificate_number', 100)->nullable();
            $table->text('part_a_notes')->nullable();

            $table->longText('part_b_data')->nullable();
            $table->string('part_b_pdf_path', 500)->nullable();
            $table->dateTime('part_b_generated_at')->nullable();
            $table->unsignedBigInteger('part_b_generated_by')->nullable();

            $table->decimal('gross_salary', 14, 2)->default(0);
            $table->decimal('exempt_allowances', 14, 2)->default(0);
            $table->decimal('net_salary', 14, 2)->default(0);
            $table->decimal('deduction_under_section_16', 14, 2)->default(0);
            $table->decimal('income_chargeable_salaries', 14, 2)->default(0);
            $table->decimal('other_income', 14, 2)->default(0);
            $table->decimal('chapter_via_deductions', 14, 2)->default(0);
            $table->decimal('taxable_income', 14, 2)->default(0);
            $table->decimal('tax_on_total_income', 14, 2)->default(0);
            $table->decimal('rebate', 14, 2)->default(0);
            $table->decimal('surcharge', 14, 2)->default(0);
            $table->decimal('cess', 14, 2)->default(0);
            $table->decimal('tax_payable', 14, 2)->default(0);
            $table->decimal('tds_deducted', 14, 2)->default(0);
            $table->decimal('relief_under_89', 14, 2)->default(0);

            $table->string('final_pdf_path', 500)->nullable();
            $table->string('email_status', 20)->default('not_sent');
            $table->dateTime('last_emailed_at')->nullable();
            $table->string('last_emailed_to')->nullable();

            $table->longText('validation_errors')->nullable();
            $table->longText('missing_data_flags')->nullable();

            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->dateTime('generated_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('company_id', 'idx_form16_company');
            $table->index('employee_id', 'idx_form16_employee');
            $table->index('financial_year', 'idx_form16_fy');
            $table->index('status', 'idx_form16_status');
            $table->index(['company_id', 'employee_id', 'financial_year', 'is_latest'], 'idx_form16_latest');
            $table->unique(['company_id', 'employee_id', 'financial_year', 'version'], 'uniq_form16_version');
        });

        Schema::create('form16_quarterly_tds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form16_id');
            $table->unsignedTinyInteger('quarter');
            $table->string('receipt_number', 50)->nullable();
            $table->decimal('amount_paid_credited', 14, 2)->default(0);
            $table->decimal('tax_deducted', 14, 2)->default(0);
            $table->decimal('tax_deposited', 14, 2)->default(0);
            $table->string('bsr_code', 20)->nullable();
            $table->date('challan_date')->nullable();
            $table->string('challan_serial', 50)->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();

            $table->unique(['form16_id', 'quarter'], 'uniq_form16_quarter');
            $table->foreign('form16_id', 'fk_form16_q_record')
                ->references('id')->on('form16_records')->onDelete('cascade');
        });

        Schema::create('form16_email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form16_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('email');
            $table->string('subject', 500)->nullable();
            $table->string('status', 20)->default('queued');
            $table->text('error_message')->nullable();
            $table->unsignedInteger('attempt')->default(1);
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->index('form16_id', 'idx_form16_email_record');
            $table->index('employee_id', 'idx_form16_email_employee');
            $table->foreign('form16_id', 'fk_form16_email_record')
                ->references('id')->on('form16_records')->onDelete('cascade');
        });

        Schema::create('form16_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form16_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 80);
            $table->longText('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index('form16_id', 'idx_form16_audit_record');
            $table->index('company_id', 'idx_form16_audit_company');
            $table->index('action', 'idx_form16_audit_action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('form16_audit_logs');
        Schema::dropIfExists('form16_email_logs');
        Schema::dropIfExists('form16_quarterly_tds');
        Schema::dropIfExists('form16_records');
    }
};
