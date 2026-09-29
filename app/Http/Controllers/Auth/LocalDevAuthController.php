<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LocalDevAuthController extends Controller
{
    /**
     * Account mapping for local development fast multi-user login.
     */
    public static array $accounts = [
        'Super Admin' => [
            'email' => 'superadmin@redspark.com',
            'type'  => 'super admin',
            'name'  => 'Super Admin',
        ],
        'Company' => [
            'email' => 'company@redspark.com',
            'type'  => 'company',
            'name'  => 'Mg Motor',
        ],
        'HR' => [
            'email' => 'viral@redsparkinfo.co.in',
            'type'  => 'HR',
            'name'  => "Gekko's HR",
        ],
        'Employee' => [
            // Must match login-page Employee button + a company-linked employee with active subscription
            'email' => 'viral@redsparkinfo.co.in',
            'type'  => 'employee',
            'name'  => 'John Glad',
        ],
    ];

    /**
     * Perform quick login via POST request.
     */
    public function quickLogin(Request $request)
    {
        abort_unless(app()->environment('local'), 403, 'Multi-user quick login is strictly disabled outside local development.');

        $role = $request->input('role');
        return $this->authenticateRole($request, $role);
    }

    /**
     * Perform quick login via GET request (for 1-click links / topbar switcher).
     */
    public function quickLoginGet(Request $request, $role)
    {
        abort_unless(app()->environment('local'), 403, 'Multi-user quick login is strictly disabled outside local development.');

        return $this->authenticateRole($request, $role);
    }

    /**
     * Authenticate and redirect to dashboard.
     */
    protected function authenticateRole(Request $request, ?string $role)
    {
        // Normalize role matching (case-insensitive & hyphen-friendly)
        $matchedRole = null;
        foreach (self::$accounts as $key => $data) {
            if (strcasecmp($key, $role) === 0 || strcasecmp(str_replace(' ', '-', $key), str_replace(' ', '-', $role)) === 0) {
                $matchedRole = $key;
                break;
            }
        }

        if (!$matchedRole) {
            return redirect()->back()->with('error', "Invalid local development role: '{$role}'");
        }

        $accountData = self::$accounts[$matchedRole];
        $email = $accountData['email'];

        // Find or ensure account exists in local database
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = $this->ensureUserExists($matchedRole, $accountData);
        }

        if (!$user) {
            return redirect()->back()->with('error', "Failed to load or initialize account for {$matchedRole} ({$email}).");
        }

        // If employee, ensure web login and active status
        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            if ($employee->employee_exit == 1) {
                $employee->employee_exit = 0;
                $employee->save();
            }
        }

        // Log out existing session if any, then log in target user
        if (Auth::check()) {
            Auth::logout();
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME)->with(
            'success',
            "Logged in as {$matchedRole} ({$user->name})"
        );
    }

    /**
     * Seed or initialize a missing test user on-the-fly for local dev.
     */
    protected function ensureUserExists(string $role, array $accountData): ?User
    {
        try {
            $creatorId = 1;
            if ($accountData['type'] !== 'super admin') {
                $superAdmin = User::where('type', 'super admin')->first();
                $creatorId = $superAdmin ? $superAdmin->id : 1;
            }

            $user = User::create([
                'name'              => $accountData['name'],
                'email'             => $accountData['email'],
                'password'          => Hash::make('123456'),
                'type'              => $accountData['type'],
                'lang'              => 'en',
                'created_by'        => $creatorId,
                'email_verified_at' => now(),
            ]);

            return $user;
        } catch (\Throwable $e) {
            Log::error("LocalDevAuthController: Failed to auto-provision {$role}: " . $e->getMessage());
            return null;
        }
    }
}
