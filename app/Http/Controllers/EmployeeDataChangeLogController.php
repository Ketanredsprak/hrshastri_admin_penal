<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDataChangeLog;
use App\Services\EmployeeDataChangeLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDataChangeLogController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('View Employee Data Log') && Auth::user()->type !== 'company') {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $companyId = Auth::user()->creatorId();

        $query = EmployeeDataChangeLog::with(['employee', 'changedBy'])
            ->where('company_id', $companyId)
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', (int) $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('actor_type')) {
            $query->where('actor_type', $request->actor_type);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $logs = $query->paginate(25)->appends($request->query());

        $employees = Employee::where('created_by', $companyId)
            ->orderBy('name')
            ->get(['id', 'name', 'employee_id']);

        $sources = EmployeeDataChangeLog::sourceLabels();
        $actorTypes = EmployeeDataChangeLog::where('company_id', $companyId)
            ->whereNotNull('actor_type')
            ->distinct()
            ->pluck('actor_type');

        return view('employee_data_log.index', compact('logs', 'employees', 'sources', 'actorTypes'));
    }

    public function show($id)
    {
        if (!Auth::user()->can('View Employee Data Log') && Auth::user()->type !== 'company') {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $companyId = Auth::user()->creatorId();
        $log = EmployeeDataChangeLog::with(['employee', 'changedBy'])
            ->where('company_id', $companyId)
            ->findOrFail($id);

        $before = $log->snapshot_before ?? [];
        $after = $log->snapshot_after ?? [];
        $moduleKeys = array_unique(array_merge(array_keys($before), array_keys($after)));

        $modules = [];
        foreach ($moduleKeys as $key) {
            $left = $before[$key] ?? null;
            $right = $after[$key] ?? null;
            $changed = in_array($key, $log->modules_touched ?? [], true)
                || (json_encode($left) !== json_encode($right));

            $modules[$key] = [
                'label' => EmployeeDataChangeLogService::moduleLabel($key),
                'before' => $left,
                'after' => $right,
                'changed' => $changed,
            ];
        }

        return view('employee_data_log.show', compact('log', 'modules'));
    }
}
