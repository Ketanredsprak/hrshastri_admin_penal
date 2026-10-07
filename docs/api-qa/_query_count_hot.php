<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\API\UserAuthController;
use Illuminate\Http\Request;

$user = User::where('email', 'qa.api.test@hrshastri.local')->first();
if (!$user) {
    fwrite(STDERR, "QA user missing\n");
    exit(1);
}
Auth::login($user);

function countQueries(callable $fn): array
{
    $queries = [];
    DB::listen(function ($q) use (&$queries) {
        $queries[] = $q->sql;
    });
    $started = microtime(true);
    $result = $fn();
    $ms = round((microtime(true) - $started) * 1000, 1);
    return ['count' => count($queries), 'ms' => $ms, 'sql' => $queries, 'result' => $result];
}

$controller = new UserAuthController();

$cal = countQueries(function () use ($controller) {
    $req = Request::create('/api/employee-calendar-data', 'POST', []);
    $req->setUserResolver(function () {
        return Auth::user();
    });
    return $controller->employeeCalendarData($req);
});

$dash = countQueries(function () use ($controller) {
    $req = Request::create('/api/dashboard', 'POST', []);
    $req->setUserResolver(function () {
        return Auth::user();
    });
    return $controller->dashboard($req);
});

echo json_encode([
    'calendar' => ['query_count' => $cal['count'], 'ms' => $cal['ms']],
    'dashboard' => ['query_count' => $dash['count'], 'ms' => $dash['ms']],
    'calendar_sample_sql' => array_slice($cal['sql'], 0, 12),
    'dashboard_sample_sql' => array_slice($dash['sql'], 0, 20),
], JSON_PRETTY_PRINT) . PHP_EOL;
