<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Exports\LeaveExport;
use Illuminate\Http\Request;
use App\Mail\LeaveActionSend;
use Illuminate\Support\Carbon;
use App\Models\ExtraLeaveModel;
use App\Imports\EmployeesImport;
use App\Models\NotificationModel;
use App\Http\Controllers\DateTime;
use App\Models\LeaveApprovalModel;
use Illuminate\Support\Facades\DB;
use App\Models\Leave as LocalLeave;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EarlyLateCommingLeave;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use DataTables;
use Form;
use Illuminate\Support\Facades\Crypt;

class EarlyLateCommingLeaveController extends Controller
{
     public function index()
     {
          if (Auth::user()->can('Manage Leave'))
          {
               if (Auth::user())
               {
                    $employee = Employee::where('user_id', Auth::id())->first();
                    $leaves   = EarlyLateCommingLeave::with('employee')->where('employee_id', $employee->id ?? 0)->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
               }
               return view('EarlyLateComming.index', compact('leaves'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function earlyandlate_leave_request(Request $request)
     {
          $leavesRequest = 0;

          if ($request->ajax())
          {

               /*  $data = EarlyLateCommingLeave::with([
                     'employee' => function ($query) {
                          $query->where("employee_exit", 0);
                     }
                ])->whereHas('employee')->when(Auth::user()->type != 'company', function ($query) {
                     $query->whereIn('employee_id', selectedEmployee());
                })->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC'); */
               $data = EarlyLateCommingLeave::with('employee')
                    ->whereHas('employee', function ($q)
                    {
                         $q->where("employee_exit", 0);
                    })
                    ->when(Auth::user()->type != 'company', function ($q)
                    {
                         $q->whereIn('employee_id', selectedEmployee());
                    })
                    ->where('created_by', Auth::user()->creatorId())
                    ->orderBy('id', 'DESC');
               // ->get();

               return Datatables::of($data)
                    ->addIndexColumn()
                    // Employee ID
                    ->addColumn('employee_id', function ($row)
                    {
                         try
                         {
                              return '<a class="btn btn-outline-primary" href=" ' . route('employee.show', Crypt::encrypt($row->employee->id)) . '">' . $row->employee->employee_id . '</a>' ?? $row->employee->employee_id;
                         }
                         catch (Exception $e)
                         {
                              return "";
                         }
                    })

                    // Employee Name
                    ->addColumn('employee', function ($row)
                    {
                         try
                         {
                              return $row->employee->name ?? "";
                         }
                         catch (Exception $e)
                         {
                              return "";
                         }
                    })
                    ->filterColumn('employee', function ($query, $keyword)
                    {
                         $query->whereHas('employee', function ($q) use ($keyword)
                         {
                              $q->where('name', 'like', "%{$keyword}%");
                         });
                    })
                    ->filterColumn('employee_id', function ($query, $keyword)
                    {
                         $query->whereHas('employee', function ($q) use ($keyword)
                         {
                              $q->where('employee_id', 'like', "%{$keyword}%");
                         });
                    })
                    // Date
                    ->addColumn('date', function ($row)
                    {
                         return Auth::user()->dateFormat($row->date);
                    })

                    // Leave Type (Early or Late)
                    ->addColumn('title', function ($row)
                    {
                         return $row->name == 1 ? 'Early Leave' : 'Late Coming';
                    })

                    // Reason
                    ->addColumn('resone', function ($row)
                    {
                         return substr($row->resone, 0, 80) . '..';
                    })

                    // Status Badge
                    ->addColumn('status', function ($row)
                    {
                         if ($row->status == 0)
                         {
                              return '<div class="badge bg-warning p-2 px-3 rounded status-badge5">Pending</div>';
                         }
                         elseif ($row->status == 1)
                         {
                              return '<div class="badge bg-success p-2 px-3 rounded status-badge5">Approved</div>';
                         }
                         elseif ($row->status == 2)
                         {
                              return '<div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>';
                         }
                         else
                         {
                              return '';
                         }
                    })
                    // Action Button
                    ->addColumn('action', function ($row)
                    {
                         $title = __('Short Leave Request');
                         return '
                              <div class="action-btn bg-success ms-2">
                                   <a href="#" class="mx-3 btn btn-sm align-items-center"
                                        data-size="lg"
                                        data-url="' . url('earlyandlate_leave_action/' . $row->id) . '"
                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                        title="" data-title="' . $title . '"
                                        data-bs-original-title="' . $title . '">
                                        <i class="ti ti-caret-right text-white"></i>
                                   </a>
                              </div>';
                    })

                    ->rawColumns(['employee_id', 'employee', 'date', 'title', 'resone', 'status', 'action'])
                    ->make(true);
          }
          else
          {

               $data = EarlyLateCommingLeave::with([
                    'employee' => function ($query)
                    {
                         $query->where("employee_exit", 0);
                    }
               ])
                    ->whereHas('employee')
                    ->when(Auth::user()->type != 'company', function ($query)
                    {
                         $query->whereIn('employee_id', selectedEmployee());
                    })
                    ->where('created_by', '=', Auth::user()->creatorId())
                    ->orderBy('id', 'DESC');

               $leaves = $data->get();
               return view('EarlyLateComming.earlyandlate_leave_request', compact('leaves'));
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Leave'))
          {
               $users = '';
               $settings = Utility::settings();
               $short_leave_hours = $settings['short_leave_hours'] ?? 0;
               $short_leave_limit = $settings['short_leave_limit'] ?? 0;
               if (Auth::user()->type == 'employee')
               {
                    $employees       = Employee::where('user_id', '=', Auth::user()->id)->get()->pluck('name', 'id');
                    $employeesData   = Employee::where('user_id', '=', Auth::user()->id)->get()->first();
                    $leavetypes      = LeaveType::where('play_slab_id', $employeesData->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
                    $leavetypes_days = LeaveType::where('play_slab_id', $employeesData->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
               }
               else
               {
                    $employees       = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id', 'play_slab_id');
                    $leavetypes      = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();
                    $leavetypes_days = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();
                    $users           = User::where('type', '!=', 'employee')->where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id',);
               }
               $employee = Employee::where('user_id', '=', Auth::id())->first();

               $short_leave_count = EarlyLateCommingLeave::where('employee_id', $employee->id)
                    ->where('status', '!=', 2)
                    ->whereRaw('YEAR(date) = ?', [now()->year])
                    ->whereRaw('MONTH(date) = ?', [now()->month])
                    ->count();

               if ($short_leave_limit <= $short_leave_count)
               {
                    return response()->json(['error' => __('You can take up to ' . $short_leave_limit . ' short leaves per month. Otherwise, please apply for Half/Full Day leave.')], 401);
               }
               return view('EarlyLateComming.create', compact('employees', 'leavetypes', 'leavetypes_days', 'users'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }


     public function store(Request $request)
     {
          try
          {
               DB::beginTransaction();
               // $inTime = $request->in_time;
               // $outTime = $request->out_time;
               $employeeId = '';
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               // $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
               // $superior_user = null;

               // if($employee){
               //     $superior_user = $employee->users_types_id;
               // }

               $data               = new EarlyLateCommingLeave();
               $data->name         = $request->leave_type_id;
               $data->employee_id  = $employee->id;

               $data->date      = $request->start_date;
               // $data->in_time   = $inTime;
               // $data->out_time  = $outTime;
               $data->in_time   = "";
               $data->out_time  = "";
               $data->resone    = $request->leave_reason;

               // $data->superior_user  = $superior_user;

               $data->veryfy_status    = 0;
               $data->status           = 0;
               $data->created_by       = Auth::user()->creatorId();
               $data->save();

               //notification Code
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               foreach ($upUser as $row)
               {
                    $assign_employees = json_decode($row['assign_employees']) ?? [];
                    foreach ($assign_employees as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = "Short leave applied by " . $employee->name . " ";
                                   $notification->details      = "Short leave applied by " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'earlyandlate_leave';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               return redirect()->route('earlyandlate_leave.index')->with('success', __('Leave successfully created.'));
          }
          catch (Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
     }

     public function show($id)
     {
          //
     }

     public function edit($id)
     {
          $data =  EarlyLateCommingLeave::with('employee')->find($id);
          $settings = Utility::settings();
          $short_leave_hours = $settings['short_leave_hours'] ?? 0;

          return view('EarlyLateComming.edit', compact('data', 'short_leave_hours'));
     }


     public function update(Request $request, $id)
     {
          try
          {
               DB::beginTransaction();
               // $inTime = $request->in_time;
               // $outTime = $request->out_time;
               $employeeId = '';

               if (Auth::user()->type == 'company')
               {
                    $employeeId = $request->employee_id;
               }
               else
               {
                    $employeeId = Auth::id();
               }

               $data = EarlyLateCommingLeave::find($id);
               $data->name      = $request->leave_type_id;
               // $data->employee_id = $employeeId;

               $data->date      = $request->start_date;
               // $data->in_time   = $inTime;
               // $data->out_time  = $outTime;
               $data->in_time   = "";
               $data->out_time  = "";
               $data->resone    = $request->leave_reason;
               $data->veryfy_status    = 0;
               $data->status    = 0;
               $data->created_by       = Auth::user()->creatorId();
               $data->save();

               if (Auth::user()->type == 'employee')
               {
                    $Employee = Employee::where('user_id', $employeeId)->first();
               }
               else
               {
                    $Employee = User::find(Auth::id());
               }

               //notification Code
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               foreach ($upUser as $row)
               {
                    $assign_employees = json_decode($row['assign_employees']) ?? [];
                    foreach ($assign_employees as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = "Short leave applied by " . $employee->name . " ";
                                   $notification->details      = "Short leave applied by " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'earlyandlate_leave';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               return redirect()->route('earlyandlate_leave.index')->with('success', __('Leave successfully created.'));
          }
          catch (Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
     }


     public function destroy($id)
     {
          try
          {
               DB::beginTransaction();
               $data = EarlyLateCommingLeave::find($id);
               $data->delete();
               DB::commit();
               return redirect()->route('earlyandlate_leave.index')->with('success', __('Leave successfully delete.'));
          }
          catch (Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
     }

     public function action($id)
     {
          $data = EarlyLateCommingLeave::find($id);
          $employee  = Employee::find($data->employee_id);
          return view('EarlyLateComming.action', compact('employee', 'data'));
     }

     public function changeaction(request $request)
     {
          try
          {
               DB::beginTransaction();
               $data = EarlyLateCommingLeave::find($request->leave_id);

               //notification Code
               $employee = Employee::where('id', $data->employee_id)->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();

               $approve_short_leave = EarlyLateCommingLeave::where('employee_id', $data->employee_id)
                    ->where('status', 1)
                    ->whereRaw('YEAR(date) = ?', [now()->year])
                    ->whereRaw('MONTH(date) = ?', [now()->month])
                    ->count();

               $settings = Utility::settings();
               $short_leave_limit = $settings['short_leave_limit'] ?? 0;

               if ($short_leave_limit <= $approve_short_leave)
               {
                    return back()->with('error', 'Short leave limit exceed');
               }

               if ($request->status == 'Approved')
               {
                    $data->status = 1;
               }
               else
               {
                    $data->status = 2;
               }
               $data->save();

               foreach ($upUser as $row)
               {
                    $assign_employees = json_decode($row['assign_employees']) ?? [];
                    foreach ($assign_employees as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification   = new NotificationModel();
                                   if ($row->id != Auth::id())
                                   {
                                        $notification->user_id = $row->id;
                                        $notification->redirect     = 'earlyandlate_leave';
                                   }
                                   else
                                   {
                                        $notification->user_id      = $employee->user_id;
                                        $notification->redirect     = 'earlyandlate_leave_employee';
                                   }

                                   $status = ($request->status == "Reject" ? "Rejected" : $request->status);

                                   $notification->action_status = $status;
                                   $notification->employee_id  = $employee->id;
                                   // $notification->title        = $employee->name  . "Late coming leave of "  .  $status . ' : ' . User::find(auth::id())->name  .  " ";
                                   $notification->title        = 'Short Leave ' . $status . ' by ' . Auth::user()->name;
                                   $notification->details        = 'Short Leave ' . $status . ' by ' . Auth::user()->name;
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification

               // Send Push Notification to App
               if (in_array($data->status, [1, 2]))
               {
                    $usersData = User::where("id", $employee->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $statusArray = [
                              "1" => "Approved",
                              "2" => "Rejected",
                         ];

                         $leaveText = ($data->name == 1 ? __("Early Leaving") : __("Late Coming"));
                         $title = __("Your Short Leave for $leaveText has been " . $statusArray[$data->status]);
                         $body = __("Your Short Leave for $leaveText has been " . $statusArray[$data->status]);

                         $click_action = "short_leave_status_changed";
                         $extraData = ["id" => $data->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
               // Send Push Notification to App


               DB::commit();
               return redirect()->route('earlyandlate_leave_request')->with('success', __('Leave status successfully updated.'));
          }
          catch (Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('earlyandlate_leave.index')->with('error', $ex->getMessage());
          }
     }
}
