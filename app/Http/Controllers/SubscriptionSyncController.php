<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubscriptionSyncController extends Controller
{
    /**
     * Display and execute subscription data synchronization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function syncSubscriptionLimits(Request $request)
    {
        $action = $request->get('action', 'audit'); // audit, sync_all, auto_adjust, reset_custom
        $companyFilterId = $request->get('company_id');
        $format = $request->get('format');
        $statusFilter = $request->get('status'); // all, active, expired, over_capacity

        $syncLog = [];
        $defaultPlan = Plan::first();
        if (!$defaultPlan) {
            // Fallback default if plans table is empty
            $defaultPlan = Plan::create([
                'name' => 'Default Plan',
                'price' => 0,
                'duration' => 'year',
                'max_users' => 10,
                'max_employees' => 50,
                'description' => 'Default system plan',
            ]);
        }

        $query = User::where('type', 'company');
        if (!empty($companyFilterId)) {
            $query->where('id', $companyFilterId);
        }
        $companies = $query->orderBy('id', 'asc')->get();

        $companiesData = [];
        $stats = [
            'total_companies' => 0,
            'active_subscriptions' => 0,
            'expired_subscriptions' => 0,
            'no_expiry_set' => 0,
            'total_active_employees' => 0,
            'total_terminated_employees' => 0,
            'total_active_sub_users' => 0,
            'total_inactive_sub_users' => 0,
            'companies_over_user_limit' => 0,
            'companies_over_emp_limit' => 0,
            'companies_with_custom_limits' => 0,
            'synced_actions_count' => 0,
        ];

        foreach ($companies as $company) {
            $stats['total_companies']++;
            $companyActions = [];

            // 1. Validate & Sync Plan Association
            $plan = null;
            if (!empty($company->plan)) {
                $plan = Plan::find($company->plan);
            }
            if (!$plan) {
                if ($action === 'sync_all' || $action === 'auto_adjust') {
                    $company->plan = $defaultPlan->id;
                    $company->save();
                    $plan = $defaultPlan;
                    $companyActions[] = "Assigned default plan: {$defaultPlan->name} (ID: {$defaultPlan->id})";
                    $stats['synced_actions_count']++;
                } else {
                    $plan = $defaultPlan; // For reporting purposes
                }
            }

            // 2. Count Active & Inactive Employees
            $activeEmployees = Employee::where('created_by', $company->id)
                ->where('employee_exit', 0)
                ->count();
            $terminatedEmployees = Employee::where('created_by', $company->id)
                ->where('employee_exit', 1)
                ->count();
            $stats['total_active_employees'] += $activeEmployees;
            $stats['total_terminated_employees'] += $terminatedEmployees;

            // 3. Count Active & Inactive Sub-Users
            $activeSubUsers = User::where('created_by', $company->id)
                ->whereNotIn('type', ['super admin', 'company', 'employee'])
                ->where('is_active', 1)
                ->count();
            $inactiveSubUsers = User::where('created_by', $company->id)
                ->whereNotIn('type', ['super admin', 'company', 'employee'])
                ->where('is_active', 0)
                ->count();
            $stats['total_active_sub_users'] += $activeSubUsers;
            $stats['total_inactive_sub_users'] += $inactiveSubUsers;

            // 4. Resolve Plan & Custom Limits
            $planMaxUsers = (int) ($plan ? $plan->max_users : 0);
            $planMaxEmployees = (int) ($plan ? $plan->max_employees : 0);
            $customMaxUsers = $company->custom_max_users !== null ? (int) $company->custom_max_users : null;
            $customMaxEmployees = $company->custom_max_employees !== null ? (int) $company->custom_max_employees : null;

            if ($customMaxUsers !== null || $customMaxEmployees !== null) {
                $stats['companies_with_custom_limits']++;
            }

            // 5. Handle Actions
            if ($action === 'reset_custom' && (!empty($companyFilterId) || $request->get('confirm_all') == '1')) {
                if ($company->custom_max_users !== null || $company->custom_max_employees !== null) {
                    $company->custom_max_users = null;
                    $company->custom_max_employees = null;
                    $company->save();
                    $customMaxUsers = null;
                    $customMaxEmployees = null;
                    $companyActions[] = "Reset custom limits to plan defaults (Users: {$planMaxUsers}, Employees: {$planMaxEmployees})";
                    $stats['synced_actions_count']++;
                }
            }

            if ($action === 'auto_adjust') {
                $adjusted = false;
                $effectiveUsers = $customMaxUsers !== null ? $customMaxUsers : $planMaxUsers;
                $effectiveEmployees = $customMaxEmployees !== null ? $customMaxEmployees : $planMaxEmployees;

                if ($activeSubUsers > $effectiveUsers) {
                    $company->custom_max_users = $activeSubUsers;
                    $customMaxUsers = $activeSubUsers;
                    $companyActions[] = "Auto-adjusted custom max users from {$effectiveUsers} to {$activeSubUsers} (matched active count)";
                    $adjusted = true;
                }
                if ($activeEmployees > $effectiveEmployees) {
                    $company->custom_max_employees = $activeEmployees;
                    $customMaxEmployees = $activeEmployees;
                    $companyActions[] = "Auto-adjusted custom max employees from {$effectiveEmployees} to {$activeEmployees} (matched active count)";
                    $adjusted = true;
                }
                if ($adjusted) {
                    $company->save();
                    $stats['synced_actions_count']++;
                }
            }

            // Effective Limits
            $effectiveMaxUsers = $customMaxUsers !== null ? $customMaxUsers : $planMaxUsers;
            $effectiveMaxEmployees = $customMaxEmployees !== null ? $customMaxEmployees : $planMaxEmployees;

            // Capacity Flags & Math
            $isOverUserLimit = $effectiveMaxUsers > 0 && $activeSubUsers > $effectiveMaxUsers;
            $isOverEmpLimit = $effectiveMaxEmployees > 0 && $activeEmployees > $effectiveMaxEmployees;

            if ($isOverUserLimit) {
                $stats['companies_over_user_limit']++;
            }
            if ($isOverEmpLimit) {
                $stats['companies_over_emp_limit']++;
            }

            $userUsagePercent = $effectiveMaxUsers > 0 ? min(100, round(($activeSubUsers / $effectiveMaxUsers) * 100, 1)) : 0;
            $empUsagePercent = $effectiveMaxEmployees > 0 ? min(100, round(($activeEmployees / $effectiveMaxEmployees) * 100, 1)) : 0;

            // 6. Subscription Expiry Status
            $hasActiveSub = $company->hasActiveSubscription();
            $expiryDate = $company->plan_expire_date;
            $subStatus = 'Active';

            if (empty($expiryDate)) {
                $subStatus = 'No Expiry Date';
                $stats['no_expiry_set']++;
            } elseif (!$hasActiveSub) {
                $subStatus = 'Expired';
                $stats['expired_subscriptions']++;
            } else {
                $stats['active_subscriptions']++;
            }

            // Record Company Snapshot
            $companiesData[] = [
                'company_id' => $company->id,
                'name' => $company->name,
                'email' => $company->email,
                'login_status' => $company->is_active ? 'Active' : 'Disabled',
                'plan_id' => $plan ? $plan->id : null,
                'plan_name' => $plan ? $plan->name : 'No Plan',
                'plan_price' => $plan ? $plan->price : 0,
                'plan_duration' => $plan ? $plan->duration : 'N/A',
                'plan_expire_date' => $expiryDate,
                'is_subscription_active' => $hasActiveSub,
                'subscription_status' => $subStatus,
                'users' => [
                    'active_count' => $activeSubUsers,
                    'inactive_count' => $inactiveSubUsers,
                    'plan_max' => $planMaxUsers,
                    'custom_max' => $customMaxUsers,
                    'effective_max' => $effectiveMaxUsers,
                    'is_custom_applied' => $customMaxUsers !== null,
                    'is_over_limit' => $isOverUserLimit,
                    'remaining' => max(0, $effectiveMaxUsers - $activeSubUsers),
                    'usage_percent' => $userUsagePercent,
                ],
                'employees' => [
                    'active_count' => $activeEmployees,
                    'terminated_count' => $terminatedEmployees,
                    'plan_max' => $planMaxEmployees,
                    'custom_max' => $customMaxEmployees,
                    'effective_max' => $effectiveMaxEmployees,
                    'is_custom_applied' => $customMaxEmployees !== null,
                    'is_over_limit' => $isOverEmpLimit,
                    'remaining' => max(0, $effectiveMaxEmployees - $activeEmployees),
                    'usage_percent' => $empUsagePercent,
                ],
                'sync_actions' => $companyActions,
            ];
        }

        // Response Data Bundle
        $responsePayload = [
            'status' => 'success',
            'action_executed' => $action,
            'timestamp' => now()->toIso8601String(),
            'statistics' => $stats,
            'companies_count' => count($companiesData),
            'companies' => $companiesData,
        ];

        // If JSON format requested
        if ($format === 'json' || $request->wantsJson() || $request->ajax()) {
            return response()->json($responsePayload, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        $layout = \Illuminate\Support\Facades\Auth::check() ? 'layouts.admin' : 'layouts.sync_standalone';

        // Return HTML View
        return view('plan.subscription_sync', [
            'layout' => $layout,
            'payload' => $responsePayload,
            'stats' => $stats,
            'companies' => $companiesData,
            'action' => $action,
            'allPlans' => Plan::all(),
        ]);
    }

    /**
     * Update individual company limits directly.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateCompanyLimits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:users,id',
            'custom_max_users' => 'nullable|numeric|min:0',
            'custom_max_employees' => 'nullable|numeric|min:0',
            'plan_id' => 'nullable|exists:plans,id',
            'plan_expire_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $company = User::where('id', $request->company_id)->where('type', 'company')->first();
        if (!$company) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Company not found.'], 404);
            }
            return redirect()->back()->with('error', 'Company not found.');
        }

        if ($request->has('plan_id') && !empty($request->plan_id)) {
            $company->plan = $request->plan_id;
        }

        if ($request->has('plan_expire_date')) {
            $company->plan_expire_date = $request->plan_expire_date ?: null;
        }

        $company->custom_max_users = $request->filled('custom_max_users') ? (int) $request->custom_max_users : null;
        $company->custom_max_employees = $request->filled('custom_max_employees') ? (int) $request->custom_max_employees : null;
        $company->save();

        $activeUsers = $company->countUsers();
        $activeEmployees = $company->countEmployees();
        $maxUsers = $company->getMaxUsers();
        $maxEmployees = $company->getMaxEmployees();

        $message = "Limits updated successfully for {$company->name}. Max Users: {$maxUsers} (Active: {$activeUsers}), Max Employees: {$maxEmployees} (Active: {$activeEmployees}).";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'custom_max_users' => $company->custom_max_users,
                    'custom_max_employees' => $company->custom_max_employees,
                    'max_users' => $maxUsers,
                    'max_employees' => $maxEmployees,
                    'active_users' => $activeUsers,
                    'active_employees' => $activeEmployees,
                    'plan_expire_date' => $company->plan_expire_date,
                    'has_active_subscription' => $company->hasActiveSubscription(),
                ]
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
