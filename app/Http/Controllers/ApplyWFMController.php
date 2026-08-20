<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GetPass;
use App\Models\ApplyWFM;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\EarlyLateCommingLeave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Form;
use Illuminate\Support\Facades\Crypt;

class ApplyWFMController extends Controller
{
     public function index()
     {
          if (Auth::user())
          {
               $employee = Employee::where('user_id', Auth::id())->first();
               $getpass   = ApplyWFM::where('employee_id', ($employee->id ?? 0))->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
          }
          return view('applywfm.index', compact('getpass'));
     }

     public function applywfm_request(Request $request)
     {
          $leaves = ApplyWFM::with([
               'employee' => function ($query)
               {
                    $query->where("employee_exit", 0);
               }
          ])
               ->whereHas("employee", function ($query)
               {
                    $query->where("employee_exit", 0);
               })
               ->when(Auth::user()->type != 'company', function ($query)
               {
                    $query->whereIn('employee_id', selectedEmployee());
               })->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC');

          if ($request->ajax())
          {
               $data = $leaves;
               return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('employee_id', function ($row)
                    {
                         return ((Auth::user()->hasPermissionTo('Show Employee')) ? '<a class="btn btn-outline-primary" href=" ' . route('employee.show', Crypt::encrypt($row->employee->id)) . '">' . $row->employee->employee_id . '</a>' : $row->employee->employee_id);
                    })
                    ->addColumn('employee', function ($row)
                    {
                         return $row->employee->name;
                    })
                    ->addColumn('start_date', function ($row)
                    {
                         return !empty($row->start_date) ? Auth::user()->dateFormat($row->start_date) : '';
                    })
                    ->addColumn('end_date', function ($row)
                    {
                         return !empty($row->end_date) ? Auth::user()->dateFormat($row->end_date) : '';
                    })

                    ->filterColumn('employee', function ($query, $keyword)
                    {
                         $query->whereHas('employee', function ($q) use ($keyword)
                         {
                              $q->where('name', 'like', "%{$keyword}%")
                                   ->where("employee_exit", 0);
                         });
                    })
                    ->filterColumn('employee_id', function ($query, $keyword)
                    {
                         $query->whereHas('employee', function ($q) use ($keyword)
                         {
                              $q->where('employee_id', 'like', "%{$keyword}%")
                                   ->where("employee_exit", 0);
                         });
                    })
                    ->addColumn('status', function ($row)
                    {
                         if ($row->status == 0)
                         {
                              $status = '<span class="badge bg-warning p-2 px-3 rounded">' . __('Pending') . '</span>';
                         }
                         elseif ($row->status == 1)
                         {
                              $status = '<span class="badge bg-success p-2 px-3 rounded">' . __('Approved') . '</span>';
                         }
                         elseif ($row->status == 2)
                         {
                              $status = '<span class="badge bg-danger p-2 px-3 rounded">' . __('Rejected') . '</span>';
                         }
                         return $status;
                    })

                    ->addColumn('action', function ($row)
                    {
                         return '<div class="action-btn bg-success ms-2">
                              <a href="#" class="mx-3 btn btn-sm align-items-center" 
                                   data-size="lg"
                                   data-url="' . url('applywfm_action/' . $row->id) . '"
                                   data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" 
                                   title=""
                                   data-title="Apply WFH"
                                   data-bs-original-title="Apply WFH">
                                   <i class="ti ti-caret-right text-white"></i>
                              </a>
                         </div>';
                    })


                    ->rawColumns(['employee_id', 'employee', 'start_date', 'end_date', 'status', 'action'])
                    ->make(true);
          }
          else
          {
               return view('applywfm.applywfm_request', compact('leaves'));
          }
     }

     public function create()
     {
          $users = '';
          if (Auth::user()->type == 'employee')
          {
               $employees       = Employee::where('user_id', '=', Auth::user()->id)->get()->pluck('name', 'id');
               $employeesData   = Employee::where('user_id', '=', Auth::user()->id)->get()->first();
          }
          else
          {
               $employees       = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id', 'play_slab_id');
               $users           = User::where('type', '!=', 'employee')->where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
          }
          return view('applywfm.create', compact('employees', 'users'));
     }

