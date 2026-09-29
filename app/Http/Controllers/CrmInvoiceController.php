<?php

namespace App\Http\Controllers;

use App\Models\CrmInvoice;
use App\Models\User;
use App\Services\CrmInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CrmInvoiceController extends Controller
{
    protected function ensureSuperAdmin()
    {
        if (!Auth::check() || Auth::user()->type !== 'super admin') {
            abort(403, __('Only Super Administrators can manage company invoices.'));
        }
    }

    public function index(Request $request, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();

        $query = CrmInvoice::query()->with('company')->orderByDesc('id');

        if ($request->filled('company_id')) {
            $query->where('company_id', (int) $request->company_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->where('billing_year', (int) $request->year);
        }
        if ($request->filled('month')) {
            $query->where('billing_month', (int) $request->month);
        }

        $invoices = $query->paginate(25)->withQueryString();
        $companies = User::where('type', 'company')->orderBy('name')->get(['id', 'name', 'email']);
        $settings = $service->settings();

        return view('crm_invoice.index', compact('invoices', 'companies', 'settings'));
    }

    public function create(CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $companies = User::where('type', 'company')
            ->where('is_active', 1)
            ->whereHas('billingProfile', function ($q) {
                $q->where('billing_mode', \App\Models\CompanyBillingProfile::MODE_CUSTOM);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        $settings = $service->settings();

        return view('crm_invoice.create', compact('companies', 'settings'));
    }

    public function store(Request $request, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();

        $data = $request->validate([
            'company_id' => 'required|integer|exists:users,id',
            'billing_year' => 'required|integer|min:2020|max:2100',
            'billing_month' => 'required|integer|min:1|max:12',
            'active_employees_count' => 'nullable|integer|min:0',
            'active_users_count' => 'nullable|integer|min:0',
            'price_per_employee' => 'nullable|numeric|min:0',
            'price_per_user' => 'nullable|numeric|min:0',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:50',
            'company_gstin' => 'nullable|string|max:20',
            'ship_to_name' => 'nullable|string|max:255',
            'ship_to_address' => 'nullable|string',
            'place_of_supply' => 'nullable|string|max:100',
            'place_of_supply_code' => 'nullable|string|max:10',
            'terms' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'employee_description' => 'nullable|string|max:500',
            'user_description' => 'nullable|string|max:500',
        ]);

        $company = User::where('type', 'company')->findOrFail($data['company_id']);
        if (!$request->filled('active_employees_count')) {
            $data['active_employees_count'] = $service->countActiveEmployees($company->id);
        }
        if (!$request->filled('active_users_count')) {
            $data['active_users_count'] = $service->countActiveUsers($company->id);
        }

        try {
            $invoice = $service->createManual($data, Auth::id());
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('crm.invoices.show', $invoice->id)
            ->with('success', __('Invoice created successfully.'));
    }

    public function show($id)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::with(['items', 'emailLogs.sentBy'])->findOrFail($id);

        return view('crm_invoice.show', compact('invoice'));
    }

    public function edit($id, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::with('items')->findOrFail($id);
        if (!$invoice->isEditable()) {
            return redirect()->route('crm.invoices.show', $invoice->id)
                ->with('error', __('Paid invoices cannot be edited.'));
        }
        $settings = $service->settings();

        return view('crm_invoice.edit', compact('invoice', 'settings'));
    }

    public function update(Request $request, $id, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::findOrFail($id);

        $data = $request->validate([
            'billing_year' => 'required|integer|min:2020|max:2100',
            'billing_month' => 'required|integer|min:1|max:12',
            'active_employees_count' => 'required|integer|min:0',
            'active_users_count' => 'nullable|integer|min:0',
            'price_per_employee' => 'required|numeric|min:0',
            'price_per_user' => 'nullable|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:50',
            'company_gstin' => 'nullable|string|max:20',
            'ship_to_name' => 'nullable|string|max:255',
            'ship_to_address' => 'nullable|string',
            'place_of_supply' => 'nullable|string|max:100',
            'place_of_supply_code' => 'nullable|string|max:10',
            'terms' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'employee_description' => 'nullable|string|max:500',
            'user_description' => 'nullable|string|max:500',
        ]);

        try {
            $service->updateInvoice($invoice, $data, Auth::id());
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('crm.invoices.show', $invoice->id)
            ->with('success', __('Invoice updated successfully.'));
    }

    public function markPaid($id, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::findOrFail($id);
        $service->markPaid($invoice, Auth::id());

        return redirect()->back()->with('success', __('Invoice marked as Payment Complete. It is now locked.'));
    }

    public function downloadPdf($id, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::with('items')->findOrFail($id);

        // Always render with latest TAX INVOICE template
        $pdfPath = $service->generatePdf($invoice);
        $invoice->refresh();
        $downloadName = str_replace(['/', '\\'], '-', $invoice->invoice_number) . '.pdf';

        return response(Storage::disk('local')->get($pdfPath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function regeneratePdf($id, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::findOrFail($id);
        if (!$invoice->isEditable() && $invoice->status === CrmInvoice::STATUS_PAID) {
            // allow regenerate PDF for paid (view-only content) — optional; plan says immutable details, PDF regen OK
        }
        $service->generatePdf($invoice);

        return redirect()->back()->with('success', __('PDF regenerated.'));
    }

    public function send(Request $request, $id, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $invoice = CrmInvoice::findOrFail($id);

        $data = $request->validate([
            'recipients' => 'required|string',
        ]);

        $emails = preg_split('/[\s,;]+/', $data['recipients'], -1, PREG_SPLIT_NO_EMPTY);
        $valid = [];
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid[] = $email;
            }
        }

        if (empty($valid)) {
            return redirect()->back()->with('error', __('Please enter at least one valid email address.'));
        }

        $ok = $service->sendInvoiceEmail($invoice, $valid, Auth::id());

        return redirect()->back()->with(
            $ok ? 'success' : 'error',
            $ok ? __('Invoice emailed successfully.') : __('Failed to send invoice email. Check logs.')
        );
    }

    public function settings(CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $settings = $service->settings();

        return view('crm_invoice.settings', compact('settings'));
    }

    public function updateSettings(Request $request, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $settings = $service->settings();

        $data = $request->validate([
            'price_per_employee' => 'required|numeric|min:0',
            'cgst_percent' => 'required|numeric|min:0|max:100',
            'sgst_percent' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'due_days' => 'required|integer|min:0|max:120',
            'invoice_prefix' => 'required|string|max:20',
            'use_fy_numbering' => 'nullable|boolean',
            'seller_name' => 'nullable|string|max:255',
            'seller_address' => 'nullable|string',
            'seller_email' => 'nullable|email|max:255',
            'seller_phone' => 'nullable|string|max:50',
            'seller_gstin' => 'nullable|string|max:20',
            'seller_state' => 'nullable|string|max:100',
            'seller_state_code' => 'nullable|string|max:10',
            'product_name' => 'nullable|string|max:255',
            'payment_instructions' => 'nullable|string',
            'bank_name' => 'nullable|string|max:150',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:30',
            'bank_swift' => 'nullable|string|max:30',
            'default_hsn_sac' => 'nullable|string|max:20',
            'default_terms' => 'nullable|string|max:100',
            'default_notes' => 'nullable|string',
            'auto_email_on_generate' => 'nullable|boolean',
            'skip_zero_employee' => 'nullable|boolean',
            'logo' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',
        ]);

        $settings->fill([
            'price_per_employee' => $data['price_per_employee'],
            'cgst_percent' => $data['cgst_percent'],
            'sgst_percent' => $data['sgst_percent'],
            'currency' => $data['currency'],
            'due_days' => $data['due_days'],
            'invoice_prefix' => $data['invoice_prefix'],
            'use_fy_numbering' => $request->boolean('use_fy_numbering'),
            'seller_name' => $data['seller_name'] ?? null,
            'seller_address' => $data['seller_address'] ?? null,
            'seller_email' => $data['seller_email'] ?? null,
            'seller_phone' => $data['seller_phone'] ?? null,
            'seller_gstin' => $data['seller_gstin'] ?? null,
            'seller_state' => $data['seller_state'] ?? null,
            'seller_state_code' => $data['seller_state_code'] ?? null,
            'product_name' => $data['product_name'] ?? 'HR Shastri',
            'payment_instructions' => $data['payment_instructions'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account_no' => $data['bank_account_no'] ?? null,
            'bank_ifsc' => $data['bank_ifsc'] ?? null,
            'bank_swift' => $data['bank_swift'] ?? null,
            'default_hsn_sac' => $data['default_hsn_sac'] ?? null,
            'default_terms' => $data['default_terms'] ?? null,
            'default_notes' => $data['default_notes'] ?? null,
            'auto_email_on_generate' => $request->boolean('auto_email_on_generate'),
            'skip_zero_employee' => $request->boolean('skip_zero_employee'),
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('crm_invoice_logos', 'public');
            $settings->logo_path = $path;
        }
        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('crm_invoice_signatures', 'public');
            $settings->signature_path = $path;
        }

        $settings->save();

        return redirect()->route('crm.invoices.settings')->with('success', __('Invoice settings saved.'));
    }

    public function companyEmployeeCount(Request $request, CrmInvoiceService $service)
    {
        $this->ensureSuperAdmin();
        $companyId = (int) $request->get('company_id');
        $empCount = $companyId > 0 ? $service->countActiveEmployees($companyId) : 0;
        $userCount = $companyId > 0 ? $service->countActiveUsers($companyId) : 0;
        $profile = $companyId > 0 ? $service->billingProfileFor($companyId) : null;

        return response()->json([
            'active_employees' => $empCount,
            'active_users' => $userCount,
            'price_per_employee' => $profile->price_per_employee ?? null,
            'price_per_user' => $profile->price_per_user ?? null,
            'billing_mode' => $profile->billing_mode ?? 'global',
        ]);
    }
}
