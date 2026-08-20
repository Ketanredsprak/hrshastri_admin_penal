<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CityTier;
use App\Models\Employee;
use App\Models\GradeType;
use App\Models\VisitType;
use App\Models\Department;
use App\Models\CommuteType;
use App\Models\MyClaimModel;
use App\Models\MyVisitModel;
use Illuminate\Http\Request;

use App\Models\GradeTierData;
use App\Models\CategoriesType;
use App\Models\CityModel;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\FacadesAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Form;
use Illuminate\Support\Facades\Crypt;

class MyClaimReimbursement extends Controller
{
     public function __construct()
     {
          $this->middleware(
               [
                    'auth',
                    'XSS',
               ]
          );
     }

     public function my_claim_request(Request $request)
     {
          $myclaimsdata = MyClaimModel::with('GradeName', 'myvisitData', 'employee');
          if (Auth::user()->type == 'employee')
          {
               $employee = Employee::where('user_id', Auth::id())->first();
               $myclaimsdata->where('userid', '=', $employee->id);
          }
          else
          {
               $myclaimsdata->whereIn('userid', selectedEmployee());
          }
          $myclaimsdata = $myclaimsdata->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc');
          if ($request->ajax())
          {
               $data = $myclaimsdata;
               return Datatables::of($data)
                    ->addIndexColumn()

                    // Employee ID column
                    ->addColumn('employee_id', function ($row)
                    {
                         return !empty($row->employee) ? $row->employee->employee_id : '';
                    })

                    // Employee name
                    ->addColumn('employee', function ($row)
                    {
                         return !empty($row->employee) ? $row->employee->name : '';
                    })

                    ->filterColumn('employee', function($query, $keyword) {
                         $query->whereHas('employee', function($q) use ($keyword) {
                              $q->where('name', 'like', "%{$keyword}%");
                         });
                    })
                    ->filterColumn('employee_id', function ($query, $keyword) {
                         $query->whereHas('employee', function ($q) use ($keyword) {
                              $q->where('employee_id', 'like', "%{$keyword}%");
                         });
                    })
                    // Visit type logic
                    ->addColumn('visit_type', function ($row)
                    {
                         if ($row->visit_type == 1)
                         {
                              return 'Other Claim';
                         }
                         else
                         {
                              if (!empty($row->myvisitData))
                              {
                                   if ($row->myvisitData->visit_type == 1)
                                   {
                                        return 'Local visit';
                                   }
                                   else
                                   {
                                        return 'Out of City visit';
                                   }
                              }
                         }
                         return '';
                    })

                    // Visit name
                    ->addColumn('visit_name', function ($row)
                    {
                         return !empty($row->myvisitData) ? $row->myvisitData->visit_name : '';
                    })

                    // Name (I guess this is $row->name)
                    ->addColumn('other_claim', function ($row)
                    {
                         return $row->name;
                    })

                    // Commuting type
                    ->addColumn('vehicle_type', function ($row)
                    {
                         if (!empty($row->commuting_type))
                         {
                              return $row->commuting_type == 1 ? 'Two wheeler' : 'Four wheeler';
                         }
                         return '';
                    })

                    // Total amount (with rupee symbol)
                    ->addColumn('amount', function ($row)
                    {
                         return '&#8377; ' . $row->total_amount;
                    })

                    // Tier name if visit_type==2
                    ->addColumn('tier', function ($row)
                    {
                         if (!empty($row->myvisitData) && $row->myvisitData->visit_type == 2)
                         {
                              $tier = CityTier::find($row->myvisitData->tier_id);
                              return $tier ? $tier->name : '';
                         }
                         return '';
                    })

                    // City name if visit_type==2
                    ->addColumn('city', function ($row)
                    {
                         if (!empty($row->myvisitData) && $row->myvisitData->visit_type == 2)
                         {
                              $city = CityModel::find($row->myvisitData->city_id);
                              return $city ? $city->name : '';
                         }
                         return '';
                    })

                    // form date from myvisitData
                    ->addColumn('visit_date', function ($row)
                    {
                         return !empty($row->myvisitData) ? $row->myvisitData->form_date : '';
                    })

                    // Created at date formatted
                    ->addColumn('claimed_on', function ($row)
                    {
                         return Auth::user()->dateFormat($row->created_at);
                    })

                    // Status badge
                    ->addColumn('status', function ($row)
                    {
                         if ($row->status == 'Pending')
                         {
                              return '<div class="badge bg-warning p-2 px-3 rounded status-badge5">Pending</div>';
                         }
                         elseif ($row->status == 'Rejected')
                         {
                              return '<div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>';
                         }
                         elseif ($row->status == 'Approved')
                         {
                              return '<div class="badge bg-success p-2 px-3 rounded status-badge5">Approved</div>';
                         }
                         return '';
                    })

                    // Who changed status
                    ->addColumn('status_changed_by', function ($row)
                    {
                         return findEmployeeByUser($row->status_changed_by);
                    })

                    // Action buttons
                    ->addColumn('action', function ($row)
                    {
                         $user = auth()->user();
                         $html = '<span>';

                         // Manage Claim Action if permission & not approved
                         if ($user->can('Edit Claim Request') && $row->status != 'Approved')
                         {
                              $html .= '
                        <div class="action-btn bg-success ms-2">
                            <a href="#" class="mx-3 btn btn-sm align-items-center"
                               data-size="lg"
                               data-url="' . url('my_claim/' . $row->id . '/action_hr') . '"
                               data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                               title="" data-title="' . __("Action") . '"
                               data-bs-original-title="' . __("Manage Claim") . '">
                                <i class="ti ti-caret-right text-white"></i>
                            </a>
                        </div>';
                         }

                         // Claim details view
                         $html .= '
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm align-items-center"
                           data-size="lg"
                           data-url="' . route('my_claim.show', $row->id) . '"
                           data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                           title="" data-title="' . __("Claim Details") . '"
                           data-bs-original-title="' . __("Claim Details") . '">
                            <i class="ti ti-eye text-white"></i>
                        </a>
                    </div>';

                         // Edit Claim if status is Pending or Rejected and user is employee
                         if ($user->type == 'employee' && in_array($row->status, ['Pending', 'Rejected']))
                         {
                              $html .= '
                        <div class="action-btn btn-info ms-2">
                            <a href="#" data-size="md"
                               data-url="' . route('my_claim.edit', $row->id) . '"
                               data-ajax-popup="true" data-title="' . __("Edit claim") . '"
                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                               data-bs-toggle="tooltip" data-bs-placement="top"
                               title="' . __("Edit claim") . '">
                                <i class="ti ti-pencil text-white"></i>
                            </a>
                        </div>';
                         }

                         $html .= '</span>';

                         return $html;
                    })

                    // Make sure to allow raw HTML in these columns
                    ->rawColumns(['employee_id', 'employee', 'visit_type', 'visit_name', 'other_claim', 'vehicle_type', 'amount', 'tier', 'city', 'visit_date', 'claimed_on', 'status', 'status_changed_by', 'action',])
                    ->make(true);
          }
          else
          {
               $myclaimsdata = $myclaimsdata->get();
               return view('Myclaim.my_claim_request', compact('myclaimsdata'));
          }
     }