     public function store(Request $request)
     {
          $validator = Validator::make(
               $request->all(),
               [
                    'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
                    'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date'
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               return redirect()->back()->with('error', $messages->first());
          }

          DB::beginTransaction();
          try
          {
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();

               $data                = new ApplyWFM();
               $data->employee_id   = $employee->id;
               $data->start_date    = $request->start_date;
               $data->end_date      = $request->end_date;
               $data->note          = $request->note;
               $data->status        = 0;
               $data->created_by    = Auth::user()->creatorId();
               $data->save();

               DB::commit();

               //notification Code
               foreach ($upUser as $row)
               {
                    foreach (json_decode($row['assign_employees']) ?? [] as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = $employee->name . " has applied for Work-From-Home.";
                                   $notification->details      = $employee->name . " has applied for Work-From-Home.";
                                   // $notification->details      = "Employee Apply Work-From-Home " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'applywfm';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification

               return redirect()->route('applywfm.index')->with('success', __('Apply wfh successfully created.'));
          }
          catch (\Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm.index')->with('error', $ex->getMessage());
          }
          catch (\Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm.index')->with('error', $ex->getMessage());
          }
     }

     public function show($id)
     {
          //
     }

     public function edit($id)
     {
          $data = ApplyWFM::find($id);
          return view('applywfm.edit', compact('data'));
     }

     public function update(Request $request, $id)
     {
          $validator = Validator::make(
               $request->all(),
               [
                    // 'start_date'      => 'required|date|before:end_date',
                    // 'end_date'        => 'date|after:start_date',
                    'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
                    'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date'
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               return redirect()->back()->with('error', $messages->first());
          }

          DB::beginTransaction();
          try
          {
               $employeeId = '';
               if (Auth::user()->type == 'company')
               {
                    $employeeId = $request->employee_id;
               }
               else
               {
                    $employeeId = Auth::id();
               }
               $data            =  ApplyWFM::find($id);
               $data->start_date      = $request->start_date;
               $data->end_date      = $request->end_date;
               $data->note          = $request->note;
               $data->status        = 0;
               $data->created_by    = Auth::user()->creatorId();
               $data->save();

               if (Auth::user()->type == 'employee')
               {
                    $Employee = Employee::where('user_id', $employeeId)->first();
               }
               else
               {
                    $Employee = User::find(Auth::id());
               }

               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               //notification Code
               foreach ($upUser as $row)
               {
                    foreach (json_decode($row['assign_employees']) ?? [] as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = $employee->name . " has applied for Work-From-Home.";
                                   $notification->details      = $employee->name . " has applied for Work-From-Home.";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'applywfm';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               return redirect()->route('applywfm.index')->with('success', __('Apply wfh successfully updated.'));
          }
          catch (\Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm.index')->with('error', $ex->getMessage());
          }
          catch (\Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm.index')->with('error', $ex->getMessage());
          }
     }

     public function destroy($id)
     {
          try
          {
               DB::beginTransaction();

               $data  =  ApplyWFM::find($id);
               $data->delete();

               DB::commit();
               return redirect()->route('applywfm.index')->with('success', __('Apply wfh successfully deleted.'));
          }
          catch (\Exception $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm_request')->with('error', $ex->getMessage());
          }
          catch (\Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm_request')->with('error', $ex->getMessage());
          }
     }

     public function applywfm_action($id)
     {
          $data  =  ApplyWFM::with('employee')->find($id);
          return view('applywfm.action', compact('data'));
     }
     public function applywfm_update_action(request $request)
     {
          DB::beginTransaction();
          try
          {
               $data = ApplyWFM::find($request->leave_id);
               $employee = Employee::where('id', $data->employee_id)->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();

               if ($request->status == 'Approved')
               {
                    $data->status = 1;
               }
               else
               {
                    $data->status = 2;
               }
               $data->save();

               DB::commit();

               //notification Code
               foreach ($upUser as $row)
               {
                    foreach (json_decode($row['assign_employees']) ?? [] as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification   = new NotificationModel();
                                   if ($row->id != Auth::id())
                                   {
                                        $notification->user_id = $row->id;
                                        $notification->redirect     = 'applywfm';
                                   }
                                   else
                                   {
                                        $notification->user_id      = $employee->user_id;
                                        $notification->redirect     = 'applywfm_employee';
                                   }

                                   $status = ($request->status == "Reject" ? "Rejected" : $request->status);

                                   $notification->action_status = $status;
                                   $notification->employee_id  = $employee->id;
                                   // $notification->title        = $employee->name  . "Apply Work-From-Home "  .  $status . ' : ' . User::find(auth::id())->name  .  " ";
                                   $notification->title        = Auth::user()->name . " has " . $status . " the Work-From-Home request.";
                                   $notification->details      = Auth::user()->name . " has " . $status . " the Work-From-Home request.";
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

                         $title = __("Your Work from home has been " . $statusArray[$data->status]);
                         $body = __("Your Work from home has been " . $statusArray[$data->status]);

                         $click_action = "wfm_status_changed";
                         $extraData = ["id" => $data->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
               // Send Push Notification to App

               return redirect()->route('applywfm_request')->with('success', __('Apply wfh status successfully updated.'));
          }
          catch (\Exception $ex)
          {
               dd($ex);
               DB::rollBack();
               return redirect()->route('applywfm_request')->with('error', $ex->getMessage());
          }
          catch (\Throwable $ex)
          {
               DB::rollBack();
               return redirect()->route('applywfm_request')->with('error', $ex->getMessage());
          }
     }
}
