<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDataChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_data_change_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('changed_by_user_id')->nullable()->index();
            $table->string('actor_type', 32)->nullable();
            $table->string('source', 32)->default('web_admin');
            $table->string('action', 16)->default('update');
            $table->json('snapshot_before')->nullable();
            $table->json('snapshot_after')->nullable();
            $table->json('modules_touched')->nullable();
            $table->json('request_meta')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['company_id', 'created_at']);
            $table->index(['employee_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_data_change_logs');
    }
}
