<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddApiHotReadPerformanceIndexes extends Migration
{
     private function hasIndex(string $table, string $indexName): bool
     {
          $rows = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
          return !empty($rows);
     }

     private function addIndexIfMissing(string $table, string $indexName, array $columns): void
     {
          if (!Schema::hasTable($table) || $this->hasIndex($table, $indexName))
          {
               return;
          }
          Schema::table($table, function (Blueprint $tableBlueprint) use ($columns, $indexName)
          {
               $tableBlueprint->index($columns, $indexName);
          });
     }

     public function up()
     {
          $this->addIndexIfMissing('attendance_employees', 'idx_att_emp_date', ['employee_id', 'date']);
          $this->addIndexIfMissing('attendance_employees', 'idx_att_emp_date_id', ['employee_id', 'date', 'id']);
          $this->addIndexIfMissing('employees', 'idx_employees_user_id', ['user_id']);
          $this->addIndexIfMissing('leaves', 'idx_leaves_emp_status_dates', ['employee_id', 'status', 'start_date', 'end_date']);
          $this->addIndexIfMissing('leaves', 'idx_leaves_emp_created_id', ['employee_id', 'created_by', 'id']);
          $this->addIndexIfMissing('holidays', 'idx_holidays_created_status', ['created_by', 'status']);
          $this->addIndexIfMissing('mobile_menu', 'idx_mobile_menu_parent_seq', ['parent_id', 'sequence_number']);
          $this->addIndexIfMissing('company_mobile_menu', 'idx_cmm_company_menu', ['company_id', 'menu_id']);
     }

     public function down()
     {
          $drops = [
               'attendance_employees' => ['idx_att_emp_date', 'idx_att_emp_date_id'],
               'employees' => ['idx_employees_user_id'],
               'leaves' => ['idx_leaves_emp_status_dates', 'idx_leaves_emp_created_id'],
               'holidays' => ['idx_holidays_created_status'],
               'mobile_menu' => ['idx_mobile_menu_parent_seq'],
               'company_mobile_menu' => ['idx_cmm_company_menu'],
          ];
          foreach ($drops as $table => $indexes)
          {
               if (!Schema::hasTable($table))
               {
                    continue;
               }
               foreach ($indexes as $indexName)
               {
                    if ($this->hasIndex($table, $indexName))
                    {
                         Schema::table($table, function (Blueprint $tableBlueprint) use ($indexName)
                         {
                              $tableBlueprint->dropIndex($indexName);
                         });
                    }
               }
          }
     }
}
