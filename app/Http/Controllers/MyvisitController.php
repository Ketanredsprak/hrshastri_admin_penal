<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CityTier;
use App\Models\Employee;
use App\Models\GradeType;
use App\Models\MyVisitModel;

use Illuminate\Http\Request;
use App\Models\GradeTierData;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\FacadesAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Form;
use Illuminate\Support\Facades\Crypt;

class MyvisitController extends Controller
{
     public function my_visit_request(Request $request)
     {
          $data = MyVisitModel::with('tierData', 'cityData', 'employee')->whereHas("employee");
          if (Auth::user()->type == 'employee')
          {
               $employee = Employee::where('user_id', Auth::id())->first();
               $data->where('userid', '=', $employee->id);
          }
          else
          {
               $data->whereIn('userid', selectedEmployee());
          }

          $data = $data->where('created_by', Auth::user()->creatorId())->orderBy('id', 'desc');
          if ($request->ajax())
          {
               return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('employee_id', function ($row)
                    {
                         return ((Auth::user()->hasPermissionTo('Show Employee')) ? '<a class="btn btn-outline-primary" href=" ' . route('employee.show', Crypt::encrypt($row->id)) . '">' . $row->employee->employee_id . '</a>' : $row->employee->employee_id);
                    })
                    ->addColumn('employee', function ($row)
                    {
                         return $row->employee->name ?? "";
                    })
                    ->addColumn('visit_type', function ($row)
                    {
                         if ($row->visit_type == 1)
                         {
                              return "Local visit";
                         }
                         else
                         {
                              return "Out of City visit";
                         }
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
                    ->addColumn('visit_name', function ($row)
                    {
                         return $row->visit_name;
                    })
                    ->addColumn('from_date', function ($row)
                    {
                         return Carbon::parse($row->from_date)->format('Y-m-d');
                    })
                    ->addColumn('to_date', function ($row)
                    {
                         return Carbon::parse($row->to_date)->format('Y-m-d');
                    })
                    ->addColumn('tier', function ($row)
                    {
                         return !empty($row->tierData) ? $row->tierData->name : '';
                    })
                    ->addColumn('city', function ($row)
                    {
                         return !empty($row->cityData) ? $row->cityData->name : '';
                    })
                    ->addColumn('descraption', function ($row)
                    {
                         return substr($row->description, 0, 80) . (strlen($row->description) > 80 ? '...' : '');
                    })
                    ->addColumn('status', function ($row)
                    {
                         $status = $row->status;
                         if ($status == 'Pending')
                         {
                              return '<span class="badge bg-primary p-2 px-3 rounded">' . $status . '</span>';
                         }
                         elseif ($status == 'Approved')
                         {
                              return '<span class="badge bg-success p-2 px-3 rounded">' . $status . '</span>';
                         }
                         elseif ($status == 'Reject')
                         {
                              return '<span class="badge bg-danger p-2 px-3 rounded">' . $status . '</span>';
                         }
                    })
                    ->addColumn('approved_rejected_by', function ($row)
                    {
                         return findEmployeeByUser($row->status_changed_by);
                    })
                    ->addColumn('action', function ($row)
                    {
                         $user = auth()->user();
                         $html = '<span>';

                         // Check for permission: Edit Visit Request
                         if ($user->can('Edit Visit Request'))
                         {
                              $html .= '
                                   <div class="action-btn bg-success ms-2">
                                        <a href="#" class="mx-3 btn btn-sm align-items-center"
                                             data-size="lg"
                                             data-url="' . url('my_visit/' . $row->id . '/action_hr') . '"
                                             data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                             title="" data-title="' . __("Action") . '"
                                             data-bs-original-title="' . __("Manage visit") . '">
                                             <i class="ti ti-caret-right text-white"></i>
                                        </a>
                                   </div>';
                         }

                         // If employee and status is Pending or Reject, show edit button
                         if ($user->type === 'employee' && in_array($row->status, ['Pending', 'Reject']))
                         {
                              $html .= '
                              <div class="action-btn btn-info ms-2">
                                   <a href="#" data-size="md"
                                        data-url="' . url('my_visit/' . $row->id . '/edit') . '"
                                        data-ajax-popup="true" data-title="' . __("Edit visit") . '"
                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="' . __("Edit visit") . '">
                                        <i class="ti ti-pencil text-white"></i>
                                   </a>
                              </div>';
                         }

                         // Optional: Uncomment if you want to allow delete when not approved
                         /*
                         if (($user->can('Delete Visit Request') || $user->type === 'employee') && $row->status !== 'Approved') {
                             $html .= '
                                 <div class="action-btn bg-danger ms-2">
                                     <form method="POST" action="' . route('my_visit.destroy', $row->id) . '" style="display:inline;">
                                         ' . csrf_field() . method_field('DELETE') . '
                                         <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                             data-bs-toggle="tooltip" data-bs-placement="top" title="' . __("Delete") . '">
                                             <span class="text-white"> <i class="ti ti-trash"></i></span>
                                         </a>
                                     </form>
                                 </div>';
                         }
                         */

                         $html .= '</span>';
                         return $html;
                    })

                    ->rawColumns(['employee_id', 'employee', 'visit_type', 'visit_name', 'from_date', 'to_date', 'tier', 'city', 'descraption', 'status', 'approved_rejected_by', 'action'])
                    ->make(true);
          }
          else
          {
               $data = $data->get();
               return view("Myvisit.my_visit_request", compact('data'));
          }
     }

     public function index()
     {
          $data = MyVisitModel::with('tierData', 'cityData', 'employee');
          // if (Auth::user()->type == 'employee') {
          //     $employee = Employee::where('user_id', Auth::id())->first();
          //     $data->where('userid', '=', $employee->id);
          // } else {
          //     $data->whereIn('userid', selectedEmployee());
          // }

          $employee = Employee::where('user_id', Auth::id())->first();
          if ($employee != null)
          {
               $data->where('userid', '=', $employee->id);
          }
          $data = $data->where('created_by', Auth::user()->creatorId())->orderBy('id', 'desc')->get();
          return view("Myvisit.index", compact('data'));
     }

     public function create()
     {
          return view("Myvisit.create");
     }

     public function store(Request $request)
     {
          $validator = Validator::make(
               $request->all(),
               [
                    'visit_type'    => 'required',
                    'visit_name'    => 'required',
                    'form_date'     => 'required',
                    'to_date'       => 'required',
                    'description'        => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               return response()->json(['status' => 0, 'message' => $messages]);
               return redirect()->back()->with('error', $messages->first());
          }

          try
          {
               DB::beginTransaction();
               $employee = Employee::where('user_id', Auth::id())->first();

               $data = new MyVisitModel();
               $data->visit_type   = $request->visit_type;
               $data->visit_name   = $request->visit_name;
               $data->form_date    = $request->form_date;
               $data->to_date      = $request->to_date;
               $data->status       = 'Pending';
               $data->userid       = $employee->id;
               $data->description  = $request->description;
               $data->tier_id      = $request->tier_id;
               $data->city_id      = $request->city_id;
               $data->created_by = Auth::user()->creatorId();
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
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = $employee->name . " has requested for visit";
                                   $notification->details      = $employee->name . " has requested for visit";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'my_visit';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification

               DB::commit();
               return response()->json(['status' => 1, 'message' => __('Visit successfully created.')]);
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
          //
     }


     public function edit($id)
     {
          $data = MyVisitModel::find($id);
          return view("Myvisit.edit", compact('data'));
     }

     public function update(Request $request, $id)
     {
          $validator = Validator::make(
               $request->all(),
               [
                    'visit_type'    => 'required',
                    'visit_name'    => 'required',
                    'form_date'     => 'required',
                    'to_date'       => 'required',
                    'description'   => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               return response()->json(['status' => 0, 'message' => $messages]);
               return redirect()->back()->with('error', $messages->first());
          }
          try
          {
               DB::beginTransaction();
               $data = MyVisitModel::find($id);
               $data->visit_type   = $request->visit_type;
               $data->visit_name   = $request->visit_name;
               $data->form_date    = $request->form_date;
               $data->to_date      = $request->to_date;
               $data->description  = $request->description;
               $data->status       = 'Pending';


               $tier_id = $request->tier_id ?? 0;
               $city_id = $request->city_id ?? 0;

               if (!empty($tier_id))
               {
                    $data->tier_id  = $tier_id;
               }
               if (!empty($city_id))
               {
                    $data->city_id  = $city_id;
               }


               $data->created_by = Auth::user()->creatorId();
               $data->save();

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
                                   $notification->title        = $employee->name . " has updated the request for visit";
                                   $notification->details      = $employee->name . " has updated the request for visit";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'my_visit';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               return response()->json(['status' => 1, 'message' => __('Visit successfully updated.')]);
               return redirect()->route('my_visit.index')->with('success', __('Visit successfully updated.'));
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

     public function destroy($id)
     {
          $data = MyVisitModel::find($id);
          $data->delete();
          return redirect()->route('my_visit.index')->with('success', __('Visit successfully deleted.'));
     }

     public function action_hr($id)
     {
          $data       = MyVisitModel::with('tierData', 'cityData')->find($id);
          $employee   = Employee::where('id', $data->userid)->first();
          return view("Myvisit.action_hr", compact('data', 'employee'));
     }
     function changeaction_hr(request $request)
     {
          try
          {
               DB::beginTransaction();
               $data = MyVisitModel::find($request->visit_id);
               $employeeData   = Employee::find($data->userid);
               if ($request->visit_status == 'Reject')
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'reject_reason'    => 'required',
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
                    $visit_status = "Reject";
                    $title = "Your Visit request has been rejected";
                    $details = "Your Visit request has been rejected";
               }
               else
               {
                    $visit_status = "Approved";
                    $title = "Your Visit request has been approved";
                    $details = "Your Visit request has been approved";
               }
               $data->status = $visit_status;
               $data->status_changed_by = Auth::id();
               $data->reject_reason = $request->reject_reason;
               $data->save();
               $test = NotificationModel::create([
                    'user_id' => $employeeData->user_id,
                    'employee_id' => Auth::id(),  //sender_user_id
                    'title' => $title,
                    'details' => $details,
                    'created_by' => $employeeData->created_by,
                    'date' => Carbon::now(),
                    'status' => 0,
                    'redirect'   => 'my_visit',
               ]);

               // $users = User::find($employeeData->user_id);
               // $user_token = $users->device_token;
               // if ($user_token) {
               //      $title = $title;
               //      $body = $details;
               //      $click_action = 'visit_action';

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
               if (in_array($visit_status, ["Reject", "Approved"]))
               {
                    $usersData = User::where("id", $employeeData->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $statusArray = [
                              "Reject" => "Rejected",
                              "Approved" => "Approved",
                         ];

                         $title = __("Your Visit request has been " . $statusArray[$visit_status]);
                         $body = __("Your Visit request has been " . $statusArray[$visit_status]);

                         $click_action = "visit_status_changed";
                         $extraData = ["id" => $data->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
               // Send Push Notification to App

               DB::commit();
               return response()->json(['status' => 1, 'message' => __('Visit status successfully updated.')]);
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

     public function get_visitCitiesData(request $request)
     {
          $employeeData   = Employee::where('user_id', Auth::id())->first();
          $data           = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->where('created_by', Auth::user()->creatorId())->first();

          $datatier = [];
          if ($data != null)
          {
               $datatier = GradeTierData::where('grade_id', $data->id)->groupBy('tier_id')->orderBy('id', 'asc')->get();
          }
          $tierData = [];
          foreach ($datatier as $row)
          {
               $tierData[] = CityTier::find($row->tier_id);
          }
          return $tierData;
     }

     public function get_tierCitiesData(request $request)
     {
          $citytier = CityTier::find($request->getTierid);
          $citydata = DB::table('city')->whereIn('id', $citytier->citysid)->get();
          return $citydata;
     }
}
