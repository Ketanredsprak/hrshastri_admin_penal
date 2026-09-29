<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonateController extends Controller
{
    /**
     * Display the impersonation search & user list interface.
     * Exclusively accessible by Super Admin.
     */
    public function index(Request $request)
    {
        if (Auth::user()->type !== 'super admin') {
            abort(403, __('Access Denied. Only Super Administrators can access the Impersonation Hub.'));
        }

        $userType = $request->get('user_type', 'all');
        $search   = $request->get('search', '');
        $companyId = $request->get('company_id', '');
        $status   = $request->get('status', 'all');

        // Base query excluding Super Admins and the current admin
        $query = User::where('type', '!=', 'super admin')
            ->where('id', '!=', Auth::id());

        // Filter by user type
        if ($userType === 'company') {
            $query->where('type', 'company');
        } elseif ($userType === 'employee') {
            $query->where('type', 'employee');
        } elseif ($userType === 'user') {
            // Sub-users: HR, Manager, Accountant, etc. (all non-company, non-employee, non-superadmin)
            $query->whereNotIn('type', ['super admin', 'company', 'employee']);
        }

        // Search by name or email
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by parent company (created_by)
        if (!empty($companyId)) {
            $query->where(function ($q) use ($companyId) {
                $q->where('id', $companyId)
                  ->orWhere('created_by', $companyId);
            });
        }

        // Filter by active status if requested
        if ($status === 'active') {
            $query->where(function ($q) {
                $q->whereNull('login_status')->orWhere('login_status', '!=', 0);
            });
        } elseif ($status === 'inactive') {
            $query->where('login_status', 0);
        }

        // Metrics / Counts for tabs
        $counts = [
            'all'       => User::where('type', '!=', 'super admin')->count(),
            'company'   => User::where('type', 'company')->count(),
            'user'      => User::whereNotIn('type', ['super admin', 'company', 'employee'])->count(),
            'employee'  => User::where('type', 'employee')->count(),
        ];

        // List of companies for the dropdown filter
        $companies = User::where('type', 'company')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email']);

        // Paginate results
        $users = $query->orderBy('id', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // Preload company details for display
        $companyIds = $users->pluck('created_by')->filter()->unique();
        $companyMap = User::whereIn('id', $companyIds)->get()->keyBy('id');

        // Preload employee records for employees
        $employeeUserIds = $users->where('type', 'employee')->pluck('id');
        $employeeMap = Employee::whereIn('user_id', $employeeUserIds)->get()->keyBy('user_id');

        return view('impersonate.index', compact(
            'users',
            'counts',
            'companies',
            'companyMap',
            'employeeMap',
            'userType',
            'search',
            'companyId',
            'status'
        ));
    }

    /**
     * Start impersonating a target user.
     */
    public function impersonate(Request $request, $id)
    {
        // Allow Super Admin or an active impersonator to start/switch impersonation
        $isSuperAdmin = (Auth::user()->type === 'super admin');
        $hasActiveImpersonation = session()->has('admin_impersonator_id');

        if (!$isSuperAdmin && !$hasActiveImpersonation) {
            return redirect()->back()->with('error', __('Unauthorized action. Only Super Administrators can impersonate users.'));
        }

        $targetUser = User::find($id);

        if (!$targetUser) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        // Prevent impersonating Super Admins
        if ($targetUser->type === 'super admin') {
            return redirect()->back()->with('error', __('Super Administrator accounts cannot be impersonated.'));
        }

        // If not already in an active session, save original Super Admin details into session
        if (!$hasActiveImpersonation) {
            $admin = Auth::user();
            session([
                'admin_impersonator_id'    => $admin->id,
                'admin_impersonator_name'  => $admin->name,
                'admin_impersonator_email' => $admin->email,
                'impersonated_by'          => $admin->id,
                'impersonation_started_at' => now()->toDateTimeString(),
            ]);
            Log::info("SUPER_ADMIN_IMPERSONATION_STARTED: Admin [ID: {$admin->id}, Email: {$admin->email}] started impersonating User [ID: {$targetUser->id}, Name: {$targetUser->name}, Email: {$targetUser->email}, Type: {$targetUser->type}] from IP: " . $request->ip());
        } else {
            $adminId = session('admin_impersonator_id', session('impersonated_by'));
            Log::info("SUPER_ADMIN_IMPERSONATION_SWITCHED: Admin [ID: {$adminId}] switched impersonation to User [ID: {$targetUser->id}, Name: {$targetUser->name}, Email: {$targetUser->email}, Type: {$targetUser->type}] from IP: " . $request->ip());
        }

        // If target user is an employee, verify employee active status
        if ($targetUser->type === 'employee') {
            $employee = Employee::where('user_id', $targetUser->id)->first();
            if ($employee && $employee->employee_exit == 1) {
                $employee->employee_exit = 0;
                $employee->save();
            }
        }

        // Switch authentication to target user
        Auth::loginUsingId($targetUser->id);

        $roleLabel = ucfirst($targetUser->type);
        return redirect()->route('home')->with(
            'success',
            __('You are now logged in as :name (:role). A banner at the top allows you to return to your admin account at any time.', [
                'name' => $targetUser->name,
                'role' => $roleLabel,
            ])
        );
    }

    /**
     * Exit the impersonation session and restore Super Admin account.
     */
    public function exitImpersonation(Request $request)
    {
        $adminId = session('admin_impersonator_id', session('impersonated_by'));
        if (!$adminId) {
            return redirect()->route('home')->with('error', __('No active impersonation session was found.'));
        }

        $admin = User::where('id', $adminId)->where('type', 'super admin')->first();

        $impersonatedUser = Auth::user();

        // Audit log exit event
        Log::info("SUPER_ADMIN_IMPERSONATION_EXITED: Admin [ID: {$adminId}] exited impersonation of User [ID: " . ($impersonatedUser->id ?? 'unknown') . "] from IP: " . $request->ip());

        // Clear impersonation session variables
        session()->forget([
            'admin_impersonator_id',
            'admin_impersonator_name',
            'admin_impersonator_email',
            'impersonated_by',
            'impersonation_started_at',
        ]);

        if (!$admin) {
            Auth::logout();
            return redirect()->route('login')->with('error', __('Original administrator account could not be found. Please log in again.'));
        }

        // Restore Super Admin authentication
        Auth::loginUsingId($admin->id);

        return redirect()->route('home')->with(
            'success',
            __('Successfully exited impersonation. Restored Super Administrator session.')
        );
    }

    /**
     * AJAX search endpoint for the top-right Impersonate Popup Modal.
     */
    public function search(Request $request)
    {
        $isSuperAdmin = (Auth::user()->type === 'super admin');
        $hasActiveImpersonation = session()->has('admin_impersonator_id');

        if (!$isSuperAdmin && !$hasActiveImpersonation) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $userType  = $request->get('user_type', 'all');
        $search    = trim($request->get('search', ''));
        $companyId = $request->get('company_id', '');

        $adminId = session('admin_impersonator_id', Auth::id());

        $query = User::where('type', '!=', 'super admin')
            ->where('id', '!=', Auth::id())
            ->where('id', '!=', $adminId);

        if ($userType === 'company') {
            $query->where('type', 'company');
        } elseif ($userType === 'employee') {
            $query->where('type', 'employee');
        } elseif ($userType === 'user') {
            $query->whereNotIn('type', ['super admin', 'company', 'employee']);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($companyId)) {
            $query->where(function ($q) use ($companyId) {
                $q->where('id', $companyId)
                  ->orWhere('created_by', $companyId);
            });
        }

        $page  = max(1, (int) $request->get('page', 1));
        $limit = max(10, min(100, (int) $request->get('limit', 25)));

        $totalCount = $query->count();
        $users = $query->orderBy('id', 'desc')
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->get();

        $companyIds = $users->pluck('created_by')->filter()->unique();
        $companyMap = User::whereIn('id', $companyIds)->get()->keyBy('id');

        $employeeUserIds = $users->where('type', 'employee')->pluck('id');
        $employeeMap = Employee::whereIn('user_id', $employeeUserIds)->get()->keyBy('user_id');

        $results = $users->map(function ($u) use ($companyMap, $employeeMap) {
            $avatarPath = \App\Models\Utility::get_file('uploads/avatar/');
            $userAvatar = (!empty($u->avatar) && file_exists(public_path('uploads/avatar/' . $u->avatar)))
                ? $avatarPath . $u->avatar
                : null;

            $parentCompany = null;
            if ($u->type === 'company') {
                $parentCompany = $u->name;
            } else {
                $parentObj = $companyMap[$u->created_by] ?? null;
                $parentCompany = $parentObj ? $parentObj->name : __('Direct / System');
            }

            $empRecord = $employeeMap[$u->id] ?? null;
            $employeeCode = $empRecord ? $empRecord->employee_id : null;

            $isActive = ($u->login_status !== 0 && $u->login_status !== '0');
            if ($u->type === 'employee' && $empRecord) {
                $isActive = ($isActive && $empRecord->employee_exit == 0);
            }

            return [
                'id'              => $u->id,
                'name'            => $u->name,
                'email'           => $u->email,
                'type'            => $u->type,
                'avatar'          => $userAvatar,
                'initials'        => strtoupper(substr($u->name, 0, 2)),
                'parent_company'  => $parentCompany,
                'employee_code'   => $employeeCode,
                'is_active'       => $isActive,
                'impersonate_url' => route('impersonate.login', $u->id),
            ];
        });

        return response()->json([
            'success'      => true,
            'count'        => $results->count(),
            'total'        => $totalCount,
            'current_page' => $page,
            'has_more'     => ($page * $limit) < $totalCount,
            'users'        => $results,
        ]);
    }
}