     public function visitcategory()
     {
          $gradetypes = GradeType::where('assign_grade_id', 1)->where('created_by', Auth::user()->creatorId())->get();
          $visittypes = VisitType::where('created_by', Auth::user()->creatorId())->get();
          return view("Myclaim.visitcatefory", compact('gradetypes', 'visittypes'));
     }
     public function get_getGradeType(request $request)
     {
          $employeeData = Employee::where('user_id', Auth::id())->first();
          $data = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->where('created_by', Auth::user()->creatorId())->where('visit_type', $request->getvisit_data)->get();
          return $data;
     }

     //local visit
     public function get_commutingData(request $request)
     {
          $data = MyVisitModel::find($request->gradetype);
          $employee = Employee::where('user_id', Auth::id())->first();
          $data = GradeType::where('visit_type', $data->visit_type)->where('assign_grade_id', $employee->grade_type_id)->first();
          $commutingdata = CommuteType::whereIn('id', $data->commuting_id)->get();
          return $commutingdata;
     }

     public function getAmount_old(request $request)
     {
          $employee = Employee::where('user_id', Auth::id())->first();
          $Gradedata = GradeType::where('assign_grade_id', $employee->grade_type_id)->first();
          if ($request->commutingtype == 1) //two whiller
          {
               return $Gradedata->in_city_two_wheeler * $request->kilometer;
          }
          else
          {
               return $Gradedata->in_city_four_wheeler * $request->kilometer;
          }
     }

