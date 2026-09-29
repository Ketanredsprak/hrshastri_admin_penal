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
            if (!Schema::hasColumn('crm_invoice_settings', 'seller_gstin')) {
                $table->string('seller_gstin', 20)->nullable()->after('seller_phone');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'seller_state')) {
                $table->string('seller_state', 100)->nullable()->after('seller_gstin');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'seller_state_code')) {
                $table->string('seller_state_code', 10)->nullable()->after('seller_state');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'bank_name')) {
                $table->string('bank_name', 150)->nullable()->after('payment_instructions');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'bank_account_no')) {
                $table->string('bank_account_no', 50)->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'bank_ifsc')) {
                $table->string('bank_ifsc', 30)->nullable()->after('bank_account_no');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'bank_swift')) {
                $table->string('bank_swift', 30)->nullable()->after('bank_ifsc');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'default_hsn_sac')) {
                $table->string('default_hsn_sac', 20)->nullable()->after('bank_swift');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'default_terms')) {
                $table->string('default_terms', 100)->nullable()->after('default_hsn_sac');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'default_notes')) {
                $table->text('default_notes')->nullable()->after('default_terms');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'signature_path')) {
                $table->string('signature_path')->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('crm_invoice_settings', 'use_fy_numbering')) {
                $table->boolean('use_fy_numbering')->default(true)->after('next_number');
            }
        });

        Schema::table('crm_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoices', 'company_gstin')) {
                $table->string('company_gstin', 20)->nullable()->after('company_phone');
            }
            if (!Schema::hasColumn('crm_invoices', 'ship_to_name')) {
                $table->string('ship_to_name')->nullable()->after('company_gstin');
            }
            if (!Schema::hasColumn('crm_invoices', 'ship_to_address')) {
                $table->text('ship_to_address')->nullable()->after('ship_to_name');
            }
            if (!Schema::hasColumn('crm_invoices', 'place_of_supply')) {
                $table->string('place_of_supply', 100)->nullable()->after('ship_to_address');
            }
            if (!Schema::hasColumn('crm_invoices', 'place_of_supply_code')) {
                $table->string('place_of_supply_code', 10)->nullable()->after('place_of_supply');
            }
            if (!Schema::hasColumn('crm_invoices', 'terms')) {
                $table->string('terms', 100)->nullable()->after('place_of_supply_code');
            }
            if (!Schema::hasColumn('crm_invoices', 'total_in_words')) {
                $table->string('total_in_words', 500)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('crm_invoices', 'seller_gstin')) {
                $table->string('seller_gstin', 20)->nullable()->after('total_in_words');
            }
            if (!Schema::hasColumn('crm_invoices', 'seller_name_snapshot')) {
                $table->string('seller_name_snapshot')->nullable()->after('seller_gstin');
            }
            if (!Schema::hasColumn('crm_invoices', 'seller_address_snapshot')) {
                $table->text('seller_address_snapshot')->nullable()->after('seller_name_snapshot');
            }
        });

        Schema::table('crm_invoice_items', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_invoice_items', 'hsn_sac')) {
                $table->string('hsn_sac', 20)->nullable()->after('description');
            }
            if (!Schema::hasColumn('crm_invoice_items', 'unit')) {
                $table->string('unit', 20)->nullable()->default('Nos')->after('hsn_sac');
            }
            if (!Schema::hasColumn('crm_invoice_items', 'cgst_percent')) {
                $table->decimal('cgst_percent', 8, 2)->default(0)->after('line_total');
            }
            if (!Schema::hasColumn('crm_invoice_items', 'cgst_amount')) {
                $table->decimal('cgst_amount', 14, 2)->default(0)->after('cgst_percent');
            }
            if (!Schema::hasColumn('crm_invoice_items', 'sgst_percent')) {
                $table->decimal('sgst_percent', 8, 2)->default(0)->after('cgst_amount');
            }
            if (!Schema::hasColumn('crm_invoice_items', 'sgst_amount')) {
                $table->decimal('sgst_amount', 14, 2)->default(0)->after('sgst_percent');
            }
        });

        // Seed HR Shastri Tech LLP defaults from sample TAX INVOICE
        if (Schema::hasTable('crm_invoice_settings')) {
            $row = DB::table('crm_invoice_settings')->orderBy('id')->first();
            if ($row) {
                DB::table('crm_invoice_settings')->where('id', $row->id)->update([
                    'seller_name' => !empty($row->seller_name) && $row->seller_name !== 'HR Shastri'
                        ? $row->seller_name
                        : 'HR SHASTRI TECH LLP',
                    'seller_address' => $row->seller_address ?: "5th Floor, Darshanam Oxy Park\nVasna Bhayli Road\nVadodara Gujarat 390015\nIndia",
                    'seller_gstin' => $row->seller_gstin ?: '24AAQFH2769H1Z1',
                    'seller_state' => $row->seller_state ?: 'Gujarat',
                    'seller_state_code' => $row->seller_state_code ?: '24',
                    'bank_name' => $row->bank_name ?: 'AXIS BANK',
                    'bank_account_no' => $row->bank_account_no ?: '922020049777281',
                    'bank_ifsc' => $row->bank_ifsc ?: 'UTIB0001491',
                    'bank_swift' => $row->bank_swift ?: 'AXISINBB013',
                    'default_hsn_sac' => $row->default_hsn_sac ?: '997331',
                    'default_terms' => $row->default_terms ?: 'Due on Receipt',
                    'default_notes' => $row->default_notes ?: 'Thanks for your business.',
                    'invoice_prefix' => 'INV',
                    'use_fy_numbering' => 1,
                    'cgst_percent' => ((float) ($row->cgst_percent ?? 0) > 0) ? $row->cgst_percent : 9,
                    'sgst_percent' => ((float) ($row->sgst_percent ?? 0) > 0) ? $row->sgst_percent : 9,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::table('crm_invoice_items', function (Blueprint $table) {
            foreach (['sgst_amount', 'sgst_percent', 'cgst_amount', 'cgst_percent', 'unit', 'hsn_sac'] as $col) {
                if (Schema::hasColumn('crm_invoice_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('crm_invoices', function (Blueprint $table) {
            foreach ([
                'seller_address_snapshot', 'seller_name_snapshot', 'seller_gstin', 'total_in_words',
                'terms', 'place_of_supply_code', 'place_of_supply', 'ship_to_address', 'ship_to_name', 'company_gstin',
            ] as $col) {
                if (Schema::hasColumn('crm_invoices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('crm_invoice_settings', function (Blueprint $table) {
            foreach ([
                'use_fy_numbering', 'signature_path', 'default_notes', 'default_terms', 'default_hsn_sac',
                'bank_swift', 'bank_ifsc', 'bank_account_no', 'bank_name',
                'seller_state_code', 'seller_state', 'seller_gstin',
            ] as $col) {
                if (Schema::hasColumn('crm_invoice_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
