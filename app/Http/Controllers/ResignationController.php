<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\Resignation;

use Illuminate\Http\Request;
use App\Mail\ResignationSend;
use App\Models\NotificationModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Form;
use Illuminate\Support\Facades\Crypt;

class ResignationController extends Controller
{
     public function index()
     {
          if (Auth::user()->can('Manage Resignation'))
          {
               if (Auth::user()->type != 'company')
               {
                    $emp          = Employee::where('user_id', '=', Auth::user()->id)->first();
                    $resignations = Resignation::where('created_by', '=', Auth::user()->creatorId())->where('employee_id', '=', $emp->id)->get();
               }
               else
               {
                    $resignations = Resignation::where('created_by', '=', Auth::user()->creatorId())->get();
               }
               return view('resignation.index', compact('resignations'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function resignation_request(Request $request)
     {
          $resignations = 0;
          $employees = Employee::where('created_by', Auth::user()->creatorId())->get();
          $resignations = Resignation::with('employee_new')->whereIn('employee_id', selectedEmployee())->where('created_by', '=', Auth::user()->creatorId())->orderBy('created_at', 'desc');
          if ($request->ajax())
          {
               $data = $resignations;
               return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('employee_id', function ($row)
                    {

                         $employee_id = !empty($row->employee_new) ? $row->employee_new->employee_id ?? '' : '';
                         return ((Auth::user()->hasPermissionTo('Show Employee')) ? '<a class="btn btn-outline-primary" href=" ' . route('employee.show', Crypt::encrypt($row->id)) . '">' . $employee_id . '</a>' : $employee_id);
                    })
                    ->filterColumn('employee', function ($query, $keyword)
                    {
                         $query->whereHas('employee_new', function ($q) use ($keyword)
                         {
                              $q->where('name', 'like', "%{$keyword}%");
                         });
                    })
                    ->filterColumn('employee_id', function ($query, $keyword)
                    {
                         $query->whereHas('employee_new', function ($q) use ($keyword)
                         {
                              $q->where('employee_id', 'like', "%{$keyword}%");
                         });
                    })
                    ->addColumn('employee', function ($row)
                    {
                         return $row->employee_new->name;
                    })
                    ->addColumn('resignation_date', function ($row)
                    {
                         return !empty($row->resignation_date) ? \Auth::user()->dateFormat($row->resignation_date) : '';
                    })
                    ->addColumn('last_working_day', function ($row)
                    {
                         return !empty($row->notice_date) ? \Auth::user()->dateFormat($row->notice_date) : '';
                    })
                    ->addColumn('reason', function ($row)
                    {
                         $short = substr($row->description, 0, 60) . '..';
                         $full = htmlspecialchars($row->description); // Escape to prevent XSS
                         return '<p title="' . $full . '">' . $short . '</p>';
                    })
                    ->addColumn('notes', function ($row)
                    {
                         $short = substr($row->notes, 0, 60) . '..';
                         $full = htmlspecialchars($row->notes); // Escape to prevent XSS
                         return '<p title="' . $full . '">' . $short . '</p>';
                    })
                    ->addColumn('status', function ($row)
                    {
                         if ($row->status == 0)
                         {
                              $status = '<span class="badge bg-primary p-2 px-3 rounded">' . __('Pending') . '</span>';
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
                         $actions = '';

                         // View Button
                         $actions .= '
                              <div class="action-btn bg-info ms-2">
                                   <a href="#" class="mx-3 btn btn-sm align-items-center"
                                        data-size="lg"
                                        data-url="' . url('resignation_request_view/' . $row->id) . '"
                                        data-ajax-popup="true" data-size="md"
                                        data-bs-toggle="tooltip" title=""
                                        data-title="Resignation View"
                                        data-bs-original-title="Resignation View">
                                        <i class="ti ti-eye text-white"></i>
                                   </a>
                              </div>';

                         // Action Button if status == 0 (Pending)
                         if (in_array($row->status, [0]))
                         {
                              $actions .= '
                         <div class="action-btn bg-success ms-2">
                              <a href="#" class="mx-3 btn btn-sm align-items-center"
                                   data-size="lg"
                                   data-url="' . url('resignation/' . $row->id . '/action') . '"
                                   data-ajax-popup="true" data-size="md"
                                   data-bs-toggle="tooltip" title=""
                                   data-title="Resignation Action"
                                   data-bs-original-title="Manage Resignation">
                                   <i class="ti ti-caret-right text-white"></i>
                              </a>
                         </div>';
                         }

                         // Exit Interview Button if status == 1 (Approved)
                         if (in_array($row->status, [1]))
                         {
                              $actions .= '
                              <div class="action-btn bg-primary ms-2">
                                   <a href="#" class="mx-3 btn btn-sm align-items-center"
                                        data-size="lg"
                                        data-url="' . url('resignation/' . $row->id . '/exit_inerview') . '"
                                        data-ajax-popup="true" data-size="md"
                                        data-bs-toggle="tooltip" title=""
                                        data-title="Exit Interview"
                                        data-bs-original-title="Exit Interview">
                                        <i class="ti ti-user text-white"></i>
                                   </a>
                              </div>';
                         }

                         // Delete Button (check permission + status == 0)
                         if (Auth::user()->can('Delete Resignation') && in_array($row->status, [0]))
                         {
                              $formId = 'delete-form-' . $row->id;
                              $deleteRoute = route('resignation.destroy', $row->id);

                              $actions .= '
                                   <div class="action-btn bg-danger ms-2">
                                        <form method="POST" action="' . $deleteRoute . '" id="' . $formId . '" style="display:inline;">
                                             ' . csrf_field() . method_field('DELETE') . '
                                             <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                  data-bs-toggle="tooltip" title=""
                                                  data-bs-original-title="Delete" aria-label="Delete">
                                                  <i class="ti ti-trash text-white"></i>
                                             </a>
                                        </form>
                                   </div>';
                         }

                         return $actions;
                    })
                    ->rawColumns(['employee_id', 'status', 'action', 'reason', 'notes'])
                    ->make(true);
          }
          else
          {
               return view('resignation.resignation_request', compact('resignations'));
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Resignation'))
          {
               $employees = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

               return view('resignation.create', compact('employees'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {
          if (Auth::user()->can('Create Resignation'))
          {

               $validator = Validator::make(
                    $request->all(),
                    [
                         'description' => 'required',
                         'resignation_date' => 'required|date_format:Y-m-d',
                         // 'notice_date' => 'required|date_format:Y-m-d|before_or_equal:resignation_date',
                         // 'resignation_date' => 'required|date_format:Y-m-d|after_or_equal:notice_date'

                    ],
                    [
                         "description.required" => __("Reason field is required.")
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    // return response()->json(['status' => 0,'message' => $messages]);
                    return redirect()->back()->with('error', $messages->first());
               }

               DB::beginTransaction();
               try
               {
                    $resignation = new Resignation();
                    $user        = Auth::user();
                    $employee    = Employee::where('user_id', $user->id)->first();

                    $superior_user = null;

                    if ($employee)
                    {
                         $superior_user = $employee->users_types_id;
                    }

                    $resignation->employee_id      = $employee->id;
                    // $resignation->superior_user  = $superior_user;
                    // $resignation->notice_date      = $request->notice_date;
                    $resignation->resignation_date = date('Y-m-d');
                    $resignation->description      = $request->description;
                    $resignation->status           = 0;
                    $resignation->created_by       = Auth::user()->creatorId();
                    $resignation->save();

                    $setings = Utility::settings();

                    if ($setings['employee_resignation'] == 1)
                    {
                         $employee           = Employee::find($resignation->employee_id);
                         $resignation_email_notification = Utility::getValByName('resignation_email_notification') ?? '';
                         if (!empty($resignation_email_notification))
                         {
                              $resignation_email = explode(',', $resignation_email_notification);

                              foreach ($resignation_email as $email)
                              {
                                   $uArr = [
                                        'resigned_user' => $employee->name,
                                        'resignation_date'  => date('d-m-Y'),
                                        'notice_date' => '',
                                   ];
                                   $resp = Utility::sendEmailTemplate('employee_resignation', [$email], $uArr);
                              }
                         }

                         $uArr = [
                              'assign_user' => $employee->name,
                              'resignation_date'  => date('Y-m-d'),
                              // 'resignation_date'  => $request->notice_date,
                              'notice_date' => '',
                         ];

                         $resp1 = Utility::sendEmailTemplate('employee_resignation', [$employee->email], $uArr);
                         $user = User::find($employee->created_by);

                         $uArr = [
                              'assign_user' => $user->name,
                              'resignation_date'  => date('Y-m-d'),
                              'notice_date' => '',
                         ];

                         $resp = Utility::sendEmailTemplate('employee_resignation', [$user->email], $uArr);

                         // dd($resp1, $resp);

                         //notification Code
                         $employee = Employee::where('user_id', '=', Auth::id())->first();
                         $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
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


                                             $notification->title        = "You have received a resignation request from $employee->name.";
                                             $notification->details      = "You have received a resignation request from $employee->name.";
                                             $notification->status       = 0;
                                             $notification->date         = Carbon::now();
                                             $notification->created_by   = $employee->created_by;
                                             $notification->redirect     = 'resignation';
                                             $notification->save();
                                        }
                                   }
                              }
                         }
                         //end notification
                         DB::commit();
                         return redirect()->route('resignation.index')->with('success', __('Resignation  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                    }
                    DB::commit();
                    return redirect()->route('resignation.index')->with('success', __('Resignation  successfully created.'));
               }
               catch (Exception $e)
               {
                    DB::rollBack();
                    return redirect()->route('termination.index')->with('success', showExceptionMessage($e));
               }
               catch (Throwable $e)
               {
                    DB::rollBack();
                    return redirect()->route('termination.index')->with('success', showExceptionMessage($e));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Resignation $resignation)
     {
          return redirect()->route('resignation.index');
     }

     public function edit(Resignation $resignation)
     {
          if (Auth::user()->can('Edit Resignation'))
          {
               $employees = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
               if ($resignation->created_by == Auth::user()->creatorId())
               {
                    return view('resignation.edit', compact('resignation', 'employees'));
               }
               else
               {
                    return response()->json(['error' => __('Permission denied.')], 401);
               }
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function update(Request $request, Resignation $resignation)
     {
          if (Auth::user()->can('Edit Resignation'))
          {
               if ($resignation->created_by == Auth::user()->creatorId())
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              // 'notice_date' => 'required',
                              // 'resignation_date' => 'required',
                              // 'notice_date' => 'required|date_format:Y-m-d|before_or_equal:resignation_date',
                              // 'resignation_date' => 'required|date_format:Y-m-d|after_or_equal:notice_date'

                              'description' => 'required',
                              'resignation_date' => 'required|date_format:Y-m-d',
                              // 'notice_date' => 'required|date_format:Y-m-d|before_or_equal:resignation_date',
                              // 'resignation_date' => 'required|date_format:Y-m-d|after_or_equal:notice_date'
                         ],
                         [
                              "description.required" => __("Reason field is required.")
                         ]
                    );

                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();
                         // return response()->json(['status' => 0,'message' => $messages]);
                         return redirect()->back()->with('error', $messages->first());
                    }

                    DB::beginTransaction();
                    try
                    {
                         // if(Auth::user()->type != 'employee')
                         // {
                         //     $resignation->employee_id = $request->employee_id;
                         // }
                         // $resignation->notice_date      = $request->notice_date;
                         $resignation->resignation_date = $request->resignation_date;
                         $resignation->description      = $request->description;
                         $resignation->save();
                         DB::commit();
                         return redirect()->route('resignation.index')->with('success', __('Resignation successfully updated.'));
                    }
                    catch (Exception $e)
                    {
                         DB::rollBack();
                         return redirect()->route('termination.index')->with('success', showExceptionMessage($e));
                    }
                    catch (Throwable $e)
                    {
                         DB::rollBack();
                         return redirect()->route('termination.index')->with('success', showExceptionMessage($e));
                    }
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function destroy(Resignation $resignation)
     {
          if (Auth::user()->can('Delete Resignation'))
          {
               if ($resignation->created_by == Auth::user()->creatorId())
               {
                    $resignation->delete();

                    return redirect()->route('resignation.index')->with('success', __('Resignation successfully deleted.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function resignation_request_view($id)
     {
          $data = Resignation::find($id);
          return view('resignation.resignation_request_view', compact('data'));
     }

     public function action($id)
     {
          $data = Resignation::find($id);
          return view('resignation.action', compact('data'));
     }

     public function actionupdate(request $request, $id)
     {
          if ($request->status == 'Approved')
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'notice_date' => 'required|date_format:Y-m-d',
                         'description' => 'required',

                    ],
                    [
                         "notice_date.required" => __("Last working day field is required"),
                         "description.required" => __("Note field is required.")
                    ]
               );
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'description' => 'required',
                    ],
                    [
                         "description.required" => __("Note field is required.")
                    ]
               );
          }
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }

          DB::beginTransaction();
          try
          {
               if ($request->status == 'Approved')
               {
                    $statuscheck  = 'Approved';
                    $check  = 1;
               }
               else
               {
                    $statuscheck  = 'Reject';
                    $check        = 2;
               }
               $data = Resignation::find($id);

               $employee = Employee::find($data->employee_id);

               $data->status = $check;
               $data->notice_date      = $request->notice_date;
               $data->notes  = $request->description;
               $data->save();
               if ($request->status == 'Approved')
               {
                    $employee->employe_status = 'Resigned';
                    $employee->save();
               }


               $email = $employee->email;
               DB::commit();
               try
               {
                    // Mail::send(
                    //      ['html' => 'system_emails.employee_resignation'],
                    //      array(
                    //           'content' => $request->description,
                    //           'statuscheck'   => $statuscheck,
                    //           'employee'      => $employee,
                    //           'data'          => $data
                    //      ),
                    //      function ($message) use ($email, $statuscheck)
                    //      {
                    //           $message->from(env('MAIL_USERNAME'), 'Resignation ' . $statuscheck);
                    //           $message->to($email)->subject("Resignation " . $statuscheck);
                    //      }
                    // );
               }
               catch (Exception $e)
               {
                    // return redirect()->back()->with('error', showExceptionMessage($e));
               }
               catch (Throwable $e)
               {
                    // return redirect()->back()->with('error', showExceptionMessage($e));
               }

               // dd("here");



               // Send Push Notification to App

               if (in_array($statuscheck, ["Reject", "Rejected", "Approved"]))
               {
                    $usersData = User::where("id", $employee->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    // dd($usersData, $employee);

                    if (!$usersData->isEmpty())
                    {
                         $statusArray = [
                              "Rejected" => "Rejected",
                              "Reject" => "Rejected",
                              "Approved" => "Approved",
                         ];

                         $approvedBy = Employee::where("user_id", Auth::user()->id)->first();

                         $title = "$approvedBy->name has " . $statusArray[$statuscheck] . " your resignation.";
                         $body = "$approvedBy->name has " . $statusArray[$statuscheck] . " your resignation.";

                         $click_action = "resign_status_changed";
                         $extraData = ["id" => $data->id];

                         $data = sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
               // Send Push Notification to App





               return redirect()->route('resignation_request')->with('success', __('Resignation successfully ' . $statuscheck));
          }
          catch (Exception $e)
          {
               // dd($e);
               DB::rollBack();
               return redirect()->back()->with('error', showExceptionMessage($e));
          }
          catch (Throwable $e)
          {
               // dd($e);
               DB::rollBack();
               return redirect()->back()->with('error', showExceptionMessage($e));
          }
     }

     public function exit_inerview($id)
     {
          $data = Resignation::find($id);
          return view('resignation.exit_inerview', compact('id', 'data'));
     }

     public function exit_inerviewupdate(request $request, $id)
     {
          $data = Resignation::find($id);
          $data->exit_notes = $request->description;
          $data->save();
          return redirect()->route('resignation_request')->with('success', __('Resignation successfully exit notes created'));
     }
}
