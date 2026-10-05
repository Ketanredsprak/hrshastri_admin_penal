<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Mail\UserCreate;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use App\Models\VisitType;
use App\Models\EXPTable;
use App\Models\EdcutionTable;
use App\Models\DependentData;
use App\Models\PayslipType;
use Spatie\Permission\Models\Role;
use File;
use DateTime;
use App\Models\NotificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\JoiningLetter;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
use App\Models\AllowanceOption;
use App\Models\Commission_option;
use App\Models\DeductionOption;
use App\Models\EmployeeDeductionsDetails;
use App\Models\EmployeeEarningsDetails;
use App\Models\ExperienceCertificate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\NOC;
use App\Models\GradeType;
use App\Services\EmployeeDataChangeLogService;
use App\Services\EmployeeDataSnapshotService;
use App\Models\EmployeeJoininglater;
use App\Models\LeaveType;
use App\Models\Termination;
use App\Models\UserLeaveBalance;
use App\Models\UserLeaveBalanceYear;
use App\Models\WorkingShiftEmployees;
use App\Models\WorkingShifts;
use App\Models\StatutoryDeductionsEpf;
use App\Models\EmployeesStateInsurance;
use App\Models\OtherPaymentotion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use DataTables;
use Form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
     public function get_users_data($id)
     {
          $users = User::where('roles_types', $id)->where('created_by', Auth::user()->creatorId())->get();
          return $users;
     }
     public function getEmployees($id)
     {
          $employees = Employee::where('play_slab_id', $id)->where('created_by', Auth::user()->creatorId())->get();
          return $employees;
     }

     public function index(request $request)
     {
          try {
               // $employees = Employee::where('id', '=', 145)->first();

               // $department = Department::find($employees->department_id);
               // $branch = Branch::find($employees->branch_id);
               // $designation = Designation::find($employees->designation_id);

               // $user = User::where("id", $employees->user_id)->first();
               // $uArr = [
               //      'employee_email'    => $user->email,
               //      'employee_password' => $user->see_password,
               //      'employee_name'     => $request['name'],
               //      'employee_branch'   => $branch->name,
               //      'employee_department'     => $department->name,
               //      'employee_designation'    => !empty($designation->name) ? $designation->name : '',
               // ];
               // $resp = \App\Models\Utility::sendEmailTemplate('new_employee', [$user->id => $user->email], $uArr);
               // dd($resp);

               $employees = Employee::with('playslab', 'selectUsers')->where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC');
               if (Auth::user()->can('Manage Employee')) {

                    if (Auth::user()->type == 'employee') {
                         $employees = Employee::with('playslab', 'selectUsers')->where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC');
                    } elseif (Auth::user()->type == 'super admin') {
                         $employees = Employee::with('login_sts', 'playslab', 'selectUsers')->orderBy('id', 'DESC');
                    } elseif (Auth::user()->type == 'company') {
                         $employees = Employee::with('login_sts', 'playslab', 'selectUsers')->where('created_by', Auth::user()->creatorId())->orderBy('id', 'DESC');
                    } else {
                         $selectedEmployees = selectedEmployee();
                         if (!empty($selectedEmployees)) {
                              $employees = Employee::whereIn('id', $selectedEmployees)->with('login_sts', 'playslab', 'selectUsers')
                                   ->where("created_by", Auth::user()->creatorId())
                                   ->orderBy('id', 'DESC');
                         } else {
                              $employees = Employee::with('login_sts', 'playslab', 'selectUsers')
                                   ->where("created_by", Auth::user()->creatorId())
                                   ->orderBy('id', 'DESC');
                         }
                    }

                    if ($request->ajax()) {

                         // Apply status filter if provided
                         // DataTables may send arrays in different formats
                         $statusFilter = $request->input('status_filter', []);

                         // Also check for array format status_filter[]
                         if (empty($statusFilter)) {
                              $statusFilter = $request->input('status_filter[]', []);
                         }

                         // Handle both array and string formats
                         if (is_string($statusFilter) && !empty($statusFilter)) {
                              $statusFilter = [$statusFilter];
                         }

                         if (!empty($statusFilter) && is_array($statusFilter)) {
                              // If "all" is in the filter, don't apply any status filter
                              if (!in_array('all', $statusFilter)) {
                                   $employees->whereIn('employe_status', $statusFilter);
                              }
                         }

                         $data = $employees;
                         return Datatables::of($data)
                              ->addIndexColumn()

                              ->addColumn('employee_id', function ($row) {
                                   return ((Auth::user()->hasPermissionTo('Show Employee')) ? '<a class="btn btn-outline-primary" href=" ' . route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($row->id)) . '">' . $row->employee_id . '</a>' : $row->employee_id);

                                   // return ((Auth::user()->hasPermissionTo('Show Employee')) ? '<a class="btn btn-outline-primary" href=" ' . route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($row->id)) . '">' . Auth::user()->employeeIdFormat($row->employee_id) . '</a>' : Auth::user()->employeeIdFormat($row->employee_id));
                              })

                              ->addColumn('branch_id', function ($row) {
                                   return (!empty(Auth::user()->getBranch($row->branch_id)) ? Auth::user()->getBranch($row->branch_id)->name : '');
                              })

                              ->addColumn('department_id', function ($row) {
                                   return (!empty(Auth::user()->getDepartment($row->department_id)) ? Auth::user()->getDepartment($row->department_id)->name : '');
                              })

                              ->addColumn('designation_id', function ($row) {
                                   return (!empty(Auth::user()->getDesignation($row->designation_id)) ? Auth::user()->getDesignation($row->designation_id)->name : '');
                              })

                              ->addColumn('playslab', function ($row) {
                                   return ((!empty($row->playslab)) ? $row->playslab->name : '');
                              })

                              ->addColumn('company_doj', function ($row) {
                                   return (Auth::user()->dateFormat($row->getRawOriginal("company_doj")));
                              })

                              ->editColumn('salary', function ($row) {
                                   return Auth::user()->priceFormat($row->ctc_annual / 12);
                              })

                              ->addColumn('delete_employe', function ($row) {
                                   $data = User::where('id', $row->user_id)->first();
                                   $statusRoute = "";
                                   $statusText = "In-active";

                                   // dd($data, $data->login_status);
                                   $bootstrapClass = "success";
                                   if ($row->employe_status == 'Resigned') {
                                        return "Resigned";
                                   } else if (!empty($data)) {
                                        if ($data->login_status == 1) {
                                             $statusRoute = "inactive_user";
                                             $statusText = '<i class="ti ti-check  text-white text-white">';
                                             $bootstrapClass = "success";
                                        } else {
                                             $statusRoute = "active_user";
                                             $statusText = '
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-cancel text-white text-white" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                             <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                             <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                             <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                             <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                             <path d="M17 21l4 -4" />
                                           </svg>
                                             ';
                                             $bootstrapClass = "danger";
                                        }
                                        return ((Auth::user()->hasPermissionTo('Delete Employee')) ? Form::open(['method' => 'get', 'route' => [$statusRoute, $row->id], 'id' => 'delete-form-' . $row->id]) . '<div class="action-btn bg-' . $bootstrapClass . ' ms-2">' . '<a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"data-bs-toggle="tooltip" title="" data-bs-original-title="InActive" aria-label="InActive">' . $statusText . '</i></a></div></form>' : '');
                                        // <i class="ti ti-check  text-white text-white"> 
                                    } else {
                                         return "Assign User";
                                    }
                              })

                              ->addColumn('edit_employe', function ($row) {

                                   $btn = (Auth::user()->hasPermissionTo('Edit Employee')) ? '<div class="action-btn bg-info ms-1"><a href="' . route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($row->id)) . '" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="' . __('Edit') . '"><i class="ti ti-pencil text-white"></i></a></div>' : '';

                                   $cn = (Auth::user()->hasPermissionTo('Delete Employee')) ? Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $row->id], 'id' => 'delete-form-' . $row->id, 'class' => 'd-inline']) . '<div class="action-btn bg-danger ms-1"><a href="#" class="btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white"></i></a></div></form>' : '';

                                   $form16 = '';
                                   if (config('form16.enabled') && (Auth::user()->hasPermissionTo('Manage Employee') || Auth::user()->type == 'company')) {
                                        $form16 = '<div class="action-btn bg-warning ms-1"><a href="' . route('form16.start', Crypt::encrypt($row->id)) . '" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="' . __('Form 16') . '"><i class="ti ti-file-certificate text-white"></i></a></div>';
                                   }

                                   $phone_configuration = '';
                                   if (!empty(Auth::user()->user_device_configurations)) {
                                        $phone_configuration = '
                                             <div class="action-btn bg-primary ms-1">
                                                  <a href="#" class="btn btn-sm align-items-center"
                                                       data-size="lg"
                                                       data-url="' . route('employee.phone.configuration', Crypt::encrypt(Auth::user()->id)) . '"
                                                       data-ajax-popup="true" data-size="md"
                                                       data-bs-toggle="tooltip" title=""
                                                       data-title="Phone Configuration"
                                                       data-bs-original-title="Phone Configuration">
                                                       <i class="ti ti-phone text-white"></i>
                                                  </a>
                                             </div>';
                                   }

                                   return (($row->is_active == 1) ? '<div class="d-inline-flex align-items-center">' . $btn . $form16 . $cn . $phone_configuration . '</div>' : '<i class="ti ti-lock"></i>');
                              })
                              ->rawColumns(['employee_id', 'branch_id', 'department_id', 'designation_id', 'playslab', 'company_doj', 'delete_employe', 'edit_employe'])
                              ->make(true);
                    } else {
                         return view("employee.index");
                    }
                    // return view('employee.index', compact('employees'));
               } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          } catch (\Throwable $e) {
               \Log::error('EmployeeController index error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
               if ($request->ajax()) {
                    return response()->json([
                         'draw' => intval($request->input('draw')),
                         'recordsTotal' => 0,
                         'recordsFiltered' => 0,
                         'data' => [],
                         'error' => $e->getMessage()
                    ]);
               }
               return redirect()->back()->with('error', __('Something went wrong.'));
          }
     }

     public function phoneConfiguration($id)
     {
          try {
               $id = Crypt::decrypt($id);
               $user = User::find($id);
               return view('employee.phone_configuration', compact('user'));
          } catch (Throwable $e) {
               return redirect()->back()->with('error', __('Something went wrong.'));
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Employee')) {
               $company_settings = Utility::settings();
               $documents = Document::where('created_by', Auth::user()->creatorId())->get();

               $gradeTypes = VisitType::where('created_by', '=', Auth::user()->creatorId())->get();

               $branches = Branch::where('created_by', Auth::user()->creatorId())->get();
               $departments = Department::where('status', 'Active')->where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $designations = Designation::where('status', 'Active')->where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $employees = User::where('created_by', Auth::user()->creatorId())->get();
               $employeesId = Auth::user()->employeeIdFormat($this->employeeNumber());
               $PayslipType = PayslipType::where('created_by', Auth::user()->creatorId())->get();
               $roles = Role::where('created_by', '=', Auth::user()->creatorId())->get();

               $workingShifts = WorkingShifts::where('created_by', '=', Auth::user()->creatorId())->get();

               $companyEpf = StatutoryDeductionsEpf::where("created_by", Auth::user()->creatorId())->first();
               $companyEsi = EmployeesStateInsurance::where("created_by", Auth::user()->creatorId())->first();

               $companyUser = User::where("id", Auth::user()->creatorId())->first();
               $professional_tax_status = $companyUser->professional_tax_status ?? 0;
               $professional_tax_number = $companyUser->professional_tax_number ?? '';

               $company_ctc_percentage = getCompanyCtc(Auth::user()->creatorId());

               $allowancesList = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
               $commissionList = Commission_option::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
               $otherPaymentList = OtherPaymentotion::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
               $deductionList = DeductionOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();

               return view('employee.create', compact('gradeTypes', 'roles', 'PayslipType', 'employees', 'employeesId', 'departments', 'designations', 'documents', 'branches', 'company_settings', 'workingShifts', 'company_ctc_percentage', 'companyEpf', 'companyEsi', 'professional_tax_status', 'professional_tax_number', 'allowancesList', 'commissionList', 'otherPaymentList', 'deductionList'));
          } else {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function store(Request $request)
     {
          $weekoffs_data = $request->weekoffs_data ?? "";
          if (Auth::user()->can('Create Employee')) {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         'employee_id' => 'required',
                         'name' => 'required',
                         'phone' => 'required | numeric|digits:10',
                         'dob' => 'required',
                         'salary' => 'required',
                         'dob' => 'required',
                         'address' => 'required',
                         'email' => 'required|unique:users',
                         'branch_id' => 'required',
                         'department_id' => 'required',
                         'designation_id' => 'required',
                         'company_doj' => 'required',
                         'employe_types' => 'required',
                         'password' => 'required',
                         'employe_status' => 'required',
                         'play_slab_id' => 'required',
                         'probation_period_date' => 'required',
                         'password_confirmation' => 'required_with:password|same:password|',

                         'esic_amount' => 'numeric|min:0|max:' . $request->input('salary'),

                         'uan_no' => 'nullable|alpha_num',

                         'ctc_annual' => 'required|numeric',
                         'ctc_percentage' => 'required|numeric',
                    ],
                    [
                         'branch_id' => 'The branch field is required.',
                         'department_id' => 'The department field is required.',
                         'designation_id' => 'The designation field is required.',
                         'company_doj' => 'The company date of joining field is required.',

                         'esic_amount.numeric' => 'ESIC Amount field should be numeric.',
                         'esic_amount.max' => 'ESIC Amount can not be grater than the salary amount',

                         'ctc_annual.required' => 'Annual Ctc Field is Required.',
                         'ctc_percentage.required' => 'Annual Ctc Percentage Field is Required.',
                    ]
               );

               if ($validator->fails()) {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }

               DB::beginTransaction();

               try {
                    $objUser = User::find(Auth::user()->creatorId());
                    $total_employee = $objUser->countEmployees();
                    $max_employees = $objUser->getMaxEmployees();
                    $date = date("Y-m-d H:i:s");

                    $user = [];
                    if ($max_employees == -1 || $total_employee < $max_employees) {
                         $user = User::create([
                              'name' => $request['name'],
                              'email' => $request['email'],
                              'password' => Hash::make($request['password']),
                              'see_password' => $request['password'],
                              'type' => 'employee',
                              'lang' => 'en',
                              'created_by' => Auth::user()->creatorId(),
                              'email_verified_at' => $date,
                         ]);
                         $user->save();
                         $user->assignRole('Employee');
                    } else {
                         DB::rollBack();
                         return response()->json(['status_error' => 1, 'message' => __('Your employee limit is over, Please upgrade plan.')]);
                         // return response()->json(['status' => 0,'message' => __('Your employee limit is over, Please upgrade plan.')]);
                    }
                    if (!empty($request->document) && !is_null($request->document)) {
                         $document_implode = implode(',', array_keys($request->document));
                    } else {
                         $document_implode = null;
                    }

                    $companyEpf = StatutoryDeductionsEpf::where("created_by", Auth::user()->creatorId())->first();

                    $epf_status = $request->has("epf_status") ? 1 : 0;
                    $epf_number = $request->input("epf_number") ?? null;
                    $employee_contribution_rate = $request->input("employee_contribution_rate") ?? 0;
                    $employer_contribution_rate = $request->input("employer_contribution_rate") ?? 0;

                    $marriage_anniversary_date = null;
                    $marital_status = $request->marital_status ?? "";

                    if ($marital_status == "Married" || $marital_status == "Widowed") {
                         $marriage_anniversary_date = $request->marriage_anniversary_date ?? null;
                    }

                    // Add Custom weekoff data
                    $weekoffs_data = $request->weekoffs_data ?? "";
                    $is_custom_weekoffs = $request->has("is_custom_weekoffs") ? 1 : 0;
                    if ($is_custom_weekoffs == 0 || $weekoffs_data == "[]") {
                         $weekoffs_data = "";
                    }
                    // Add Custom weekoff data

                    $employee = Employee::create([
                         'user_id' => $user->id,
                         'name' => $request['name'],
                         'dob' => $request['dob'],
                         'gender' => $request['gender'],
                         'phone' => $request['phone'],
                         'address' => $request['address'],
                         'email' => $request['email'],
                         'personal_email' => $request['personal_email'],
                         'password' => Hash::make($request['password']),
                         'employee_id' => $request['employee_id'] ?? $this->employeeNumber(),
                         'branch_id' => $request['branch_id'],
                         'play_slab_id' => $request['play_slab_id'],
                         "users_types_id" => $request['select_users'],
                         'salary' => $request['salary'],
                         'department_id' => $request['department_id'],
                         'designation_id' => $request['designation_id'],
                         'designation_description' => $request['designation_description'],
                         'company_doj' => $request['company_doj'],
                         'documents' => $document_implode,
                         'account_holder_name' => $request['account_holder_name'],
                         'account_number' => $request['account_number'],
                         'bank_name' => $request['bank_name'],
                         'bank_identifier_code' => $request['bank_identifier_code'],
                         'branch_location' => $request['branch_location'],
                         'tax_payer_id' => $request['tax_payer_id'],
                         'created_by' => Auth::user()->creatorId(),
                         'marital_status' => $marital_status,
                         'marriage_anniversary_date' => $marriage_anniversary_date,
                         'employe_status' => $request->employe_status,
                         'employe_types' => $request->employe_types,
                         'employee_experience' => $request->employee_experience,
                         'date_of_exit' => $request->date_of_exit,
                         'Job_description' => $request->Job_description,
                         'grade_type_id' => $request->select_grade_type,
                         'office_staff' => $request->select_office_staff ?? 0,
                         'blood_group' => $request->blood_group,
                         'pf_number' => $request->pf_number,
                         'pan_number' => $request->pan_number,
                         'uan_number' => $request->uan_number,

                         'probation_period_date' => $request['probation_period_date'] ?? null,
                         'confirm_probation_period' => $request->has('confirm_probation_period') ? 1 : 0,
                         'app_login' => $request->has('app_login') ? 1 : 0,
                         'web_login' => $request->has('web_login') ? 1 : 0,

                         'ctc_annual' => $request->input("ctc_annual") ?? 0,
                         'ctc_percentage' => $request->input("ctc_percentage") ?? 0,

                         "epf_status" => $epf_status,
                         "epf_number" => $epf_number,
                         'uan_no' => $request->input("uan_no") ?? null,
                         "epf_employee_contribution_rate" => $employee_contribution_rate,
                         "epf_employer_contribution_rate" => $employer_contribution_rate,

                         "esi_status" => $request->has("esi_status") ? 1 : 0,
                         "esi_number" => $request->input("esi_number") ?? null,

                         "ept_status" => $request->has("ept_status") ? 1 : 0,
                         "ept_number" => $request->input("ept_number") ?? "",

                         "weekoffs_data" => $weekoffs_data,
                         "is_custom_weekoffs" => $is_custom_weekoffs,
                    ]);



                    // Save Employee Profile Picture

                    if ($request->hasFile("employee_profile")) {
                         $extension = $request->file('employee_profile')->getClientOriginalExtension();
                         $employee_profile_image_name = $employee->id . "_" . rand(1, 100000000) . time() . '.' . $extension;
                         $dir = 'uploads/employee_profile/';
                         $path = Utility::upload_file($request, 'employee_profile', $employee_profile_image_name, $dir, []);

                         if ($path['flag'] != 1) {
                              $employee_profile_image_name = "";
                         }

                         $employee->employee_profile = $employee_profile_image_name;

                         $user->avatar = $employee_profile_image_name;
                    }



                    // Save Employee Profile Picture




                    // check geofencing
                    $branchDetails = Branch::find($request['branch_id']);
                    if ($branchDetails != null) {
                         if ($branchDetails->geofencing_status == 1) {
                              if ($request->has("geofencing_status")) {
                                   $employee->geofencing_status = 1;
                              } else {
                                   $employee->geofencing_status = 0;
                              }
                         } else {
                              $employee->geofencing_status = 0;
                         }
                    }
                    // check geofencing
                    $employee->latitude = $branchDetails->latitude;
                    $employee->longitude = $branchDetails->longitude;
                    // Employee Earnings and Deductions

                    $earning_ids = $request->earning_id ?? [];
                    $earning_amount = $request->earning_amount ?? [];
                    $earning_type = $request->earning_type ?? [];
                    $earning_amount_type = $request->earning_amount_type ?? [];

                    $deduction_ids = $request->deduction_id ?? [];
                    $deduction_amount = $request->deduction_amount ?? [];
                    $deduction_type = $request->deduction_type ?? [];
                    $deduction_amount_type = $request->deduction_amount_type ?? [];

                    $earningsData = [];
                    $deductionsData = [];

                    $deductionList = DeductionOption::with('playslab')->where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();

                    // Earnings Data
                    try {
                         foreach ($earning_ids as $earningId) {
                              try {
                                   // earning_type  | 0 = Allowances, 1 = Commission, 2 = other payment earnings,

                                   $earningAmountType = 0;
                                   $earningType = 0;
                                   $earningAmount = 0;

                                   if (array_key_exists($earningId, $earning_type)) {
                                        $earningType = $earning_type[$earningId] ?? 0;
                                   }

                                   if (array_key_exists($earningId, $earning_amount)) {
                                        $earningAmount = $earning_amount[$earningId] ?? 0;
                                   }

                                   if (array_key_exists($earningId, $earning_amount_type)) {
                                        $earningAmountType = $earning_amount_type[$earningId] == "percentage" ? 1 : 0;
                                   }
                              } catch (Exception | Throwable $ex) {
                              }

                              if ($earningType == 0) {
                                   $earningData = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->where("id", $earningId)->first();
                              } else if ($earningType == 1) {
                                   $earningData = Commission_option::where('created_by', '=', Auth::user()->creatorId())->where("id", $earningId)->first();
                              } else if ($earningType == 2) {
                                   $earningData = OtherPaymentotion::where('created_by', '=', Auth::user()->creatorId())->where("id", $earningId)->first();
                              }

                              $name = "";
                              if ($earningData != null) {
                                   $name = $earningData->name;
                              }

                              $earningsData[] = [
                                   // "earningName" => $name,
                                   "employees_id" => $employee->id,
                                   "earning_type" => $earningType,
                                   "earning_id" => $earningId,
                                   "amount" => $earningAmount,
                                   "amount_type" => $earningAmountType,
                                   "status" => 1,
                                   "created_by" => Auth::user()->creatorId()
                              ];
                         }
                    } catch (Exception | Throwable $ex) {
                    }

                    // Statutory Bonus Earnings
                    $statutory_bonus_status = $request->has("statutory_bonus_status") ? 1 : 0;
                    if ($statutory_bonus_status == 1) {
                         $employee->statutory_bonus = $request->input("statutory_bonus") ?? 0;
                    }

                    $employee->statutory_bonus_status = $statutory_bonus_status;
                    // Statutory Bonus Earnings

                    // Earnings Data


                    // Deductions Data
                    try {
                         foreach ($deduction_ids as $deductionId) {
                              try {
                                   // earning_type  | 0 = Allowances, 1 = Commission, 2 = other payment earnings,

                                   $deductionAmountType = 0;
                                   $deductionType = 0;
                                   $deductionAmount = 0;

                                   if (array_key_exists($deductionId, $deduction_type)) {
                                        $deductionType = $deduction_type[$deductionId] ?? 0;
                                   }

                                   if (array_key_exists($deductionId, $deduction_amount)) {
                                        $deductionAmount = $deduction_amount[$deductionId] ?? 0;
                                   }

                                   if (array_key_exists($deductionId, $deduction_amount_type)) {
                                        $deductionAmountType = $deduction_amount_type[$deductionId] == "percentage" ? 1 : 0;
                                   }
                              } catch (Exception | Throwable $ex) {
                              }

                              $deductionData = DeductionOption::where('created_by', '=', Auth::user()->creatorId())->where("id", $deductionId)->first();

                              $name = "";
                              if ($deductionData != null) {
                                   $name = $deductionData->name;
                              }

                              $deductionsData[] = [
                                   // "deductionName" => $name,
                                   "employees_id" => $employee->id,
                                   "deduction_type" => $deductionType,
                                   "deduction_id" => $deductionId,
                                   "amount" => $deductionAmount,
                                   "amount_type" => $deductionAmountType,
                                   "status" => 1,
                                   "created_by" => Auth::user()->creatorId()
                              ];
                         }
                    } catch (Exception | Throwable $ex) {
                    }

                    // Employee Provident Fund
                    $companyEpf = StatutoryDeductionsEpf::where("created_by", Auth::user()->creatorId())->first();

                    $epf_status = $request->has("epf_status") ? 1 : 0;
                    if ($companyEpf != null) {
                         if ($companyEpf->override_employee_pf == 1 && $epf_status == 1) {
                              $employee->epf_employee_contribution_rate = $request->input("employee_contribution_rate") ?? 0;
                              $employee->epf_employer_contribution_rate = $request->input("employer_contribution_rate") ?? 0;
                              $employee->epf_number = $request->input("epf_number") ?? "";
                         }
                    }

                    $employee->epf_status = $epf_status;
                    // Employee Provident Fund


                    // Tax Deducted at Source (TDS)
                    $tax_deducted_at_source_status = $request->has("tax_deducted_at_source_status") ? 1 : 0;
                    if ($tax_deducted_at_source_status == 1) {
                         $employee->tax_deducted_at_source = $request->input("tax_deducted_at_source") ?? 0;
                    }

                    $employee->tax_deducted_at_source_status = $tax_deducted_at_source_status;
                    // Tax Deducted at Source (TDS)

                    // Deductions Data

                    if (count($earningsData) > 0) {
                         EmployeeEarningsDetails::insert($earningsData);
                    }

                    if (count($deductionsData) > 0) {
                         EmployeeDeductionsDetails::insert($deductionsData);
                    }


                    // Employee Earnings and Deductions

                    // assign_shifts
                    $assign_shifts = $request->input("assign_shifts") ?? [];

                    foreach ($assign_shifts as $shiftId) {
                         if ($shiftId > 0) {
                              WorkingShiftEmployees::create([
                                   "working_shifts_id" => $shiftId,
                                   "employee_id" => $employee->id
                              ]);
                         }
                    }
                    // assign_shifts

                    $assign_employees = selectedEmployee() ?? [];

                    $assign_employees[] = (string) $employee->id;

                    User::where("id", Auth::user()->id)->update(['assign_employees' => $assign_employees]);

                    if (!empty($request->previous_company_name[0])) {
                         foreach ($request->previous_company_name as $key => $institute2) {
                              $eduData = new EXPTable();
                              $eduData->employee_id = $employee->id;
                              $eduData->previous_company_name = $request->previous_company_name[$key];
                              $eduData->previous_job_title = $request->previous_job_title[$key];
                              $eduData->previous_from_date = $request->previous_from_date[$key];
                              $eduData->previous_to_date = $request->previous_to_date[$key];
                              $eduData->previous_company_designation_id = $request->previous_company_designation_id[$key];
                              $eduData->experiance_Job_description = $request->experiance_Job_description[$key];
                              $eduData->save();
                         }
                    }
                    if (!empty($request->institute_name[0])) {
                         foreach ($request->institute_name as $key => $institute2) {
                              $eduData = new EdcutionTable();
                              $eduData->employee_id = $employee->id;
                              $eduData->institute_name = $request->institute_name[$key];
                              $eduData->education = $request->education[$key];
                              $eduData->specialization = $request->specialization[$key];
                              $eduData->date_of_completion = $request->date_of_completion[$key];
                              $eduData->notes = $request->notes[$key];
                              $eduData->save();
                         }
                    }
                    if (!empty($request->dependent_name[0])) {
                         foreach ($request->dependent_name as $key => $institute2) {
                              $eduData = new DependentData();
                              $eduData->employee_id = $employee->id;
                              $eduData->dependent_name = $request->dependent_name[$key];
                              $eduData->dependent_relation = $request->dependent_relation[$key];
                              $eduData->dependent_dob = $request->dependent_dob[$key];
                              $eduData->emergency_phone_no = $request->emergency_phone_no[$key];
                              $eduData->save();
                         }
                    }

                    if ($request->hasFile('document')) {
                         foreach ($request->document as $key => $document) {
                              $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                              $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                              $extension = $request->file('document')[$key]->getClientOriginalExtension();
                              $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                              $dir = 'uploads/document/';
                              $image_path = $dir . $fileNameToStore;
                              if (\File::exists($image_path)) {
                                   \File::delete($image_path);
                              }

                              $path = \App\Models\Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);
                              if ($path['flag'] == 1) {
                                   $url = $path['url'];
                              } else {
                                   return response()->json(['status' => 0, 'message' => __($path['msg'])]);
                              }
                              $employee_document = EmployeeDocument::create([
                                   'employee_id' => $employee['id'],
                                   'document_id' => $key,
                                   'document_value' => $path['url'],
                                   'created_by' => Auth::user()->creatorId(),
                              ]);
                              $employee_document->save();
                         }
                    }

                    $probation_period_date = $request['probation_period_date'] ?? "";
                    if (
                         empty($probation_period_date)
                         // || (date("Y", strtotime($probation_period_date)) == date("Y") && date("m", strtotime($probation_period_date)) == date("m") &&
                         //      date("Y-m-d", strtotime($probation_period_date)) <= Carbon::now()->endOfMonth()->format("Y-m-d"))
                    ) {
                         // add leave balance
                         $leaveTypes = LeaveType::where("play_slab_id", $employee->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
                         foreach ($leaveTypes as $leaveType) {
                              $addLeave = ($leaveType->days / 12);

                              UserLeaveBalance::create([
                                   "user_id" => $employee->id,
                                   "leave_type_id" => $leaveType->id,
                                   "created_by" => Auth::user()->creatorId(),
                                   "pending_leaves" => $addLeave,
                              ]);

                              UserLeaveBalanceYear::create([
                                   "user_id" => $employee->id,
                                   "leave_type_id" => $leaveType->id,
                                   "created_by" => Auth::user()->creatorId(),
                                   "pending_leaves" => $addLeave,
                                   "leaves_year" => date('Y'),
                              ]);
                         }
                         // add leave balance
                    } else {
                         // add leave balance
                         $leaveTypes = LeaveType::where("play_slab_id", $employee->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
                         foreach ($leaveTypes as $leaveType) {
                              $addLeave = ($leaveType->days / 12);

                              UserLeaveBalance::create([
                                   "user_id" => $employee->id,
                                   "leave_type_id" => $leaveType->id,
                                   "created_by" => Auth::user()->creatorId(),
                                   "pending_leaves" => 0,
                              ]);

                              UserLeaveBalanceYear::create([
                                   "user_id" => $employee->id,
                                   "leave_type_id" => $leaveType->id,
                                   "created_by" => Auth::user()->creatorId(),
                                   "pending_leaves" => 0,
                                   "leaves_year" => date('Y'),
                              ]);
                         }
                         // add leave balance
                    }

                    $employee->save();

                    // dd("before commit");
                    DB::commit();

                    $setings = \App\Models\Utility::settings();

                    if ($setings['new_employee'] == 1) {
                         $department = Department::find($request['department_id']);
                         $branch = Branch::find($request['branch_id']);
                         $designation = Designation::find($request['designation_id']);
                         $uArr = [
                              'employee_email' => $user->email,
                              'employee_password' => $request->password,
                              'employee_name' => $request['name'],
                              'employee_branch' => $branch->name,
                              'employee_department' => $department->name,
                              'employee_designation' => !empty($designation->name) ? $designation->name : '',
                         ];
                         $resp = \App\Models\Utility::sendEmailTemplate('new_employee', [$user->id => $user->email], $uArr);

                         return response()->json(['status' => 1, 'message' => __('Employee successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : '')]);
                    }

                    return response()->json(['status' => 1, 'message' => __('Employee successfully created.')]);
               } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
               } catch (Throwable $e) {
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
               }
          } else {
               return response()->json(['status' => 0, 'message' => __('Permission denied.')]);
          }
     }

     public function edit($id)
     {
          $id = Crypt::decrypt($id);
          if (Auth::user()->type == 'employee') {
               $checkUserData = User::find(Auth::id());
               $checkEmployeeData = Employee::where('user_id', ($checkUserData->id ?? 0))->first();

               if ($checkEmployeeData != null) {
                    $id = $checkEmployeeData->id;
               } else {
                    return redirect()->back()->with('error', __('Invalid User.'));
               }
          }


          if (Auth::user()->can('Edit Employee') || Auth::user()->type == 'employee') {
               $employee = Employee::select("*", "employee_profile as employee_profile_image")->where("id", $id)->first();

               if ($employee == null) {
                    return redirect()->back()->with('error', __('Employee not found.'));
               }

               // Employees may only edit their own profile
               if (Auth::user()->type == 'employee' && (int) $employee->user_id !== (int) Auth::id()) {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }

               $documents = Document::where('created_by', Auth::user()->creatorId())->get();
               $gradeTypes = VisitType::where('created_by', '=', Auth::user()->creatorId())->get();
               $branches = Branch::where('created_by', Auth::user()->creatorId())->get();
               $departments = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');



               $employeesId = Auth::user()->employeeIdFormat($employee->employee_id);
               $PayslipType = PayslipType::where('created_by', Auth::user()->creatorId())->get();
               $roles = Role::where('created_by', '=', Auth::user()->creatorId())->get();

               $usersData = User::find($employee->user_id);

               $EXPTableData = EXPTable::where('employee_id', $id)->orderBy('id', 'asc')->get();
               $EXPTableDatacount = count($EXPTableData);
               $EducationData = EdcutionTable::where('employee_id', $id)->orderBy('id', 'asc')->get();
               $EducationDatacount = count($EXPTableData);
               $DependentData = DependentData::where('employee_id', $id)->orderBy('id', 'asc')->get();
               $DependentDatacount = count($DependentData);

               $probation_period_months = null;

               $date1 = new DateTime($employee->getRawOriginal("company_doj"));
               $date2 = new DateTime($employee->getRawOriginal("probation_period_date"));
               $interval = $date1->diff($date2);
               $probation_period_months = ($interval->y * 12) + $interval->m;

               $workingShifts = WorkingShifts::where('created_by', '=', Auth::user()->creatorId())->get();
               $assignedWorkingShifts = WorkingShiftEmployees::where('employee_id', '=', $employee->id)->get()->pluck("working_shifts_id")->toArray();


               $company_ctc_percentage = ($employee->ctc_percentage <= 0 ? getCompanyCtc(Auth::user()->creatorId()) : $employee->ctc_percentage);

               $companyEpf = StatutoryDeductionsEpf::where("created_by", Auth::user()->creatorId())->first();
               $companyEsi = EmployeesStateInsurance::where("created_by", Auth::user()->creatorId())->first();

               $companyUser = User::where("id", Auth::user()->creatorId())->first();
               $professional_tax_status = $companyUser->professional_tax_status ?? 0;
               $professional_tax_number = $companyUser->professional_tax_number ?? '';

               $allowancesList = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
               $commissionList = Commission_option::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
               $otherPaymentList = OtherPaymentotion::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
               $deductionList = DeductionOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();

               $employeeEarningsData = EmployeeEarningsDetails::where("employees_id", $employee->id)->get();
               $employeeDeductionData = EmployeeDeductionsDetails::where("employees_id", $employee->id)->get();

               $payslipDetail = getEmployeeSalaryStructure($employee->id, date("Y") . '-' . date("m"));

               return view('employee.edit', compact('probation_period_months', 'gradeTypes', 'usersData', 'roles', 'PayslipType', 'DependentData', 'DependentDatacount', 'EXPTableDatacount', 'EducationDatacount', 'EXPTableData', 'EducationData', 'employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'workingShifts', 'assignedWorkingShifts', 'company_ctc_percentage', 'companyEpf', 'companyEsi', 'professional_tax_status', 'professional_tax_number', "allowancesList", "commissionList", "otherPaymentList", "deductionList", "employeeEarningsData", "employeeDeductionData", "payslipDetail"));
          } else {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function exp_table_remove($id)
     {
          EXPTable::where('id', $id)->delete();
          return redirect()->back()->with('success', 'Experience data remove successfully');
     }

     public function eduction_table_remove($id)
     {
          EdcutionTable::where('id', $id)->delete();
          return redirect()->back()->with('success', 'Education data remove successfully');
     }

     public function Dependent_table_remove($id)
     {
          DependentData::where('id', $id)->delete();
          return redirect()->back()->with('success', 'Dependent data remove successfully');
     }

     public function update(Request $request, $id)
     {
          if (Auth::user()->can('Edit Employee')) {
               $employeeDetails = Employee::findOrFail($id);

               $userId = 0;
               if ($employeeDetails != null) {
                    $userId = (User::where("id", $employeeDetails->user_id)->first()->id ?? 0);
               }

               $validation = [];

               $validation = [
                    'name' => 'required',
                    'phone' => 'required |numeric|digits:10',
                    'email' => 'required|unique:users,email,' . $userId,
                    'dob' => 'required',
                    'address' => 'required',
               ];

               $isProfileEdit = false;
               if (Auth::user()->type != 'company') {
                    $employee = Employee::findOrFail($id);

                    $loggedInEmployee = Employee::where('user_id', \Auth::user()->id)->first();
                    if ($employee->id == ($loggedInEmployee->id ?? 0)) {
                         $isProfileEdit = true;
                    }
               }

               if ($isProfileEdit == false) {
                    $validation['play_slab_id'] = 'required';
                    $validation['salary'] = 'required';
                    $validation['department_id'] = 'required';
                    $validation['designation_id'] = 'required';
                    $validation['employe_types'] = 'required';
                    $validation['employe_status'] = 'required';
                    $validation['probation_period_date'] = 'required';
                    $validation['esic_amount'] = 'numeric|min:0|max:' . $request->input('salary');

                    $validation['uan_no'] = 'nullable|alpha_num';
                    $validation['ctc_annual'] = 'required|numeric';
                    $validation['ctc_percentage'] = 'required|numeric';

                    // $validation['acount_holder_name'] = 'required';
                    // $validation['acount_number'] = 'required';
                    // $validation['bnk_identifier_code'] = 'required';
                    // $validation['banch_location'] = 'required';
               }

               $validator = \Validator::make(
                    $request->all(),
                    $validation,
                    [
                         'play_slab_id' => 'The play slab field is required.',
                         'department_id' => 'The department field is required.',
                         'designation_id' => 'The designation field is required.',

                         'esic_amount.numeric' => 'ESIC Amount field should be numeric.',
                         'esic_amount.max' => 'ESIC Amount can not be grater than the salary amount',

                         'ctc_annual.required' => 'Annual Ctc Field is Required.',
                         'ctc_percentage.required' => 'Annual Ctc Percentage Field is Required.',
                    ]
               );

               // Add password validation only if password is provided
               if (!empty($request->password)) {
                    $passwordValidation = [
                         'password' => 'required|min:6',
                         'password_confirmation' => 'required_with:password|same:password',
                    ];
                    $passwordValidator = \Validator::make($request->all(), $passwordValidation);
                    if ($passwordValidator->fails()) {
                         $messages = $passwordValidator->messages();
                         return response()->json(['status' => 0, 'message' => $messages]);
                    }
               }

               if ($validator->fails()) {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }

               // check dependent fields
               if (!empty($request->dependent_name[0])) {
                    foreach ($request->dependent_name as $key => $institute2) {
                         $dependent_name = $request->dependent_name[$key] ?? "";
                         if (!empty(trim($dependent_name))) {
                              $dependent_relation = $request->dependent_relation[$key] ?? "";
                              if (empty($dependent_relation) || $dependent_relation == 00) {
                                   return response()->json(
                                        [
                                             'status' => 0,
                                             'message' => [
                                                  'dependent_relation' => ['Relation type field is required.']
                                             ]
                                        ]
                                   );
                                   exit;
                              }
                         }
                    }
               }
               // check dependent fields



               try {
                    DB::beginTransaction();

                    $employee = Employee::findOrFail($id);

                    $auditBefore = [];
                    $auditEnabled = false;
                    try {
                         $auditLogService = app(EmployeeDataChangeLogService::class);
                         $auditEnabled = $auditLogService->isEnabled((int) Auth::user()->creatorId());
                         if ($auditEnabled) {
                              $auditBefore = app(EmployeeDataSnapshotService::class)->capture((int) $employee->id);
                         }
                    } catch (Throwable $auditEx) {
                         $auditEnabled = false;
                    }

                    $confirm_probation_period_old = $employee->confirm_probation_period;

                    $marriage_anniversary_date = null;
                    $marital_status = $request->marital_status ?? "";

                    if ($marital_status == "Married" || $marital_status == "Widowed") {
                         $marriage_anniversary_date = $request->marriage_anniversary_date ?? null;
                    } else {
                         // $marriage_anniversary_date = $employee->marriage_anniversary_date;
                    }

                    // $employee->employee_id = $request['employee_id'] ?? $this->employeeNumber();
                    $employee->employee_id = $request['employee_id'];

                    $employee->name = $request['name'];
                    $employee->email = $request['email'];
                    $employee->personal_email = $request['personal_email'];
                    $employee->dob = $request['dob'];
                    $employee->gender = $request['gender'];
                    $employee->phone = $request['phone'];
                    $employee->address = $request['address'];
                    $employee->marital_status = $marital_status;
                    $employee->marriage_anniversary_date = $marriage_anniversary_date;



                    if ($isProfileEdit == false) {
                         $employee->branch_id = $request['branch_id'];
                         $employee->play_slab_id = $request['play_slab_id'];
                         $employee->salary = $request['salary'];
                         $employee->department_id = $request['department_id'];
                         $employee->designation_id = $request['designation_id'];
                         $employee->designation_description = $request['designation_description'];
                         $employee->company_doj = $request['company_doj'];
                         $employee->documents = $request['documents'];
                         $employee->account_holder_name = $request['account_holder_name'];
                         $employee->account_number = $request['account_number'];
                         $employee->bank_name = $request['bank_name'];
                         $employee->bank_identifier_code = $request['bank_identifier_code'];
                         $employee->branch_location = $request['branch_location'];
                         $employee->tax_payer_id = $request['tax_payer_id'];
                         $employee->employe_status = $request['employe_status'];
                         $employee->employe_types = $request['employe_types'];
                         $employee->employee_experience = $request['employee_experience'];
                         $employee->date_of_exit = $request['date_of_exit'];
                         $employee->Job_description = $request['Job_description'];
                         $employee->grade_type_id = $request['grade_type_id'];
                         $employee->office_staff = $request['office_staff'];
                         $employee->employee_exit = $request->has('employee_exit') ? 1 : 0;
                         $employee->probation_period_date = $request['probation_period_date'];
                         $employee->confirm_probation_period = $request->has('confirm_probation_period') ? 1 : 0;
                         $employee->app_login = $request->has('app_login') ? 1 : 0;
                         $employee->web_login = $request->has('web_login') ? 1 : 0;

                         $employee->ctc_annual = $request['ctc_annual'];
                         // $employee->ctc_percentage = $request['ctc_percentage'];

                         // 'ctc_annual' => $request->input("ctc_annual") ?? 0,
                         // 'ctc_percentage' => $request->input("ctc_percentage") ?? 0,

                         $employee->uan_no = $request->input("uan_no") ?? null;


                         // #DEBUG

                         $calculatedCtcPercentage = 0;
                         try {

                              $salary = $request->input('salary');
                              $ctc_monthly = ($request['ctc_annual'] / 12);

                              $calculatedCtcPercentage = ($salary / $ctc_monthly) * 100;

                              if (is_nan($calculatedCtcPercentage) || !is_finite($calculatedCtcPercentage)) {
                                   $calculatedCtcPercentage = 0;
                              }
                         } catch (\Throwable $th) {
                              //throw $th;
                         }

                         // dd($calculatedCtcPercentage);

                         $employee->ctc_percentage = $calculatedCtcPercentage;

                         // #DEBUG


                         $employee->esi_status = $request->has("esi_status") ? 1 : 0;
                         $employee->esi_number = $request->input("esi_number") ?? null;

                         $employee->ept_status = $request->has("ept_status") ? 1 : 0;
                         $employee->ept_number = $request->input("ept_number") ?? "";

                         // check geofencing
                         $branchDetails = Branch::find($request['branch_id']);
                         if ($branchDetails != null) {
                              if ($branchDetails->geofencing_status == 1) {
                                   if ($request->has("geofencing_status")) {
                                        $employee->geofencing_status = 1;
                                   } else {
                                        $employee->geofencing_status = 0;
                                   }
                              } else {
                                   $employee->geofencing_status = 0;
                              }
                         }
                         // check geofencing

                         $employee->latitude = $branchDetails->latitude;
                         $employee->longitude = $branchDetails->longitude;

                         // Add Custom weekoff data
                         $weekoffs_data = $request->weekoffs_data ?? "";
                         $is_custom_weekoffs = $request->has("is_custom_weekoffs") ? 1 : 0;
                         if ($is_custom_weekoffs == 0 || $weekoffs_data == "[]") {
                              $weekoffs_data = $employee->weekoffs_data;
                         }

                         if (($request->has("is_custom_weekoffs") ? 1 : 0) == 1 && ($request->weekoffs_data == "[]" || empty(trim($request->weekoffs_data)))) {
                              $weekoffs_data = "";
                              $is_custom_weekoffs = 0;
                         }

                         $employee->weekoffs_data = $weekoffs_data;
                         $employee->is_custom_weekoffs = $is_custom_weekoffs;
                         // dd($is_custom_weekoffs, $weekoffs_data, json_decode($weekoffs_data), json_encode($weekoffs_data));
                         // Add Custom weekoff data
                    }

                    // Save Employee Profile Picture
                    if ($request->hasFile("employee_profile")) {
                         $extension = $request->file('employee_profile')->getClientOriginalExtension();
                         $employee_profile_image_name = $employee->id . "_" . rand(1, 100000000) . time() . '.' . $extension;
                         $dir = 'uploads/employee_profile/';
                         $path = Utility::upload_file($request, 'employee_profile', $employee_profile_image_name, $dir, []);

                         if ($path['flag'] == 1) {
                              if ($employee->employee_profile != null || !empty(trim($employee->employee_profile))) {
                                   $image_path = storage_path() . "/" . $dir . $employee->employee_profile;
                                   Utility::delete_directory($image_path);
                              }
                         } else {
                              $employee_profile_image_name = "";
                              return response()->json(['status' => 0, 'message' => __($path['msg'])]);
                         }

                         $employee->employee_profile = $employee_profile_image_name;
                    }
                    // Save Employee Profile Picture


                    if ($isProfileEdit == false) {

                         // Employee Earnings and Deductions

                         EmployeeEarningsDetails::where("employees_id", $employee->id)->delete();
                         EmployeeDeductionsDetails::where("employees_id", $employee->id)->delete();

                         $earning_ids = $request->earning_id ?? [];
                         $earning_amount = $request->earning_amount ?? [];
                         $earning_type = $request->earning_type ?? [];
                         $earning_amount_type = $request->earning_amount_type ?? [];

                         $deduction_ids = $request->deduction_id ?? [];
                         $deduction_amount = $request->deduction_amount ?? [];
                         $deduction_type = $request->deduction_type ?? [];
                         $deduction_amount_type = $request->deduction_amount_type ?? [];

                         $earningsData = [];
                         $deductionsData = [];

                         // $deductionList = DeductionOption::with('playslab')->where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();

                         // Earnings Data
                         try {
                              foreach ($earning_ids as $earningId) {
                                   try {
                                        // earning_type  | 0 = Allowances, 1 = Commission, 2 = other payment earnings,

                                        $earningAmountType = 0;
                                        $earningType = 0;
                                        $earningAmount = 0;

                                        if (array_key_exists($earningId, $earning_type)) {
                                             $earningType = $earning_type[$earningId] ?? 0;
                                        }

                                        if (array_key_exists($earningId, $earning_amount)) {
                                             $earningAmount = $earning_amount[$earningId] ?? 0;
                                        }

                                        if (array_key_exists($earningId, $earning_amount_type)) {
                                             $earningAmountType = $earning_amount_type[$earningId] == "percentage" ? 1 : 0;
                                        }
                                   } catch (Exception | Throwable $ex) {
                                   }

                                   if ($earningType == 0) {
                                        $earningData = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->where("id", $earningId)->first();
                                   } else if ($earningType == 1) {
                                        $earningData = Commission_option::where('created_by', '=', Auth::user()->creatorId())->where("id", $earningId)->first();
                                   } else if ($earningType == 2) {
                                        $earningData = OtherPaymentotion::where('created_by', '=', Auth::user()->creatorId())->where("id", $earningId)->first();
                                   }

                                   $name = "";
                                   if ($earningData != null) {
                                        $name = $earningData->name;
                                   }

                                   $earningsData[] = [
                                        // "earningName" => $name,
                                        "employees_id" => $employee->id,
                                        "earning_type" => $earningType,
                                        "earning_id" => $earningId,
                                        "amount" => $earningAmount,
                                        "amount_type" => $earningAmountType,
                                        "status" => 1,
                                        "created_by" => Auth::user()->creatorId()
                                   ];
                              }
                         } catch (Exception | Throwable $ex) {
                         }

                         // Statutory Bonus Earnings
                         $statutory_bonus_status = $request->has("statutory_bonus_status") ? 1 : 0;
                         if ($statutory_bonus_status == 1) {
                              $employee->statutory_bonus = $request->input("statutory_bonus") ?? 0;
                         }

                         $employee->statutory_bonus_status = $statutory_bonus_status;
                         // Statutory Bonus Earnings

                         // Earnings Data


                         // Deductions Data
                         try {
                              foreach ($deduction_ids as $deductionId) {
                                   try {
                                        // earning_type  | 0 = Allowances, 1 = Commission, 2 = other payment earnings,

                                        $deductionAmountType = 0;
                                        $deductionType = 0;
                                        $deductionAmount = 0;

                                        if (array_key_exists($deductionId, $deduction_type)) {
                                             $deductionType = $deduction_type[$deductionId] ?? 0;
                                        }

                                        if (array_key_exists($deductionId, $deduction_amount)) {
                                             $deductionAmount = $deduction_amount[$deductionId] ?? 0;
                                        }

                                        if (array_key_exists($deductionId, $deduction_amount_type)) {
                                             $deductionAmountType = $deduction_amount_type[$deductionId] == "percentage" ? 1 : 0;
                                        }
                                   } catch (Exception | Throwable $ex) {
                                   }

                                   $deductionData = DeductionOption::where('created_by', '=', Auth::user()->creatorId())->where("id", $deductionId)->first();

                                   $name = "";
                                   if ($deductionData != null) {
                                        $name = $deductionData->name;
                                   }

                                   $deductionsData[] = [
                                        // "deductionName" => $name,
                                        "employees_id" => $employee->id,
                                        "deduction_type" => $deductionType,
                                        "deduction_id" => $deductionId,
                                        "amount" => $deductionAmount,
                                        "amount_type" => $deductionAmountType,
                                        "status" => 1,
                                        "created_by" => Auth::user()->creatorId()
                                   ];
                              }
                         } catch (Exception | Throwable $ex) {
                         }


                         // Employee Provident Fund
                         $companyEpf = StatutoryDeductionsEpf::where("created_by", Auth::user()->creatorId())->first();

                         $epf_status = $request->has("epf_status") ? 1 : 0;


                         // dd($employee->id);
                         if ($companyEpf != null) {
                              if ($companyEpf->override_employee_pf == 1 && $epf_status == 1) {
                                   $employee->epf_employee_contribution_rate = $request->input("employee_contribution_rate") ?? 0;
                                   $employee->epf_employer_contribution_rate = $request->input("employer_contribution_rate") ?? 0;
                                   $employee->epf_number = $request->input("epf_number") ?? "";
                              }
                         }
                         $employee->epf_status = $epf_status;
                         // Employee Provident Fund


                         // Tax Deducted at Source (TDS)
                         $tax_deducted_at_source_status = $request->has("tax_deducted_at_source_status") ? 1 : 0;
                         if ($tax_deducted_at_source_status == 1) {
                              $employee->tax_deducted_at_source = $request->input("tax_deducted_at_source") ?? 0;
                         }

                         $employee->tax_deducted_at_source_status = $tax_deducted_at_source_status;
                         // Tax Deducted at Source (TDS)


                         // Deductions Data

                         if (count($earningsData) > 0) {
                              EmployeeEarningsDetails::insert($earningsData);
                         }

                         if (count($deductionsData) > 0) {
                              EmployeeDeductionsDetails::insert($deductionsData);
                         }


                         // Employee Earnings and Deductions

                         // assign_shifts
                         $assign_shifts = $request->input("assign_shifts") ?? [];

                         WorkingShiftEmployees::where("employee_id", $employee->id)->delete();
                         foreach ($assign_shifts as $shiftId) {
                              if ($shiftId > 0) {
                                   WorkingShiftEmployees::updateOrInsert(
                                        [
                                             "working_shifts_id" => $shiftId,
                                             "employee_id" => $employee->id
                                        ],
                                        [
                                             "working_shifts_id" => $shiftId,
                                             "employee_id" => $employee->id
                                        ]
                                   );
                              }
                         }
                         // assign_shifts
                    }



                    // Documents
                    // dd($request->document);
                    if (!empty($request->document)) {
                         foreach ($request->document as $key => $document) {
                              if (!empty($document)) {
                                   $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                                   $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                                   $extension = $request->file('document')[$key]->getClientOriginalExtension();
                                   $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                                   $dir = 'uploads/document/';

                                   $image_path = $dir . $fileNameToStore;
                                   if (\File::exists($image_path)) {
                                        \File::delete($image_path);
                                   }

                                   $path = \App\Models\Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);

                                   // dd($path, $fileNameToStore);

                                   if ($path['flag'] == 1) {
                                        $url = $path['url'];
                                   } else {
                                        return response()->json(['status' => 0, 'message' => __($path['msg'])]);
                                   }

                                   $employee_document = EmployeeDocument::where('employee_id', $employee->id)->where('document_id', $key)->first();
                                   if (!empty($employee_document)) {
                                        if ($employee_document->document_value) {
                                             \File::delete(storage_path('uploads/document/' . $employee_document->document_value));
                                        }
                                        $employee_document->document_value = $fileNameToStore;
                                        $employee_document->save();
                                   } else {
                                        $employee_document_new = new EmployeeDocument();
                                        $employee_document_new->employee_id = $employee->id;
                                        $employee_document_new->document_id = $key;
                                        $employee_document_new->document_value = $fileNameToStore;
                                        $employee_document_new->save();
                                   }
                              }
                         }
                    }
                    // Documents


                    EXPTable::where("employee_id", $employee->id)->delete();
                    EdcutionTable::where("employee_id", $employee->id)->delete();
                    DependentData::where("employee_id", $employee->id)->delete();

                    // Experience
                    if (!empty($request->previous_company_name[0])) {
                         foreach ($request->previous_company_name as $key => $institute2) {
                              $eduData = new EXPTable();
                              $eduData->employee_id = $employee->id;
                              $eduData->previous_company_name = $request->previous_company_name[$key] ?? "";
                              $eduData->previous_job_title = $request->previous_job_title[$key] ?? "";
                              $eduData->previous_from_date = $request->previous_from_date[$key] ?? "";
                              $eduData->previous_to_date = $request->previous_to_date[$key] ?? "";
                              $eduData->previous_company_designation_id = $request->previous_company_designation_id[$key] ?? "";
                              $eduData->experiance_Job_description = $request->experiance_Job_description[$key] ?? "";
                              $eduData->save();

                              // dd($eduData, $eduData->previous_company_designation_id);
                         }
                    }
                    // Experience

                    //eduction
                    if (!empty($request->institute_name[0])) {
                         foreach ($request->institute_name as $key => $institute2) {
                              $eduData = new EdcutionTable();

                              $eduData->employee_id = $employee->id;
                              $eduData->institute_name = $request->institute_name[$key];
                              $eduData->education = $request->education[$key];
                              $eduData->specialization = $request->specialization[$key];
                              $eduData->date_of_completion = $request->date_of_completion[$key];
                              $eduData->notes = $request->notes[$key];
                              $eduData->save();
                         }
                    }
                    //eduction

                    // Dependent
                    foreach ($request->dependent_name as $key => $institute2) {
                         if (!empty($request->dependent_name[$key])) {
                              $eduData = new DependentData();
                              $eduData->employee_id = $employee->id;
                              $eduData->dependent_name = $request->dependent_name[$key];
                              $eduData->dependent_relation = $request->dependent_relation[$key];
                              $eduData->dependent_dob = $request->dependent_dob[$key];
                              $eduData->emergency_phone_no = $request->emergency_phone_no[$key];
                              $eduData->save();
                         }
                    }
                    // Dependent


                    if ($isProfileEdit == false) {
                         if (!empty($request['employee_exit'])) {
                              $Employee = Employee::find($id);
                              if (!empty($Employee)) {
                                   $notification = new NotificationModel();
                                   $notification->user_id = $Employee->users_types_id;
                                   $notification->employee_id = $Employee->id;
                                   $notification->title = "Employee Exit " . $Employee->name;
                                   $notification->details = "Employee Exit " . $Employee->name . " wish you all all the best";
                                   $notification->status = 0;
                                   $notification->date = Carbon::now();
                                   $notification->created_by = $Employee->created_by;
                                   $notification->redirect = 'employee';
                                   $notification->save();
                              }
                              //HR get notification
                              $userHR = User::where('type', 1)->get();
                              foreach ($userHR as $hr) {
                                   if (!empty($hr)) {
                                        $notification = new NotificationModel();
                                        $notification->user_id = $hr->id;
                                        $notification->employee_id = $Employee->id;
                                        $notification->title = "Employee Exit " . $Employee->name;
                                        $notification->details = "Employee Exit " . $Employee->name . " wish you all all the best";
                                        $notification->status = 0;
                                        $notification->date = Carbon::now();
                                        $notification->created_by = $Employee->created_by;
                                        $notification->redirect = 'employee';
                                        $notification->save();
                                   }
                              }
                         }

                         // add leave balance
                         if ($request->has("confirm_probation_period")) {
                              // dd($request->confirm_probation_period == 1 && $confirm_probation_period_old == 0);
                              if ($request->confirm_probation_period == 1 && $confirm_probation_period_old == 0) {
                                   $leaveTypes = LeaveType::where("play_slab_id", $employee->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
                                   foreach ($leaveTypes as $leaveType) {
                                        $addLeave = (float) numberFormat(($leaveType->days / 12), 2);

                                        // add leave balance
                                        $userLeaveBalance = UserLeaveBalance::where([
                                             ["user_id", "=", $employee->id],
                                             ["leave_type_id", "=", $leaveType->id],
                                        ])->first();

                                        if (empty($userLeaveBalance)) {
                                             $userLeaveBalance = new UserLeaveBalance();

                                             $userLeaveBalance->user_id = $employee->id;
                                             $userLeaveBalance->leave_type_id = $leaveType->id;
                                             $userLeaveBalance->pending_leaves = $addLeave;
                                             $userLeaveBalance->created_by = Auth::user()->creatorId();
                                        } else {
                                             $userLeaveBalance->pending_leaves = ($userLeaveBalance->pending_leaves + $addLeave);
                                        }

                                        $userLeaveBalance->save();
                                        // add leave balance

                                        // Add Leave Balance Year
                                        $currentYear = date('Y');
                                        $userLeaveBalanceYear = UserLeaveBalanceYear::where([
                                             ["user_id", "=", $employee->id],
                                             ["leave_type_id", "=", $leaveType->id],
                                             ["leaves_year", "=", $currentYear],
                                        ])->first();

                                        if (empty($userLeaveBalanceYear)) {
                                             $userLeaveBalanceYear = new UserLeaveBalanceYear();

                                             $userLeaveBalanceYear->user_id = $employee->id;
                                             $userLeaveBalanceYear->leave_type_id = $leaveType->id;
                                             $userLeaveBalanceYear->pending_leaves = $addLeave;
                                             $userLeaveBalanceYear->created_by = Auth::user()->creatorId();
                                             $userLeaveBalanceYear->leaves_year = $currentYear;
                                        } else {
                                             $userLeaveBalanceYear->pending_leaves = ($userLeaveBalanceYear->pending_leaves + $addLeave);
                                        }

                                        $userLeaveBalanceYear->save();
                                        // Add Leave Balance
                                   }
                              }
                         }
                         // add leave balance
                    }

                    $employee->save();

                    // Update user data
                    $userUpdateData = [
                         'name' => $employee->name,
                         'email' => $employee->email,
                         "avatar" => $employee->employee_profile
                    ];

                    // Update password only if provided
                    if (!empty($request->password)) {
                         $userUpdateData['password'] = Hash::make($request->password);
                         $userUpdateData['see_password'] = $request->password;
                    }

                    User::where("id", $employee->user_id)->update($userUpdateData);

                    DB::commit();

                    if ($auditEnabled) {
                         try {
                              app(EmployeeDataChangeLogService::class)->recordFromEmployee(
                                   (int) $employee->id,
                                   $auditBefore,
                                   [
                                        'source' => $isProfileEdit ? 'web_ess' : 'web_admin',
                                        'company_id' => (int) Auth::user()->creatorId(),
                                   ]
                              );
                         } catch (Throwable $auditEx) {
                              // Never break employee save
                         }
                    }

                    if ($isProfileEdit == false) {
                         return response()->json(['status' => 1, 'message' => 'Employee successfully updated.', 'redirect' => route('employee.index')]);
                    } else {
                         return response()->json(['status' => 1, 'message' => 'Employee successfully updated.', 'redirect' => route('employeeProfileShow', Crypt::encrypt($employee->id))]);
                    }
               } catch (Exception | Throwable $e) {
                    // dd($e);
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
               }
          } else {
               return response()->json(['status' => 0, 'message' => __('Permission denied.')]);
          }
     }

     public function destroy($id)
     {
          if (Auth::user()->can('Delete Employee')) {
               $employee = Employee::findOrFail($id);
               $user = User::where('id', '=', $employee->user_id)->first();
               $emp_documents = EmployeeDocument::where('employee_id', $employee->id)->get();
               $employee->delete();
               $user->delete();

               $dir = storage_path('uploads/document/');
               foreach ($emp_documents as $emp_document) {
                    $emp_document->delete();
                    \File::delete(storage_path('uploads/document/' . $emp_document->document_value));
                    if (!empty($emp_document->document_value)) {
                         // unlink($dir . $emp_document->document_value);
                    }
               }
               return redirect()->route('employee.index')->with('success', 'Employee successfully deleted.');
          } else {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show($id)
     {
          try {
               $empId = \Illuminate\Support\Facades\Crypt::decrypt($id);
          } catch (\RuntimeException $e) {
               return redirect()->back()->with('error', __('Employee not avaliable'));
          }

          $employee = Employee::find($empId);
          if ($employee == null) {
               return redirect()->back()->with('error', __('Employee not found.'));
          }

          // Employees can always view their own profile; others need Show Employee permission
          $isOwnProfile = Auth::user()->type == 'employee' && (int) $employee->user_id === (int) Auth::id();
          if (!Auth::user()->can('Show Employee') && !$isOwnProfile) {
               return redirect()->back()->with('error', __('Permission denied.'));
          }

          $documents = Document::where('created_by', Auth::user()->creatorId())->get();
          $branches = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
          $departments = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
          $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

          $userdata = User::find($employee->users_types_id);

          $employeesId = Auth::user()->employeeIdFormat($employee->employee_id);
          $empId = Crypt::decrypt($id);

          //EXPTableData table
          $EXPTableData = EXPTable::where('employee_id', $empId)->get();

          //EducationData table
          $EducationData = EdcutionTable::where('employee_id', $empId)->get();

          //DependentData table
          $DependentData = DependentData::where('employee_id', $empId)->get();

          //payslip employee wise

          $employees = Employee::where(['created_by' => Auth::user()->creatorId(),])->first();

          $month = [
               '01' => 'JAN',
               '02' => 'FEB',
               '03' => 'MAR',
               '04' => 'APR',
               '05' => 'MAY',
               '06' => 'JUN',
               '07' => 'JUL',
               '08' => 'AUG',
               '09' => 'SEP',
               '10' => 'OCT',
               '11' => 'NOV',
               '12' => 'DEC',
          ];

          $year = [

               '2022' => '2022',
               '2023' => '2023',
               '2024' => '2024',
               '2025' => '2025',
               '2026' => '2026',
               '2027' => '2027',
               '2028' => '2028',
               '2029' => '2029',
               '2030' => '2030',
               '2031' => '2031',
               '2032' => '2032',
          ];

          $yearsCount = 4;

          $payslipDetail = getEmployeeSalaryStructure($empId, date("Y") . '-' . date("m"));

          // Salary Structure End

          return view('employee.show', compact('userdata', 'employees', 'month', 'year', 'DependentData', 'EXPTableData', 'EducationData', 'employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'yearsCount', 'payslipDetail'));
     }

     function employeeNumber()
     {
          $latest = Employee::where('created_by', '=', Auth::user()->creatorId())->latest('id')->first();
          if (!$latest) {
               $latest = Employee::latest('id')->first();
          }

          return $latest->id + 1;
     }

     public function export(Request $request)
     {
          $statusFilter = $request->input('status_filter', 'all');

          // Handle both array and string formats
          if (is_array($statusFilter)) {
               $statusFilter = $statusFilter[0] ?? 'all';
          }

          $name = 'employee_' . date('Y-m-d i:h:s');
          $data = Excel::download(new EmployeesExport($statusFilter), $name . '.xlsx');

          return $data;
     }

     public function importFile()
     {
          return view('employee.import');
     }

     public function import_original(Request $request)
     {
          $rules = [
               'file' => 'required|mimes:csv,txt',
          ];

          $validator = Validator::make($request->all(), $rules);

          if ($validator->fails()) {
               $messages = $validator->getMessageBag();

               return redirect()->back()->with('error', $messages->first());
          }

          $employees = (new EmployeesImport())->toArray(request()->file('file'))[0];
          $totalCustomer = count($employees) - 1;
          $errorArray = [];

          for ($i = 1; $i <= count($employees) - 1; $i++) {
               $employee = $employees[$i];
               $employeeByEmail = Employee::where('email', $employee[5])->first();
               $userByEmail = User::where('email', $employee[5])->first();

               if (!empty($employeeByEmail) && !empty($userByEmail)) {
                    $employeeData = $employeeByEmail;
               } else {
                    $user = new User();
                    $user->name = $employee[0];
                    $user->email = $employee[5];
                    $user->password = Hash::make($employee[6]);
                    $user->type = 'employee';
                    $user->lang = 'en';
                    $user->created_by = Auth::user()->creatorId();
                    $user->save();
                    $user->assignRole('Employee');

                    $employeeData = new Employee();
                    $employeeData->employee_id = $this->employeeNumber();
                    $employeeData->user_id = $user->id;
               }


               $employeeData->name = $employee[0];
               $employeeData->dob = Carbon::parse($employee[1])->format('Y-m-d');
               $employeeData->gender = $employee[2];
               $employeeData->phone = $employee[3];
               $employeeData->address = $employee[4];
               $employeeData->email = $employee[5];
               $employeeData->password = Hash::make($employee[6]);
               $employeeData->employee_id = $this->employeeNumber();
               $employeeData->branch_id = $employee[8];
               $employeeData->department_id = $employee[9];
               $employeeData->designation_id = $employee[10];
               $employeeData->company_doj = $employee[11];
               $employeeData->account_holder_name = $employee[12];
               $employeeData->account_number = $employee[13];
               $employeeData->bank_name = $employee[14];
               $employeeData->bank_identifier_code = $employee[15];
               $employeeData->branch_location = $employee[16];
               $employeeData->tax_payer_id = $employee[17];
               $employeeData->created_by = Auth::user()->creatorId();

               $employeeData->employe_status = $employee[19];
               $employeeData->employe_types = $employee[20];
               $employeeData->employee_experience = $employee[21];
               $employeeData->date_of_exit = $employee[22];

               $employeeData->Job_description = $employee[23];
               $employeeData->previous_company_name = $employee[24];
               $employeeData->previous_job_title = $employee[25];
               $employeeData->previous_from_date = $employee[26];
               $employeeData->previous_to_date = $employee[27];
               $employeeData->previous_company_designation_id = $employee[28];
               $employeeData->experiance_Job_description = $employee[29];
               $employeeData->institute_name = $employee[30];
               $employeeData->education = $employee[31];

               $employeeData->specialization = $employee[32];
               $employeeData->date_of_completion = $employee[33];
               $employeeData->notes = $employee[34];
               $employeeData->dependent_name = $employee[35];

               $employeeData->dependent_relation = $employee[36];
               $employeeData->dependent_dob = $employee[37];

               if (empty($employeeData)) {
                    $errorArray[] = $employeeData;
               } else {
                    $employeeData->save();
               }
          }

          $errorRecord = [];

          if (empty($errorArray)) {
               $data['status'] = 'success';
               $data['msg'] = __('Record successfully imported');
          } else {
               $data['status'] = 'error';
               $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


               foreach ($errorArray as $errorData) {

                    $errorRecord[] = implode(',', $errorData);
               }

               Session::put('errorArray', $errorRecord);
          }

          return redirect()->back()->with($data['status'], $data['msg']);
     }

     public function json(Request $request)
     {
          $designations = Designation::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();

          return response()->json($designations);
     }

     public function getDesignationDescription(Request $request)
     {
          $designation = Designation::where('id', $request->designation_id)->first();

          if ($designation) {
               return response()->json([
                    'description' => $designation->description ?? ''
               ]);
          }

          return response()->json(['description' => '']);
     }

     public function Branch_department_json(Request $request)
     {
          $designations = Department::where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
          return response()->json($designations);
     }

     public function profile(Request $request)
     {
          if (Auth::user()->can('Manage Employee Profile')) {
               $employees = Employee::where('created_by', Auth::user()->creatorId());
               if (!empty($request->branch)) {
                    $employees->where('branch_id', $request->branch);
               }
               if (!empty($request->department)) {
                    $employees->where('department_id', $request->department);
               }
               if (!empty($request->designation)) {
                    $employees->where('designation_id', $request->designation);
               }
               $employees = $employees->get();

               $brances = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $brances->prepend('All', '');

               $departments = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $departments->prepend('All', '');

               $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $designations->prepend('All', '');

               // $employees = Employee::where(['created_by' => Auth::user()->creatorId(),])->first();

               $month = [
                    '01' => 'JAN',
                    '02' => 'FEB',
                    '03' => 'MAR',
                    '04' => 'APR',
                    '05' => 'MAY',
                    '06' => 'JUN',
                    '07' => 'JUL',
                    '08' => 'AUG',
                    '09' => 'SEP',
                    '10' => 'OCT',
                    '11' => 'NOV',
                    '12' => 'DEC',
               ];

               $year = [

                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                    '2028' => '2028',
                    '2029' => '2029',
                    '2030' => '2030',
                    '2031' => '2031',
                    '2032' => '2032',
               ];


               return view('employee.profile', compact('month', 'year', 'employees', 'departments', 'designations', 'brances'));
          } else {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function profileShow($id)
     {
          if (Auth::user()->can('Show Employee Profile')) {
               $empId = Crypt::decrypt($id);
               $documents = Document::where('created_by', Auth::user()->creatorId())->get();
               $branches = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $departments = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               $employee = Employee::find($empId);
               if ($employee == null) {
                    $employee = Employee::where('user_id', $empId)->first();
               }
               //EXPTableData table
               $EXPTableData = EXPTable::where('employee_id', $empId)->get();
               //EducationData table
               $EducationData = EdcutionTable::where('employee_id', $empId)->get();
               //DependentData table
               $DependentData = DependentData::where('employee_id', $empId)->get();
               $employeesId = Auth::user()->employeeIdFormat($employee->employee_id);
               $employees = Employee::where(['created_by' => Auth::user()->creatorId(),])->first();
               $userdata = User::find($employee->users_types_id);
               $month = [
                    '01' => 'JAN',
                    '02' => 'FEB',
                    '03' => 'MAR',
                    '04' => 'APR',
                    '05' => 'MAY',
                    '06' => 'JUN',
                    '07' => 'JUL',
                    '08' => 'AUG',
                    '09' => 'SEP',
                    '10' => 'OCT',
                    '11' => 'NOV',
                    '12' => 'DEC',
               ];

               $year = [
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                    '2028' => '2028',
                    '2029' => '2029',
                    '2030' => '2030',
                    '2031' => '2031',
                    '2032' => '2032',
               ];
               return view('employee.show', compact('userdata', 'employees', 'month', 'year', 'EXPTableData', 'EducationData', 'DependentData', 'employee', 'employeesId', 'branches', 'departments', 'designations', 'documents'));
          } else {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function lastLogin()
     {
          $users = User::where('created_by', Auth::user()->creatorId())->get();
          return view('employee.lastLogin', compact('users'));
     }

     public function employeeJson(Request $request)
     {
          $employees = Employee::where('branch_id', $request->branch)->get()->pluck('name', 'id')->toArray();
          return response()->json($employees);
     }

     public function joiningletterPdf($id)
     {
          $users = Auth::user();
          $currantLang = $users->currentLanguage();
          $joiningletter = JoiningLetter::where('lang', $currantLang)->where('created_by', Auth::user()->creatorId())->first();
          $date = date('Y-m-d');
          $employees = Employee::where('id', $id)->where('created_by', Auth::user()->creatorId())->first();
          $settings = \App\Models\Utility::settings();
          $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
          $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
          $obj = [
               'date' => Auth::user()->dateFormat($date),
               'app_name' => env('APP_NAME'),
               'employee_name' => $employees->name,
               'address' => !empty($employees->address) ? $employees->address : '',
               'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
               'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
               'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
               'start_time' => !empty($settings['company_start_time']) ? $settings['company_start_time'] : '',
               'end_time' => !empty($settings['company_end_time']) ? $settings['company_end_time'] : '',
               'total_hours' => $result,
          ];
          $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
          // try {
          //      DB::beginTransaction();
          //      $data = new EmployeeJoininglater();
          //      $data->employee_id  = $id;
          //      $data->content      = $joiningletter->content;
          //      $data->type         =  'PDF';
          //      $data->created_by   = Auth::user()->creatorId();
          //      $data->save();
          //      DB::commit();
          // } catch (Exception $e) {
          //      DB::rollBack();
          //      return showExceptionMessage($e);
          //      return redirect()->back()->with('error',showExceptionMessage($e));
          // } catch (Throwable $e) {
          //      DB::rollBack();
          //      return showExceptionMessage($e);
          //      return redirect()->back()->with('error',showExceptionMessage($e));
          // }
          return view('employee.template.joiningletterpdf', compact('joiningletter', 'employees'));
     }

     public function joiningletterDoc($id)
     {
          $users = Auth::user();
          $currantLang = $users->currentLanguage();
          $joiningletter = JoiningLetter::where('lang', $currantLang)->where('created_by', Auth::user()->creatorId())->first();
          $date = date('Y-m-d');
          $employees = Employee::where('id', $id)->where('created_by', Auth::user()->creatorId())->first();
          $settings = \App\Models\Utility::settings();
          $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
          $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
          $obj = [
               'date' => Auth::user()->dateFormat($date),
               'app_name' => env('APP_NAME'),
               'employee_name' => $employees->name,
               'address' => !empty($employees->address) ? $employees->address : '',
               'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
               'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
               'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
               'start_time' => !empty($settings['company_start_time']) ? $settings['company_start_time'] : '',
               'end_time' => !empty($settings['company_end_time']) ? $settings['company_end_time'] : '',
               'total_hours' => $result,
          ];
          $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
          // try {
          //      DB::beginTransaction();
          //      $data = new EmployeeJoininglater();
          //      $data->employee_id  = $id;
          //      $data->content      = $joiningletter->content;
          //      $data->type        =  'DOC';
          //      $data->created_by   = Auth::user()->creatorId();
          //      $data->save();
          //      DB::commit();
          // } catch (\Exception $e) {
          //      DB::rollBack();
          //      $error_msg =  error_msg();
          //      return ApiResponse($error_msg);
          // } catch (\Throwable $e) {
          //      DB::rollBack();
          //      $error_msg =  error_msg();
          //      return ApiResponse($error_msg);
          // }
          return view('employee.template.joiningletterdocx', compact('joiningletter', 'employees'));
     }

     public function ExpCertificatePdf($id)
     {
          $users = Auth::user();
          $currantLang = $users->currentLanguage();
          $termination = Termination::where('employee_id', $id)->where('created_by', Auth::user()->creatorId())->first();
          $experience_certificate = ExperienceCertificate::where('lang', $currantLang)->where('created_by', Auth::user()->creatorId())->first();
          $date = date('Y-m-d');
          $employees = Employee::where('id', $id)->where('created_by', Auth::user()->creatorId())->first();
          // dd($employees->salaryType->name);
          $settings = \App\Models\Utility::settings();
          $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
          $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
          $date1 = date_create($employees->company_doj);
          $date2 = date_create($employees->termination_date);
          $diff = date_diff($date1, $date2);
          $duration = $diff->format("%a days");
          if (!empty($termination->termination_date)) {
               $obj = [
                    'date' => Auth::user()->dateFormat($date),
                    'app_name' => env('APP_NAME'),
                    'employee_name' => $employees->name,
                    'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                    'duration' => $duration,
                    'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
               ];
          } else {
               return redirect()->back()->with('error', __('Termination date is required.'));
          }
          $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
          return view('employee.template.ExpCertificatepdf', compact('experience_certificate', 'employees'));
     }

     public function ExpCertificateDoc($id)
     {
          $currantLang = \Cookie::get('LANGUAGE');
          if (!isset($currantLang)) {
               $currantLang = 'en';
          }
          $termination = Termination::where('employee_id', $id)->where('created_by', Auth::user()->creatorId())->first();
          $experience_certificate = ExperienceCertificate::where('lang', $currantLang)->where('created_by', Auth::user()->creatorId())->first();
          $date = date('Y-m-d');
          $employees = Employee::where('id', $id)->where('created_by', Auth::user()->creatorId())->first();
          ;
          $settings = \App\Models\Utility::settings();
          $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
          $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
          $date1 = date_create($employees->company_doj);
          $date2 = date_create($employees->termination_date);
          $diff = date_diff($date1, $date2);
          $duration = $diff->format("%a days");
          if (!empty($termination->termination_date)) {
               $obj = [
                    'date' => Auth::user()->dateFormat($date),
                    'app_name' => env('APP_NAME'),
                    'employee_name' => $employees->name,
                    'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                    'duration' => $duration,
                    'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
               ];
          } else {
               return redirect()->back()->with('error', __('Termination date is required.'));
          }
          $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
          return view('employee.template.ExpCertificatedocx', compact('experience_certificate', 'employees'));
     }

     public function NocPdf($id)
     {
          $users = Auth::user();

          $currantLang = $users->currentLanguage();
          $noc_certificate = NOC::where('lang', $currantLang)->where('created_by', Auth::user()->creatorId())->first();
          $date = date('Y-m-d');
          $employees = Employee::where('id', $id)->where('created_by', Auth::user()->creatorId())->first();
          $settings = \App\Models\Utility::settings();
          $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
          $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
          $obj = [
               'date' => Auth::user()->dateFormat($date),
               'employee_name' => $employees->name,
               'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
               'app_name' => env('APP_NAME'),
          ];
          $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
          return view('employee.template.Nocpdf', compact('noc_certificate', 'employees'));
     }

     public function NocDoc($id)
     {
          $users = Auth::user();
          $currantLang = $users->currentLanguage();
          $noc_certificate = NOC::where('lang', $currantLang)->where('created_by', Auth::user()->creatorId())->first();
          $date = date('Y-m-d');
          $employees = Employee::where('id', $id)->where('created_by', Auth::user()->creatorId())->first();
          $settings = \App\Models\Utility::settings();
          $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
          $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
          $obj = [
               'date' => Auth::user()->dateFormat($date),
               'employee_name' => $employees->name,
               'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
               'app_name' => env('APP_NAME'),
          ];
          $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
          return view('employee.template.Nocdocx', compact('noc_certificate', 'employees'));
     }

     public function downloadImportEmployeeSampleFile(Request $request)
     {
          // Set headers for file download
          $headers = [
               'Content-Type' => 'text/csv',
               'Content-Disposition' => 'attachment; filename="example.csv"',
          ];

          $csvContent = '';

          // $PaySlabTypes = PayslipType::where('created_by', '=', Auth::user()->creatorId())->orderBy("id", "Asc")->get();

          $leaveTypes = LeaveType::select("*", "title as title_with_pay_slab_name")->where('created_by', '=', Auth::user()->creatorId())->orderBy("play_slab_id", "Asc")->get()->pluck("title_with_pay_slab_name")->toArray();

          try {
               // Define CSV data
               $data = array(
                    array_merge([
                         "employee id",
                         "name",
                         "dob",
                         "gender",
                         "phone",
                         "address",
                         "email",
                         "password",
                         "branch",
                         "department",
                         "designation",
                         "employee status (active | deceased | resigned |terminated)",
                         "employee types(on contract | permanent | temporary | trainee)",
                         "type of staff (office staff | field staff)",
                         "pay slab",

                         "CTC Annual",
                         "Basic(%)", // CTC Percentage
                         "salary (Monthly)",

                         "company doj",
                         "probation period date",
                         "account holder name",
                         "account number",
                         "bank name",
                         "bank identifier code",
                         "branch location",
                         "tax payer id",

                    ], $leaveTypes),
                    [
                         "Emp-1",
                         "John Doe",
                         "01-01-1995",
                         "Male",
                         1234567890,
                         "Blanditiis sunt cul",
                         "johndoe@mailinator.com",
                         1234,
                         "Branch A",
                         "Marketing",
                         "Social Media",
                         "active",
                         "permanent",
                         "office staff",
                         "Support Staff Test",

                         180000.00,
                         100,
                         50000, // salary

                         Carbon::now()->format("d-m-Y"),
                         Carbon::now()->format("d-m-Y"),
                         "John Doe",
                         400,
                         "Brady Workman",
                         123456,
                         "india",
                         123456,
                    ]
               );

               // Generate CSV content

               foreach ($data as $row) {
                    $csvContent .= implode(',', $row) . "\n";
               }
          } catch (Exception | Throwable $ex) {
          }

          // Return CSV as a download response
          return Response::make($csvContent, 200, $headers);
     }

     public function import(Request $request)
     {
          return $this->importEmployee($request);

          try {
               $rules = [
                    'file' => 'required|mimes:csv,txt',
               ];

               $validator = Validator::make($request->all(), $rules);

               if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $employees = (new EmployeesImport())->toArray(request()->file('file'))[0];
               $totalCustomer = count($employees) - 1;
               $errorArray = [];

               for ($i = 1; $i <= count($employees) - 1; $i++) {
                    $employee = $employees[$i];
                    $employeeByEmail = Employee::where('email', $employee[5])->first();
                    $userByEmail = User::where('email', $employee[5])->first();

                    $user = [];
                    $employeeData = [];

                    DB::beginTransaction();

                    if (!empty($userByEmail)) {
                         $user = $userByEmail;
                    } else {
                         $user = new User();
                         $user->name = $employee[0];
                         $user->email = $employee[5];
                         $user->password = Hash::make($employee[6]);
                         $user->type = 'employee';
                         $user->lang = 'en';
                         $user->email_verified_at = Carbon::now();
                         $user->created_by = Auth::user()->creatorId();
                         $user->save();
                         $user->assignRole('Employee');
                    }

                    if (!empty($employeeByEmail)) // check employee exists
                    {
                         $employeeData = $employeeByEmail;
                    } else {
                         $employeeData = new Employee();
                    }

                    $employeeData->employee_id = $this->employeeNumber();
                    $employeeData->user_id = $user->id;

                    $employeeData->name = $employee[0];
                    $employeeData->dob = Carbon::parse($employee[1])->format('Y-m-d');
                    $employeeData->gender = $employee[2];
                    $employeeData->phone = $employee[3];
                    $employeeData->address = $employee[4];
                    $employeeData->email = $employee[5];

                    $employeeData->password = Hash::make($employee[6]);

                    $branch_id = Branch::where('name', $employee[7])->where('created_by', Auth::user()->creatorId())->first();

                    $department_id = Department::where('name', $employee[8])->where('created_by', Auth::user()->creatorId())->first();

                    $designation_id = Designation::where('name', $employee[9])->where('created_by', Auth::user()->creatorId())->first();

                    $play_slab_id = PayslipType::where('name', $employee[13])->where('created_by', Auth::user()->creatorId())->first();
                    // dd($branch_id,$department_id,$designation_id,$play_slab_id);

                    // employe_status
                    if ($employee[10] == "active") {
                         $employe_status = "Active";
                    } elseif ($employee[10] == "deceased") {
                         $employe_status = "Deceased";
                    } elseif ($employee[10] == "resigned") {
                         $employe_status = "Resigned";
                    } else {
                         $employe_status = "Terminated";
                    }

                    if ($employee[11] == "on contract") {
                         $employe_types = "On Contract";
                    } elseif ($employee[11] == "permanent") {
                         $employe_types = "Permanent";
                    } elseif ($employee[11] == "temporary") {
                         $employe_types = "Temporary";
                    } else {
                         $employe_types = "Trainee";
                    }

                    if ($employee[12] == "office staff") {
                         $office_staff = 1;
                    } else {
                         $office_staff = 2;
                    }

                    $employeeData->branch_id = $branch_id->id ?? 0;
                    $employeeData->department_id = $department_id->id ?? 0;
                    $employeeData->designation_id = $designation_id->id ?? 0;
                    $employeeData->employe_status = $employe_status;
                    $employeeData->employe_types = $employe_types;
                    $employeeData->office_staff = $office_staff;
                    $employeeData->play_slab_id = $play_slab_id->id;
                    // $employeeData->branch_id           = $employee[7];
                    // $employeeData->department_id       = $employee[8];
                    // $employeeData->designation_id      = $employee[9];
                    // $employeeData->employe_status      = $employee[10];
                    // $employeeData->employe_types      = $employee[11];
                    // $employeeData->office_staff      = $employee[12];
                    // $employeeData->play_slab_id      = $employee[13];


                    $employeeData->salary = $employee[14];
                    $employeeData->company_doj = Carbon::parse($employee[15])->format('Y-m-d');
                    $employeeData->probation_period_date = Carbon::parse($employee[16])->format('Y-m-d');
                    $employeeData->account_holder_name = $employee[17];
                    $employeeData->account_number = $employee[18];
                    $employeeData->bank_name = $employee[19];
                    $employeeData->bank_identifier_code = $employee[20];
                    $employeeData->branch_location = $employee[21];
                    $employeeData->tax_payer_id = $employee[22];
                    $employeeData->created_by = Auth::user()->creatorId();

                    if (empty($employeeData)) {
                         $errorArray[] = $employeeData;
                    } else {
                         $employeeData->save();
                    }

                    DB::commit();
               }

               $errorRecord = [];

               if (empty($errorArray)) {
                    $data['status'] = 'success';
                    $data['msg'] = __('Record successfully imported');
               } else {
                    $data['status'] = 'error';
                    $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');

                    foreach ($errorArray as $errorData) {
                         $errorRecord[] = implode(',', $errorData);
                    }

                    Session::put('errorArray', $errorRecord);
               }

               return redirect()->back()->with($data['status'], $data['msg']);
          } catch (Exception $e) {
               dd($e);
               return redirect()->back()->with('error', __("Something is wrong"));
          } catch (Throwable $e) {
               return redirect()->back()->with('error', __("Something is wrong"));
          }
     }

     public function importEmployee(Request $request)
     {

          try {
               $rules = [
                    'file' => 'required|mimes:csv,txt',
               ];

               $validator = Validator::make($request->all(), $rules);

               if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }

               $employees = (new EmployeesImport())->toArray(request()->file('file'))[0];

               $totalCustomer = count($employees) - 1;
               $companyUser = User::find(Auth::user()->creatorId());
               $max_employees = $companyUser->getMaxEmployees();
               $current_employees = $companyUser->countEmployees();

               if ($max_employees != -1 && ($current_employees + $totalCustomer) > $max_employees) {
                    $remaining_seats = max(0, $max_employees - $current_employees);
                    return redirect()->back()->with('error', __('Import rejected: Your plan has ') . $remaining_seats . __(' employee seats remaining, but the file contains ') . $totalCustomer . __(' records. Please upgrade your plan or reduce the import list.'));
               }

               $errorArray = [];

               $columns = [
                    "employee_id" => 0,
                    "name" => 0,
                    "dob" => 0,
                    "gender" => 0,
                    "phone" => 0,
                    "address" => 0,
                    "email" => 0,
                    "password" => 0,
                    "branch" => 0,
                    "department" => 0,
                    "designation" => 0,
                    "employee_status" => 0,
                    "employee_types" => 0,
                    "type_of_staff" => 0,
                    "pay_slab" => 0,
                    "ctc_annual" => 0,
                    "ctc_percentage" => 0,
                    "salary" => 0,
                    "company_doj" => 0,
                    "probation_period_date" => 0,
                    "account_holder_name" => 0,
                    "account_number" => 0,
                    "bank_name" => 0,
                    "bank_identifier_code" => 0,
                    "branch_location" => 0,
                    "tax_payer_id" => 0,
               ];

               // sequence columns indexes
               $sequenceNo = 0;
               foreach ($columns as $key => $value) {
                    $columns[$key] = $sequenceNo;
                    $sequenceNo++;
               }

               $columns = (object) $columns;
               // sequence columns indexes

               // define required columns
               $requiredColumns = [
                    $columns->employee_id,
                    $columns->name,
                    $columns->dob,
                    $columns->gender,
                    $columns->phone,
                    $columns->address,
                    $columns->email,
                    $columns->password,
                    $columns->branch,
                    $columns->department,
                    $columns->designation,
                    $columns->employee_status,
                    $columns->employee_types,
                    $columns->type_of_staff,
                    $columns->pay_slab,
                    $columns->salary,
                    $columns->company_doj,
                    $columns->probation_period_date,
                    $columns->account_holder_name,
                    $columns->account_number,
                    $columns->bank_name,
                    $columns->bank_identifier_code,
                    $columns->branch_location,
                    $columns->tax_payer_id,
               ];


               // check invalid rows

               for ($i = 1; $i <= count($employees) - 1; $i++) {
                    DB::beginTransaction();
                    try {
                         $employee = $employees[$i];

                         $employeeByEmail = Employee::where('email', $employee[$columns->email])->first();
                         $userByEmail = User::where('email', $employee[$columns->email])->first();

                         $user = [];
                         $employeeData = [];

                         // generate Password
                         $firstThreeChars = substr(str_replace(' ', '', $employee[$columns->name]), 0, 3);
                         $lastFourChars = substr(str_replace(' ', '', $employee[$columns->phone]), max(0, strlen(str_replace(' ', '', $employee[$columns->phone])) - 4));
                         $generatedPassword = $firstThreeChars . $lastFourChars;
                         // generate Password

                         $employeePassword = "";
                         if (!empty(trim($employee[$columns->password]))) {
                              $employeePassword = $employee[$columns->password];
                         } else {
                              $employeePassword = $generatedPassword;
                         }

                         // dd($employeePassword);

                         if (!empty($userByEmail)) {
                              $user = $userByEmail;

                              $user->name = $employee[$columns->name];
                              $user->email = $employee[$columns->email];
                              $user->password = Hash::make($employeePassword);
                              $user->save();
                         } else {
                              $user = new User();
                              $user->name = $employee[$columns->name];
                              $user->email = $employee[$columns->email];
                              // $user->password = Hash::make($employee[$columns->password]);
                              $user->password = Hash::make($employeePassword);
                              $user->type = 'employee';
                              $user->lang = 'en';
                              $user->email_verified_at = Carbon::now();
                              $user->created_by = Auth::user()->creatorId();
                              $user->save();
                              $user->assignRole('Employee');
                         }

                         if (!empty($employeeByEmail)) // check employee exists
                         {
                              $employeeData = $employeeByEmail;
                         } else {
                              $employeeData = new Employee();
                         }

                         $employeeData->employee_id = ($employee[$columns->employee_id] ?? $this->employeeNumber());
                         $employeeData->user_id = $user->id;

                         $employeeData->name = $employee[$columns->name];
                         try {
                              $employeeData->dob = Carbon::parse($employee[$columns->dob])->format('Y-m-d');
                         } catch (Exception | Throwable $ex) {
                         }
                         $employeeData->gender = $employee[$columns->gender];
                         $employeeData->phone = $employee[$columns->phone];
                         $employeeData->address = $employee[$columns->address];
                         $employeeData->email = $employee[$columns->email];

                         $employeeData->password = Hash::make($employeePassword);
                         // dd($employeePassword)

                         $branch_id = Branch::where('name', $employee[$columns->branch])->where('created_by', Auth::user()->creatorId())->first();
                         // dd(
                         //      $employee[$columns->branch],
                         //      $branch_id,
                         // );

                         $department_id = Department::where('name', $employee[$columns->department])->where('created_by', Auth::user()->creatorId())->first();
                         $designation_id = Designation::where('name', $employee[$columns->designation])->where('created_by', Auth::user()->creatorId())->first();
                         $play_slab_data = PayslipType::where('name', $employee[$columns->pay_slab])->where('created_by', Auth::user()->creatorId())->first();

                         $paySlabId = 0;
                         if ($play_slab_data != null) {
                              $paySlabId = $play_slab_data->id;
                         }

                         // employe_status
                         if (strtolower($employee[$columns->employee_status]) == "active") {
                              $employe_status = "Active";
                         } elseif (strtolower($employee[$columns->employee_status]) == "deceased") {
                              $employe_status = "Deceased";
                         } elseif (strtolower($employee[$columns->employee_status]) == "resigned") {
                              $employe_status = "Resigned";
                         } else {
                              $employe_status = "Terminated";
                         }

                         if (strtolower($employee[$columns->employee_types]) == "on contract") {
                              $employe_types = "On Contract";
                         } elseif (strtolower($employee[$columns->employee_types]) == "permanent") {
                              $employe_types = "Permanent";
                         } elseif (strtolower($employee[$columns->employee_types]) == "temporary") {
                              $employe_types = "Temporary";
                         } else {
                              $employe_types = "Trainee";
                         }

                         if ($employee[$columns->type_of_staff] == "office staff") {
                              $office_staff = 1;
                         } else {
                              $office_staff = 2;
                         }

                         $salary = ($employee[$columns->salary] ?? 0);

                         $ctc_annual = $employee[$columns->ctc_annual] ?? 0;
                         $ctc_percentage = $employee[$columns->ctc_percentage] ?? 0;

                         if ($ctc_annual != 0 && !empty($ctc_annual) && !empty($ctc_percentage)) {
                              // $salary = (($ctc_annual / 12) * $ctc_percentage / 100);
                         }

                         $employeeData->branch_id = $branch_id->id ?? 0;
                         $employeeData->department_id = $department_id->id ?? 0;
                         $employeeData->designation_id = $designation_id->id ?? 0;
                         $employeeData->employe_status = $employe_status;
                         $employeeData->employe_types = $employe_types;
                         $employeeData->office_staff = $office_staff;
                         $employeeData->play_slab_id = $paySlabId;

                         $employeeData->ctc_annual = $ctc_annual;
                         $employeeData->ctc_percentage = $ctc_percentage;
                         $employeeData->salary = $salary;

                         $employeeData->company_doj = Carbon::parse($employee[$columns->company_doj])->format('Y-m-d');
                         $employeeData->probation_period_date = Carbon::parse($employee[$columns->probation_period_date])->format('Y-m-d');
                         $employeeData->account_holder_name = ($employee[$columns->account_holder_name] ?? "");
                         $employeeData->account_number = ($employee[$columns->account_number] ?? "");
                         $employeeData->bank_name = ($employee[$columns->bank_name] ?? "");
                         $employeeData->bank_identifier_code = ($employee[$columns->bank_identifier_code] ?? "");
                         $employeeData->branch_location = ($employee[$columns->branch_location] ?? "");
                         $employeeData->tax_payer_id = ($employee[$columns->tax_payer_id] ?? "");
                         $employeeData->created_by = Auth::user()->creatorId();
                         $employeeData->confirm_probation_period = 1;

                         if (empty($employeeData)) {
                              $errorArray[] = $employeeData;
                         } else {
                              $employeeData->save();
                         }

                         // Add Leave Balance

                         $leaveBalanceData = [];
                         $leaveBalanceData = array_values(array_slice($employee, ($columns->tax_payer_id + 1), count($employee) + 1, true));
                         $leaveTypes = LeaveType::where('created_by', '=', Auth::user()->creatorId())->orderBy("play_slab_id", "Asc")->get();

                         // $paySlabTypes = PayslipType::where('created_by', '=', Auth::user()->creatorId())->orderBy("id", "Asc")->get();

                         $incrementValue = 0;

                         // $addedLeaveTypeIds = [];
                         $leaveBalanceAdded = [];
                         if ($paySlabId >= 1) {
                              foreach ($leaveTypes as $leaveTypeRow) {
                                   if ($leaveTypeRow->play_slab_id == $paySlabId)
                                   // if ($leaveTypeRow->play_slab_id == $paySlabId && !in_array($leaveTypeRow->id, $addedLeaveTypeIds))
                                   {
                                        // $addedLeaveTypeIds[] = $leaveTypeRow->id;

                                        $leaveDays = ($leaveBalanceData[$incrementValue] ?? 0);
                                        if ($leaveDays > 0) {
                                             // add leave balance
                                             $userLeaveBalance = UserLeaveBalance::where([
                                                  ["user_id", "=", $employeeData->id],
                                                  ["leave_type_id", "=", $leaveTypeRow->id],
                                             ])->first();

                                             if (empty($userLeaveBalance)) {
                                                  $userLeaveBalance = new UserLeaveBalance();

                                                  $userLeaveBalance->user_id = $employeeData->id;
                                                  $userLeaveBalance->leave_type_id = $leaveTypeRow->id;
                                                  $userLeaveBalance->pending_leaves = $leaveDays;
                                                  $userLeaveBalance->created_by = Auth::user()->creatorId();
                                             } else {
                                                  $userLeaveBalance->pending_leaves = ($userLeaveBalance->pending_leaves + $leaveDays);
                                             }

                                             $userLeaveBalance->save();
                                             // add leave balance

                                             $leaveBalanceAdded[] = $userLeaveBalance;

                                             // Add Leave Balance Year
                                             $currentYear = date('Y');
                                             $userLeaveBalanceYear = UserLeaveBalanceYear::where([
                                                  ["user_id", "=", $employeeData->id],
                                                  ["leave_type_id", "=", $leaveTypeRow->id],
                                                  ["leaves_year", "=", $currentYear],
                                             ])->first();

                                             if (empty($userLeaveBalanceYear)) {
                                                  $userLeaveBalanceYear = new UserLeaveBalanceYear();

                                                  $userLeaveBalanceYear->user_id = $employeeData->id;
                                                  $userLeaveBalanceYear->leave_type_id = $leaveTypeRow->id;
                                                  $userLeaveBalanceYear->pending_leaves = $leaveDays;
                                                  $userLeaveBalanceYear->created_by = Auth::user()->creatorId();
                                                  $userLeaveBalanceYear->leaves_year = $currentYear;
                                             } else {
                                                  $userLeaveBalanceYear->pending_leaves = ($userLeaveBalanceYear->pending_leaves + $leaveDays);
                                             }

                                             $userLeaveBalanceYear->save();
                                             // Add Leave Balance
                                        }
                                   }
                                   $incrementValue++;
                              }
                         }


                         // dd($leaveBalanceAdded);



                         // Add Leave Balance



                         DB::commit();
                    } catch (Exception | Throwable $ex) {
                         //dd($ex);
                         DB::rollBack();
                    }
               }

               $errorRecord = [];

               if (empty($errorArray)) {
                    $data['status'] = 'success';
                    $data['msg'] = __('Record successfully imported');
               } else {
                    $data['status'] = 'error';
                    $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');

                    foreach ($errorArray as $errorData) {
                         $errorRecord[] = implode(',', $errorData);
                    }

                    Session::put('errorArray', $errorRecord);
               }

               return redirect()->back()->with($data['status'], $data['msg']);
          } catch (Exception $e) {
               // dd($e);
               return redirect()->back()->with('error', __("Something is wrong"));
          } catch (Throwable $e) {
               return redirect()->back()->with('error', __("Something is wrong"));
          }
     }

     public function appendEmployeeLeaveBalance(Request $request)
     {
          $response = [];

          $addedLeaveBalanceTotal = 0;
          $addedLeaveBalanceData = [];

          try {
               $employeeIds = $request->employeeIds ?? [];

               $leaveBalanceType = $request->leaveBalanceType ?? 0; // 0 = device leave type days by number of months of year (12 months) and add accordingly, 1 = add passed leave balance
               $customLeaveBalance = $request->customLeaveBalance ?? 0;

               $employeesList = Employee::select(
                    "id",
                    "name",
                    "play_slab_id",
                    "created_by",
               )
                    ->when(!empty($employeeIds), function ($query) use ($employeeIds) {
                         $query->whereIn("id", $employeeIds);
                    })

                    ->orderBy("created_by", "ASC")
                    ->get();

               foreach ($employeesList as $employeeDetails) {

                    // get all leave types of employee's payslab and add leave balance
                    $leaveTypes = LeaveType::where("play_slab_id", $employeeDetails->play_slab_id)->where('created_by', '=', $employeeDetails->created_by)->get();

                    $employeeLeaveBalanceData = [];
                    foreach ($leaveTypes as $leaveType) {
                         $addLeave = 0;
                         if ($leaveBalanceType == 0) {
                              $addLeave = ($leaveType->days / 12);
                         } else {
                              $addLeave = $customLeaveBalance;
                         }

                         DB::beginTransaction();

                         try {
                              // Add User Leave Balance Table
                              $userLeaveBalance = UserLeaveBalance::where([
                                   ["user_id", "=", $employeeDetails->id],
                                   ["leave_type_id", "=", $leaveType->id],
                              ])->first();

                              if (empty($userLeaveBalance)) {
                                   $userLeaveBalance = new UserLeaveBalance();

                                   $userLeaveBalance->user_id = $employeeDetails->id;
                                   $userLeaveBalance->leave_type_id = $leaveType->id;
                                   $userLeaveBalance->pending_leaves = $addLeave;
                                   $userLeaveBalance->created_by = $employeeDetails->created_by;
                              } else {
                                   $userLeaveBalance->pending_leaves = ($userLeaveBalance->pending_leaves + $addLeave);
                              }

                              $userLeaveBalance->save();
                              // Add User Leave Balance Table

                              // Add User Leave Balance Year Table
                              $currentYear = date('Y');
                              $userLeaveBalanceYear = UserLeaveBalanceYear::where([
                                   ["user_id", "=", $employeeDetails->id],
                                   ["leave_type_id", "=", $leaveType->id],
                                   ["leaves_year", "=", $currentYear],
                              ])->first();

                              if (empty($userLeaveBalanceYear)) {
                                   $userLeaveBalanceYear = new UserLeaveBalanceYear();

                                   $userLeaveBalanceYear->user_id = $employeeDetails->id;
                                   $userLeaveBalanceYear->leave_type_id = $leaveType->id;
                                   $userLeaveBalanceYear->pending_leaves = $addLeave;
                                   $userLeaveBalanceYear->created_by = $employeeDetails->created_by;
                                   $userLeaveBalanceYear->leaves_year = $currentYear;
                              } else {
                                   $userLeaveBalanceYear->pending_leaves = ($userLeaveBalanceYear->pending_leaves + $addLeave);
                              }

                              $userLeaveBalanceYear->save();
                              // Add User Leave Balance Year Table

                              DB::commit();

                              $addedLeaveBalanceTotal += $addLeave;

                              $employeeLeaveBalanceData[] = [
                                   "leave_type_id" => $leaveType->id,
                                   "leave_type_name" => $leaveType->title,
                                   "added_leave_balance" => $addLeave,
                              ];
                         } catch (Exception | Throwable $ex) {
                              DB::rollBack();
                         }
                    }

                    $addedLeaveBalanceData[] = [
                         "employee_id" => $employeeDetails->id,
                         "leave_data" => $employeeLeaveBalanceData,
                    ];

                    Log::channel('employee_leave_balance_operations_log')->info("Added Monthly Leave Balance of employee $employeeDetails->id - $employeeDetails->name", [
                         "employee_id" => $employeeDetails->id,
                         "leave_data" => $employeeLeaveBalanceData,
                    ]);
               }

               $response = [
                    "status" => 1,
                    "message" => trans("file.success"),
                    "data" => [
                         "added_leave_balance_total" => $addedLeaveBalanceTotal,
                         "added_leave_balance_data" => $addedLeaveBalanceData,
                    ],
               ];


               // else
               // {
               //      $response = [
               //           "status" => 0,
               //           "message" => trans("file.employee data not found"),
               //           "data" => [],
               //      ];
               // }
          } catch (Exception | Throwable $ex) {
               $response = [
                    "status" => 0,
                    "message" => $ex->getMessage(),
                    "data" => [],
               ];
          }

          return ApiResponse($response);
     }
}
