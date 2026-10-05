<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Form16Record;
use App\Services\Form16Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Form16Controller extends Controller
{
    protected Form16Service $service;

    public function __construct(Form16Service $service)
    {
        $this->service = $service;
    }

    protected function guardCompany()
    {
        if (!Auth::check() || Auth::user()->type === 'employee') {
            abort(403, __('Permission denied.'));
        }
    }

    protected function loadAuthorizedRecord($id): Form16Record
    {
        try {
            return $this->service->assertRecordBelongsToCompany((int) $id);
        } catch (\RuntimeException $e) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                redirect()->route('form16.index')->with('error', $e->getMessage())
            );
        }
    }

    public function index(Request $request)
    {
        $this->guardCompany();
        $companyId = $this->service->companyId();

        $query = Form16Record::with('employee')
            ->where('company_id', $companyId)
            ->where('is_latest', 1);

        if ($request->filled('financial_year')) {
            $query->where('financial_year', $request->financial_year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', (int) $request->employee_id);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('employee_name', 'like', '%' . $q . '%')
                    ->orWhere('employee_code', 'like', '%' . $q . '%')
                    ->orWhere('employee_pan', 'like', '%' . $q . '%');
            });
        }

        $records = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        $years = $this->service->availableFinancialYears();
        $employees = Employee::where('created_by', $companyId)->orderBy('name')->get(['id', 'name', 'employee_id']);
        $statuses = Form16Record::STATUSES;

        return view('form16.index', compact('records', 'years', 'employees', 'statuses'));
    }

    /**
     * Entry from Employee List → Form 16
     */
    public function start(Request $request, $employeeEncrypted)
    {
        $this->guardCompany();
        try {
            $employeeId = Crypt::decrypt($employeeEncrypted);
            $employee = $this->service->assertEmployeeBelongsToCompany((int) $employeeId);
        } catch (\Throwable $e) {
            return redirect()->route('employee.index')->with('error', __('Invalid employee.'));
        }

        $years = $this->service->availableFinancialYears();

        return view('form16.start', compact('employee', 'years'));
    }

    public function init(Request $request)
    {
        $this->guardCompany();
        $request->validate([
            'employee_id' => 'required|integer',
            'financial_year' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'tax_regime' => 'nullable|in:old,new,unknown',
        ]);

        try {
            $employee = $this->service->assertEmployeeBelongsToCompany((int) $request->employee_id);
            $record = $this->service->createOrLoadDraft($employee, $request->financial_year, $request->tax_regime);
        } catch (\RuntimeException $e) {
            return redirect()->route('employee.index')->with('error', $e->getMessage());
        }

        return redirect()->route('form16.show', $record->id)
            ->with('success', __('Form 16 workspace loaded for FY :fy', ['fy' => $record->financial_year]));
    }

    public function show($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        try {
            $employee = $this->service->assertEmployeeBelongsToCompany($record->employee_id, $record->company_id);
        } catch (\RuntimeException $e) {
            return redirect()->route('form16.index')->with('error', $e->getMessage());
        }
        $partB = $record->part_b_data_array;
        $missingInfo = $record->validation_errors_array;
        $emailLogs = $record->emailLogs()->orderByDesc('id')->limit(20)->get();
        $versions = Form16Record::where('company_id', $record->company_id)
            ->where('employee_id', $record->employee_id)
            ->where('financial_year', $record->financial_year)
            ->orderByDesc('version')
            ->get(['id', 'version', 'status', 'generated_at', 'created_at', 'is_latest', 'regeneration_reason']);

        return view('form16.show', compact('record', 'employee', 'partB', 'missingInfo', 'emailLogs', 'versions'));
    }

    public function refresh($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        $this->service->refreshData($record, request('tax_regime'));
        return redirect()->route('form16.show', $record->id)->with('success', __('Form 16 data refreshed from payroll.'));
    }

    public function uploadPartA(Request $request, $id)
    {
        $this->guardCompany();
        $request->validate([
            'part_a_pdf' => 'required|file|mimes:pdf|max:10240',
            'part_a_certificate_number' => 'nullable|string|max:100',
        ]);
        $record = $this->loadAuthorizedRecord($id);
        try {
            $this->service->uploadPartA($record, $request->file('part_a_pdf'), $request->part_a_certificate_number);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('form16.show', $record->id)->with('success', __('TRACES Part A uploaded successfully.'));
    }

    public function deletePartA($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        try {
            $this->service->deletePartA($record);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('form16.show', $record->id)->with('success', __('Part A removed.'));
    }

    public function generate($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        try {
            $this->service->generatePartBPdf($record);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('form16.show', $record->id)->with('success', __('Employer Part B / Annexure generated.'));
    }

    public function verify($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        try {
            $this->service->verify($record);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('form16.show', $record->id)->with('success', __('Form 16 verified.'));
    }

    public function issue($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        try {
            $this->service->issue($record);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('form16.show', $record->id)->with('success', __('Form 16 marked as issued.'));
    }

    public function regenerate(Request $request, $id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);

        // Browser GET /form16/{id}/regenerate — send user to the Form 16 page form
        if ($request->isMethod('get')) {
            return redirect()
                ->route('form16.show', $record->id)
                ->with('error', __('Use the Regenerate form on this page and enter a reason.'));
        }

        $request->validate(['reason' => 'required|string|max:500']);
        try {
            $new = $this->service->regenerate($record, $request->reason);
        } catch (\Throwable $e) {
            return redirect()->route('form16.show', $record->id)->with('error', $e->getMessage());
        }

        return redirect()->route('form16.show', $new->id)->with('success', __('New Form 16 version created.'));
    }

    public function preview($id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        $partB = $record->part_b_data_array;
        return view('form16.preview', compact('record', 'partB'));
    }

    public function download(Request $request, $id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        $which = $request->get('which', 'final');
        try {
            [$path, $name] = $this->service->downloadAuthorized($record, $which);
        } catch (\Throwable $e) {
            return redirect()->route('form16.show', $record->id)->with('error', $e->getMessage());
        }
        return response()->download($path, $name, ['Content-Type' => 'application/pdf']);
    }

    public function send(Request $request, $id)
    {
        $this->guardCompany();
        $record = $this->loadAuthorizedRecord($id);
        try {
            $this->service->sendEmail($record);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', __('Email failed: :msg', ['msg' => $e->getMessage()]));
        }
        return redirect()->route('form16.show', $record->id)->with('success', __('Form 16 sent to employee email.'));
    }
}
