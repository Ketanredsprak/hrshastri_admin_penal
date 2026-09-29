<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('crm_invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('price_per_employee', 12, 2)->default(200);
            $table->string('currency', 10)->default('INR');
            $table->integer('due_days')->default(15);
            $table->string('invoice_prefix', 20)->default('HRSI');
            $table->integer('next_number')->default(1);
            $table->string('seller_name')->nullable();
            $table->text('seller_address')->nullable();
            $table->string('seller_email')->nullable();
            $table->string('seller_phone', 50)->nullable();
            $table->string('product_name')->nullable()->default('HR Shastri');
            $table->string('logo_path')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->boolean('auto_email_on_generate')->default(true);
            $table->boolean('skip_zero_employee')->default(true);
            $table->timestamps();
        });

        Schema::create('crm_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->unsignedBigInteger('company_id');
            $table->string('company_name');
            $table->string('company_email')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone', 50)->nullable();
            $table->smallInteger('billing_year');
            $table->tinyInteger('billing_month');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('source', ['auto', 'manual'])->default('auto');
            $table->enum('status', ['draft', 'generated', 'sent', 'paid', 'cancelled'])->default('generated');
            $table->string('auto_period_key', 64)->nullable()->unique();
            $table->integer('active_employees_count')->default(0);
            $table->decimal('price_per_employee', 12, 2);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('INR');
            $table->text('notes')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->string('pdf_path')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->unsignedBigInteger('paid_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('company_id', 'idx_crm_inv_company');
            $table->index('status', 'idx_crm_inv_status');
            $table->index(['billing_year', 'billing_month'], 'idx_crm_inv_period');
        });

        Schema::create('crm_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('description', 500);
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 14, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('invoice_id', 'fk_crm_item_invoice')
                ->references('id')->on('crm_invoices')->onDelete('cascade');
        });

        Schema::create('crm_invoice_email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->text('recipients');
            $table->string('subject')->nullable();
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id', 'fk_crm_email_invoice')
                ->references('id')->on('crm_invoices')->onDelete('cascade');
        });

        DB::table('crm_invoice_settings')->insert([
            'price_per_employee' => 200,
            'currency' => 'INR',
            'due_days' => 15,
            'invoice_prefix' => 'HRSI',
            'next_number' => 1,
            'seller_name' => 'HR Shastri',
            'product_name' => 'HR Shastri',
            'payment_instructions' => 'Please pay via bank transfer / UPI and share the UTR with support. Invoice becomes final once marked Payment Complete.',
            'auto_email_on_generate' => 1,
            'skip_zero_employee' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('crm_invoice_email_logs');
        Schema::dropIfExists('crm_invoice_items');
        Schema::dropIfExists('crm_invoices');
        Schema::dropIfExists('crm_invoice_settings');
    }
};