     public function getAmount(request $request)
     {
          $employee = Employee::where('user_id', Auth::id())->first();
          $Gradedata = GradeType::where('assign_grade_id', $employee->grade_type_id)->first();
          return response()->json(['data' => $Gradedata]);
          // if ($request->commutingtype == 1) //two whiller
          // {
          //     $amount = ($Gradedata->in_city_two_wheeler * $request->kilometer) + $request->misc_amount;
          //     return $amount;
          // } else {
          //     $amount = ($Gradedata->in_city_four_wheeler * $request->kilometer) + $request->misc_amount;
          //     return $amount;
          // }
     }

     public function get_getAmountinput(request $request)
     {
          $request->fix_not_Actual_total_amount;
          $calculation = $request->fix_not_Actual_total_amount + $request->amountinput;
          return $calculation;
     }


     public function get_getCitiesData(request $request)
     {
          $data = GradeType::find($request->getvisit_data);
          $citiestier = CityTier::where('id', $data->city_tier_id)->first();
          $cities = DB::table('city')->whereIn('id', $citiestier->citysid)->get();
          return $cities;
     }

     public function get_getExpensesdData(request $request)
     {
          $data = GradeType::find($request->getvisit_data);
          $data->expenses_data;
          $array = [];
          foreach ($data->expenses_data as $row)
          {
               $array[] = $row['expenses_id'];
          }
          $dataexpensive = CategoriesType::whereIn('id', $array)->get();
          return $dataexpensive;
     }

     public function index()
     {
          $myclaimsdata = MyClaimModel::with('GradeName', 'myvisitData', 'employee');
          // if (Auth::user()->type == 'employee') {
          //     $employee = Employee::where('user_id', Auth::id())->first();
          //     $myclaimsdata->where('userid', '=', $employee->id);
          // } else {
          //     $myclaimsdata->whereIn('userid', selectedEmployee());
          // }

          $employee = Employee::where('user_id', Auth::id())->first();
          if ($employee != null)
          {
               $myclaimsdata->where('userid', '=', $employee->id);
          }
          $myclaimsdata = $myclaimsdata->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc')->get();
          return view('Myclaim.index', compact('myclaimsdata'));
     }

     public function create()
     {
          $employee = Employee::where('user_id', Auth::id())->first();
          $myvisit = MyVisitModel::where('claim_status', null)->where('status', 'Approved')->where('userid', $employee->id)->where('created_by', '=', Auth::user()->creatorId())->get();
          return view('Myclaim.create', compact('myvisit'));
     }

