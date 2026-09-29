<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('crm_invoice_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoice_settings', 'cgst_percent')) {
                $table->decimal('cgst_percent', 8, 2)->default(0)->after('price_per_employee');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'sgst_percent')) {
                $table->decimal('sgst_percent', 8, 2)->default(0)->after('cgst_percent');
            }
        });

        Schema::table('crm_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoices', 'cgst_percent')) {
                $table->decimal('cgst_percent', 8, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('crm_invoices', 'sgst_percent')) {
                $table->decimal('sgst_percent', 8, 2)->default(0)->after('cgst_percent');
            }
            if (!Schema::hasColumn('crm_invoices', 'cgst_amount')) {
                $table->decimal('cgst_amount', 14, 2)->default(0)->after('sgst_percent');
            }
            if (!Schema::hasColumn('crm_invoices', 'sgst_amount')) {
                $table->decimal('sgst_amount', 14, 2)->default(0)->after('cgst_amount');
            }
        });

        // Safe defaults for existing settings row
        if (Schema::hasTable('crm_invoice_settings')) {
            DB::table('crm_invoice_settings')->whereNull('cgst_percent')->update(['cgst_percent' => 0]);
            DB::table('crm_invoice_settings')->whereNull('sgst_percent')->update(['sgst_percent' => 0]);
        }
    }

    public function down()
    {
        Schema::table('crm_invoices', function (Blueprint $table) {
            foreach (['sgst_amount', 'cgst_amount', 'sgst_percent', 'cgst_percent'] as $col) {
                if (Schema::hasColumn('crm_invoices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('crm_invoice_settings', function (Blueprint $table) {
            foreach (['sgst_percent', 'cgst_percent'] as $col) {
                if (Schema::hasColumn('crm_invoice_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
