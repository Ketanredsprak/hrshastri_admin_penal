<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class LocalDevUsersSeeder extends Seeder
{
    /**
     * Run the database seeds for local development accounts.
     */
    public function run()
    {
        $accounts = [
            [
                'role_label' => 'Super Admin',
                'name' => 'Super Admin',
                'email' => 'superadmin@redspark.com',
                'type' => 'super admin',
                'creator_id' => 1,
            ],
            [
                'role_label' => 'Company',
                'name' => 'Mg Motor',
                // 'email'      => 'company@redspark.com',
                'email' => 'jhaveri@mailinator.com',
                'type' => 'company',
                'creator_id' => 1,
            ],
            [
                'role_label' => 'HR',
                'name' => "Gekko's HR",
                'email' => 'viral@redsparkinfo.co.in',
                'type' => 'HR',
                'creator_id' => 2,
            ],
            [
                'role_label' => 'Employee',
                'name' => 'gekko_employee',
                'email' => 'disma81412@gmail.com',
                'type' => 'employee',
                'creator_id' => 2,
            ],
        ];

        foreach ($accounts as $acc) {
            $user = User::where('email', $acc['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $acc['name'],
                    'email' => $acc['email'],
                    'password' => Hash::make('123456'),
                    'type' => $acc['type'],
                    'lang' => 'en',
                    'created_by' => $acc['creator_id'],
                    'email_verified_at' => now(),
                ]);
                $this->command->info("Created {$acc['role_label']} user: {$acc['email']}");
            } else {
                // Ensure active status and matching type
                $user->type = $acc['type'];
                $user->save();
                $this->command->info("Verified {$acc['role_label']} user: {$acc['email']} (ID: {$user->id})");
            }

            // For employee accounts, ensure matching Employee table record exists
            if ($acc['type'] === 'employee') {
                $emp = Employee::where('user_id', $user->id)->first();
                if (!$emp) {
                    Employee::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_by' => $acc['creator_id'],
                        'employee_id' => '#EMP00' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'is_active' => 1,
                        'employee_exit' => 0,
                    ]);
                    $this->command->info("Created Employee profile record for {$acc['email']}");
                } else {
                    $emp->employee_exit = 0;
                    $emp->save();
                }
            }
        }
    }
}
