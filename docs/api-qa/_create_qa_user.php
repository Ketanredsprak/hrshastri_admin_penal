<?php
/**
 * One-time helper: create (or reset) a dedicated API QA employee.
 * Does NOT modify app/Http/Controllers/API/.
 */
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

$email = 'qa.api.test@hrshastri.local';
$password = 'QaTest@12345';

$company = User::where('type', 'company')->orderBy('id')->first();
if (!$company) {
    fwrite(STDERR, "No company user found\n");
    exit(1);
}

$user = User::where('email', $email)->first();
if (!$user) {
    $user = new User();
    $user->name = 'QA API Tester';
    $user->email = $email;
    $user->password = Hash::make($password);
    $user->type = 'employee';
    $user->created_by = $company->id;
    $user->lang = 'en';
    $user->avatar = '';
    $user->email_verified_at = now();
    $user->save();
} else {
    $user->password = Hash::make($password);
    $user->type = 'employee';
    $user->created_by = $company->id;
    $user->save();
}

$employee = Employee::where('user_id', $user->id)->first();
if (!$employee) {
    // Clone minimal fields from an existing employee in same company for branch/dept
    $template = Employee::where('created_by', $company->id)->where('employee_exit', 0)->first();
    $employee = new Employee();
    $employee->user_id = $user->id;
    $employee->name = 'QA API Tester';
    $employee->email = $email;
    $employee->password = Hash::make($password);
    $employee->employee_id = 'QA-API-001';
    $employee->created_by = $company->id;
    $employee->employee_exit = 0;
    $employee->app_login = 1;
    if ($template) {
        $employee->branch_id = $template->branch_id;
        $employee->department_id = $template->department_id;
        $employee->designation_id = $template->designation_id;
        $employee->play_slab_id = $template->play_slab_id ?? 0;
        $employee->company_doj = $template->company_doj ?? date('Y-m-d');
    } else {
        $employee->branch_id = 0;
        $employee->department_id = 0;
        $employee->designation_id = 0;
        $employee->company_doj = date('Y-m-d');
    }
    $employee->phone = '9999999999';
    $employee->dob = '1990-01-01';
    $employee->gender = 'Male';
    $employee->address = 'QA Address';
    $employee->salary = 0;
    $employee->save();
} else {
    $employee->employee_exit = 0;
    $employee->app_login = 1;
    $employee->email = $email;
    $employee->password = Hash::make($password);
    $employee->save();
}

echo json_encode([
    'email' => $email,
    'password' => $password,
    'user_id' => $user->id,
    'employee_id' => $employee->id,
    'company_id' => $company->id,
    'base_hint' => env('APP_URL') . '/api',
], JSON_PRETTY_PRINT) . PHP_EOL;
