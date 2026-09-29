<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_billing_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->unique();
            $table->enum('billing_mode', ['global', 'custom'])->default('global');
            $table->decimal('price_per_employee', 12, 2)->nullable();
            $table->decimal('price_per_user', 12, 2)->nullable();
            $table->unsignedBigInteger('invoice_plan_id')->nullable()->comment('Plan used for custom billing (e.g. 9)');
            $table->boolean('manual_invoice_only')->default(false)->comment('1 = no auto CRM invoice');
            $table->timestamps();

            $table->foreign('company_id', 'fk_billing_company')
                ->references('id')->on('users')->onDelete('cascade');
            $table->index('billing_mode', 'idx_billing_mode');
        });

        Schema::table('crm_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoices', 'active_users_count')) {
                $table->integer('active_users_count')->default(0)->after('active_employees_count');
            }
            if (!Schema::hasColumn('crm_invoices', 'price_per_user')) {
                $table->decimal('price_per_user', 12, 2)->nullable()->after('price_per_employee');
            }
        });

        Schema::table('crm_invoice_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoice_settings', 'auto_generate_enabled')) {
                $table->boolean('auto_generate_enabled')->default(false)->after('auto_email_on_generate');
            }
        });
    }

    public function down()
    {
        Schema::table('crm_invoice_settings', function (Blueprint $table) {
            if (Schema::hasColumn('crm_invoice_settings', 'auto_generate_enabled')) {
                $table->dropColumn('auto_generate_enabled');
            }
        });

        Schema::table('crm_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('crm_invoices', 'price_per_user')) {
                $table->dropColumn('price_per_user');
            }
            if (Schema::hasColumn('crm_invoices', 'active_users_count')) {
                $table->dropColumn('active_users_count');
            }
        });

        Schema::dropIfExists('company_billing_profiles');
    }
};