     public function store(Request $request)
     {
          $validator1 = Validator::make(
               $request->all(),
               [
                    'visit_type' => 'required',
               ],
               [
                    'visit_type.required' => 'First Select which type of visit you want to claim.',
               ]
          );
          if ($validator1->fails())
          {
               $messages = $validator1->messages();
               return response()->json(['status' => 0, 'message' => $messages]);
          }

          $visit_type = $request->visit_type; // 0 = normal visit, 1 = other visit

          $employee = Employee::where('user_id', Auth::id())->first();
          $arr = [];
          $expense_ids = [];
          $en_uploaded = '';

          if ($visit_type == 0) // normal visit
          {
               $validator1 = Validator::make(
                    $request->all(),
                    [
                         'my_visit_id' => 'required',
                    ],
                    [
                         'my_visit_id.required' => 'Visit type field is required',
                    ]
               );
               if ($validator1->fails())
               {
                    $messages = $validator1->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }

               $myvisit = MyVisitModel::find($request->my_visit_id);
               $grade_id = GradeType::where('assign_grade_id', $employee->grade_type_id)->first();
               $datatier = [];
               if (!empty($myvisit->tier_id))
               {
                    $datatier = GradeTierData::with('expenseData')->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id)->get();
               }

               if (count($datatier) > 0)
               {
                    foreach ($request->expens_name as $key => $expense_id)
                    {
                         $grade_tier_data = GradeTierData::with('expenseData')->find($expense_id);
                         if ($grade_tier_data != null)
                         {
                              if ($grade_tier_data->is_actual == 0 && $request->expens_amount[$key] > $grade_tier_data->amount)
                              {
                                   return response()->json(['status' => 0, 'message' => ['expens_amount.' . $key => ['You have exceeded the amount given by company']]]);
                              }
                              array_push($arr, $grade_tier_data->expenseData->name);
                         }

                         $expense_ids[] = $expense_id;
                    }
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'my_visit_id' => 'required',
                              'description' => 'required',
                              'expens_name.*' => 'required',
                              'expens_amount.*' => 'required_with:expens_name.*',
                         ],
                         [
                              'commuting_type.required' => 'Vehicle type field is required',
                              'description.required' => 'Description field is required',
                              'expens_name.*.required' => 'Expense name field is required',
                              'expens_amount.*.required_with' => 'Expense amount field is required',
                         ]
                    );
               }
               else
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'my_visit_id' => 'required',
                              'commuting_type' => 'required',
                              'kilomiter' => 'required',
                              // 'misc'         => 'required',
                              'description' => 'required',
                         ],
                         [
                              'commuting_type.required' => 'Vehicle type field is required',
                              'description.required' => 'Description field is required',
                              'kilomiter.required' => 'Kilomiter field is required',
                              'misc.required' => 'Misc field is required',
                         ]
                    );
               }
               if ($validator->fails())
               {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'claim_name' => 'required',
                         'description' => 'required',
                    ],
                    [
                         'claim_name.required' => 'Claim Name field is required',
                         'description.required' => 'Description field is required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }
          }

          try
          {
               DB::beginTransaction();

               if ($request->hasFile('uploadfile'))
               {
                    $image = $request->file('uploadfile');
                    $en_uploaded = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/fileupload');
                    $image->move($destinationPath, $en_uploaded);
               }

               $data = new MyClaimModel();
               $data->name = $request->claim_name ?? "";
               $data->my_visit_id = $request->my_visit_id ?? 0;
               $data->commuting_type = $request->commuting_type ?? null;
               $data->kilomiter = $request->kilomiter ?? 0;
               $data->description = $request->description ?? "";
               $data->fix_amount = $request->fix_amount ?? 0;
               $data->total_amount = $request->total_amount ?? 0;
               $data->misc = $request->misc ?? 0;

               $data->expens_name = json_encode($arr);
               $data->expens_amount = json_encode($request->expens_amount) ?? [];
               $data->expense_ids = json_encode($request->expense_ids) ?? [];
               $data->hidden_expense_is_actual = json_encode($request->hidden_expense_is_actual) ?? [];

               $data->fileupload = $en_uploaded ?? "";
               $data->status = 'Pending';
               $data->userid = $employee->id;
               $data->created_by = Auth::user()->creatorId();

               $data->visit_type = $visit_type ?? 0;

               $data->save();

               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();

               //notification Code
               foreach ($upUser as $row)
               {
                    $assign_employees = json_decode($row['assign_employees']) ?? [];
                    foreach ($assign_employees as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification = new NotificationModel();
                                   $notification->user_id = $row->id;
                                   $notification->employee_id = $employee->id;
                                   $notification->title = $employee->name . " has requested for claim";
                                   $notification->details = $employee->name . " has requested for claim";
                                   $notification->status = 0;
                                   $notification->date = Carbon::now();
                                   $notification->created_by = $employee->created_by;
                                   $notification->redirect = 'my_claim';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification

               DB::commit();
               return response()->json(['status' => 1, 'message' => __('Claim successfully created.')]);
               // return redirect()->route('my_claim.index')->with('success', __('Claim successfully created.'));
          }
          catch (Exception $ex)
          {
               DB::rollback();
               return response()->json(['status' => 0, 'message' => showExceptionMessage($ex)]);
          }
          catch (Exception $ex)
          {
               DB::rollback();
               return response()->json(['status' => 0, 'message' => showExceptionMessage($ex)]);
          }
     }

     public function show($id)
     {
          $data = MyClaimModel::with('myvisitData')->find($id);

          $employee = Employee::where("id", $data->userid)->first();
          return view('Myclaim.show', compact('data', 'employee'));
     }

     public function edit($id)
     {
          try
          {
               $data = MyClaimModel::find($id);

               if (!empty($data))
               {
                    $myvisit = [];
                    if ($data->visit_type == 0)
                    {
                         $myvisit = MyVisitModel::find($data->my_visit_id);
                    }

                    $employeeData = Employee::where('user_id', Auth::id())->first();

                    $grade_id = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->first();
                    $datatier = [];
                    if (!empty($myvisit->tier_id))
                    {
                         $datatier = GradeTierData::with('expenseData')->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id)->get();
                    }
                    return view('Myclaim.edit', compact('data', 'myvisit', 'datatier'));
               }
               return response()->json(['status' => 0, 'message' => __("No data found")]);
          }
          catch (Throwable $e)
          {
               return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
          }
          catch (Exception $e)
          {
               return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
          }
     }

     public function update(Request $request, $id)
     {
          $data = MyClaimModel::with('myvisitData')->find($id);

          $arr = [];
          $expense_ids = [];

          // $validator1 = Validator::make(
          //      $request->all(),
          //      [
          //           'visit_type'    => 'required',
          //      ],
          //      [
          //           'visit_type.required'  => 'First Select which type of visit you want to claim.',
          //      ]
          // );
          // if ($validator1->fails())
          // {
          //      $messages = $validator1->messages();
          //      return response()->json(['status' => 0, 'message' => $messages]);
          // }

          $visit_type = $data->visit_type ?? 0; // 0 = normal visit, 1 = other visit

          if ($visit_type == 0) // normal visit
          {

               if ($data->myvisitData->visit_type == 2)
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'my_visit_id' => 'required',
                              'description' => 'required',
                              // 'expens_name.*'       => 'required',
                              // 'expens_amount.*'       => 'required_with:expens_name.*',
                         ],
                         [
                              'description.required' => 'Description field is required',
                              // 'expens_name.*.required'  => 'Expense name field is required',
                              // 'expens_amount.*.required_with'  => 'Expense amount field is required',
                         ]
                    );
               }
               if ($data->myvisitData->visit_type == 1)
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'my_visit_id' => 'required',
                              'commuting_type' => 'required',
                              'kilomiter' => 'required',
                              // 'misc'         => 'required',
                              'description' => 'required',
                         ],
                         [
                              'commuting_type.required' => 'Vehicle type field is required',
                              'description.required' => 'Description field is required',
                              'kilomiter.required' => 'Kilomiter field is required',
                              'misc.required' => 'Misc field is required',
                         ]
                    );
               }
               if ($validator->fails())
               {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }

               if ($data->myvisitData->visit_type == 2)
               {

                    foreach ($request->expens_name as $key => $expense_id)
                    {
                         $grade_tier_data = GradeTierData::with('expenseData')->find($expense_id);
                         if (!empty($grade_tier_data))
                         {
                              if ($grade_tier_data->is_actual == 0 && $request->expens_amount[$key] > $grade_tier_data->amount)
                              {
                                   return response()->json(['status' => 0, 'message' => ['expens_amount.' . $key => ['You have exceeded the amount given by company']]]);
                              }
                              array_push($arr, $grade_tier_data->expenseData->name);
                              $expense_ids[] = $expense_id;
                         }
                    }
               }
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'claim_name' => 'required',
                         'description' => 'required',
                    ],
                    [
                         'claim_name.required' => 'Claim Name field is required',
                         'description.required' => 'Description field is required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }
          }

          try
          {
               DB::beginTransaction();

               $en_uploaded = $data->fileupload;

               if ($request->hasFile('uploadfile'))
               {
                    $image = $request->file('uploadfile');
                    $en_uploaded = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/fileupload');
                    $image->move($destinationPath, $en_uploaded);
               }

               $data->name = $request->claim_name ?? "";
               $data->commuting_type = $request->commuting_type ?? "";
               $data->kilomiter = $request->kilomiter ?? 0;
               $data->description = $request->description ?? "";
               $data->fix_amount = $request->fix_amount ?? 0;
               $data->total_amount = $request->total_amount ?? 0;
               $data->misc = $request->misc ?? 0;

               $data->expens_name = json_encode($arr);
               $data->expens_amount = json_encode($request->expens_amount);
               $data->expense_ids = json_encode($expense_ids) ?? [];
               $data->hidden_expense_is_actual = json_encode($request->hidden_expense_is_actual);

               $data->fileupload = $en_uploaded;
               $data->status = 'Pending';

               $data->update();

               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               //notification Code

               foreach ($upUser as $row)
               {
                    $assign_employees = json_decode($row['assign_employees']) ?? [];
                    foreach ($assign_employees as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification = new NotificationModel();
                                   $notification->user_id = $row->id;
                                   $notification->employee_id = $employee->id;
                                   $notification->title = $employee->name . " has updated the request for claim";
                                   $notification->details = $employee->name . " has updated the request for claim";
                                   $notification->status = 0;
                                   $notification->date = Carbon::now();
                                   $notification->created_by = $employee->created_by;
                                   $notification->redirect = 'my_claim';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               return response()->json(['status' => 1, 'message' => __('Claim successfully updated.')]);
          }
          catch (Exception $e)
          {
               DB::rollBack();
               return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
          }
          catch (Throwable $e)
          {
               DB::rollBack();
               return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
          }
     }

     public function destroy($id)
     {
          $data = MyClaimModel::find($id);
          $data->delete();
          return redirect()->route('my_claim.index')->with('success', __('Claim successfully deleted.'));
     }

     public function action_hr($id)
     {
          $data = MyClaimModel::with('myvisitData')->find($id);

          $employee = Employee::where("id", $data->userid)->first();

          return view('Myclaim.action_hr', compact('data', 'employee'));
     }

     public function changeaction_hr(request $request)
     {
          try
          {
               DB::beginTransaction();

               $data = MyClaimModel::find($request->claim_id);
               $employeeData = Employee::find($data->userid);

               $claim_status_db = 0;
               if ($request->claim_status == 'Reject')
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'reject_reason' => 'required',
                         ],
                         [
                              'reject_reason.required' => 'Reject reason is required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->messages();
                         return response()->json(['status' => 0, 'message' => $messages]);
                    }
                    // $myvisit->claim_status = 2;
                    $claim_status_db = 2;
                    $claim_status = 'Rejected';
                    $title = "Your Claim request has been rejected";
                    $details = "Your Claim request has been rejected";
               }
               else
               {
                    $claim_status = 'Approved';
                    // $myvisit->claim_status = 1;
                    $claim_status_db = 2;
                    $title = "Your Claim request has been approved";
                    $details = "Your Claim request has been approved";
               }

               if ($data->visit_type == 0) // 0 = normal visit, 1 = other visit
               {
                    $myvisit = MyVisitModel::find($data->my_visit_id);
                    if (!empty($myvisit))
                    {
                         $myvisit->claim_status = $claim_status_db;
                         $myvisit->save();
                    }
               }

               $data->status = $claim_status;
               $data->status_changed_by = Auth::id();
               $data->reject_reason = $request->reject_reason;
               $data->save();

               NotificationModel::create([
                    'user_id' => $employeeData->user_id,
                    'employee_id' => Auth::id(),  //sender_user_id
                    'title' => $title,
                    'details' => $details,
                    'created_by' => $employeeData->created_by,
                    'date' => Carbon::now(),
                    'status' => 0,
                    'redirect' => 'my_claim',
               ]);

               // $users = User::find($employeeData->user_id);
               // $user_token = $users->device_token;
               // if ($user_token) {
               //      $title = $title;
               //      $body = $details;
               //      $click_action = 'claim_action';

               //      // if ($users->device_type == 1) {
               //      $notificationMessage = array(
               //           'title' => $title,
               //           'body' => $body,
               //           // 'priority' => "high",
               //           // 'click_action' => $click_action,
               //      );
               //      // } else {
               //      //     $notificationMessage = array();
               //      // }

               //      // $extraData = ['profile_id' =>  $requestData['profile_id'], 'title' => $title, 'body' => $body, 'click_action' => $click_action];
               //      $extraData = ['id' =>  $data->id, 'title' => $title, 'body' => $body];

               //      pushNotification($notificationMessage, $extraData, $user_token);
               // }

               // Send Push Notification to App
               if (in_array($claim_status, ["Rejected", "Approved"]))
               {
                    $usersData = User::where("id", $employeeData->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $statusArray = [
                              "Rejected" => "Rejected",
                              "Approved" => "Approved",
                         ];

                         $title = __("Your Claim request has been " . $statusArray[$claim_status]);
                         $body = __("Your Claim request has been " . $statusArray[$claim_status]);

                         $click_action = "claim_status_changed";
                         $extraData = ["id" => $data->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
               // Send Push Notification to App

               DB::commit();

               return response()->json(['status' => 1, 'message' => __('Claim status successfully updated.')]);
          }
          catch (Exception $ex)
          {
               DB::rollback();
               return response()->json(['status' => 0, 'message' => showExceptionMessage($ex)]);
          }
          catch (Exception $ex)
          {
               DB::rollback();
               return response()->json(['status' => 0, 'message' => showExceptionMessage($ex)]);
          }
     }

     public function get_getVisitTypeexpense_old(request $request)
     {
          $employeeData = Employee::where('user_id', Auth::id())->first();
          $myvisit = MyVisitModel::find($request->getVisitType);
          $grade_id = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->first();
          $datatier = 0;
          if (!empty($myvisit->tier_id))
          {
               $datatier = GradeTierData::with('expenseData')->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id)->get();
          }
          return $datatier;
     }

     public function get_getVisitTypeexpense(request $request)
     {
          $employeeData = Employee::where('user_id', Auth::id())->first();
          $myvisit = MyVisitModel::find($request->getVisitType);
          $grade_id = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->first();
          $datatier = [];
          if (!empty($myvisit->tier_id))
          {
               $datatier = GradeTierData::with('expenseData')->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id)->get();
          }
          if (count($datatier) > 0)
          {
               $html = '<option value="">Expenses Name</option>';
               foreach ($datatier as $key => $value)
               {
                    $html .= '<option value="' . $value->id . '" data-id="' . $value->id . '">' . $value->expenseData->name . '</option>';
               }

               return response()->json(['status' => 1, 'data' => $datatier, 'options' => $html]);
          }
          return response()->json(['status' => 0]);
     }

     public function compareActual(Request $request)
     {
          $requestData = $request->all();
          // $employeeData   = Employee::where('user_id', Auth::id())->first();
          // $myvisit = MyVisitModel::find($requestData['get_visit_type']);
          // $grade_id = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->first();
          // $expense_type = CategoriesType::where('name', $requestData['expense_name'])->where('created_by', Auth::user()->creatorId())->pluck('id');

          // $grade_tier_data = GradeTierData::where('expense_id', $expense_type)->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id)->where('created_by', Auth::user()->creatorId())->first();
          $grade_tier_data = GradeTierData::find($requestData['expense_id']);
          if ($grade_tier_data->is_actual == 0 && $requestData['expense_amount'] > $grade_tier_data->amount)
          {
               return response()->json(['status' => 0, 'message' => 'You have exceeded the amount given by company']);
          }
          else
          {
               return response()->json(['status' => 1, 'grade_tier_data' => $grade_tier_data]);
          }
          return response()->json(['status' => 1, 'grade_tier_data' => $grade_tier_data]);
     }

     public function fileDownload($id)
     {
          $data = MyClaimModel::find($id);
          if ($data->fileupload)
          {
               $file = public_path('fileupload/' . $data->fileupload);

               $headers = array(
                    'Content-Type: application/pdf',
                    'Content-Type: application/png',
                    'Content-Type: application/jpg',
                    'Content-Type: application/jpeg',
                    'Content-Type: application/txt',
               );

               return Response::download($file, $data->fileupload, $headers);
          }
     }

     public function approvedClaims()
     {
          $myclaimsdata = MyClaimModel::with('GradeName', 'myvisitData', 'employee')->where("status", "Approved");
          if (Auth::user()->type == 'employee')
          {
               $employee = Employee::where('user_id', Auth::id())->first();
               $myclaimsdata->where('userid', '=', $employee->id);
          }
          else
          {
               $myclaimsdata->whereIn('userid', selectedEmployee());
          }
          $myclaimsdata = $myclaimsdata->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'desc')->get();

          return view('Myclaim.approved-claims-list', compact('myclaimsdata'));
     }
}
