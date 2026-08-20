<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Mail\TicketSend;
use App\Models\NotificationModel;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TicketController extends Controller
{
     public function index()
     {
          // dd(Auth::user()->type, Auth::user()->creatorId());
          try
          {

               $user = Auth::user();

               if ($user->type == 'company')
               {
                    $countTicketData      = Ticket::where('created_by', '=', Auth::user()->creatorId())->get();

                    $countTicket      = $countTicketData->count();
                    $countOpenTicket  = $countTicketData->where('status', '=', 'open')->where('created_by', '=', Auth::user()->creatorId())->count();
                    $countonholdTicket  = $countTicketData->where('status', '=', 'onhold')->where('created_by', '=', Auth::user()->creatorId())->count();
                    $countCloseTicket = $countTicketData->where('status', '=', 'close')->where('created_by', '=', Auth::user()->creatorId())->count();
               }
               else
               {
                    $countTicketData      = Ticket::where('employee_id', '=', Auth::user()->id)->orWhere('ticket_created', Auth::user()->id)->get();

                    $countTicket      = $countTicketData->count();
                    $countOpenTicket  = $countTicketData->where('status', '=', 'open')->count();
                    $countonholdTicket  = $countTicketData->where('status', '=', 'onhold')->count();
                    $countCloseTicket = $countTicketData->where('status', '=', 'close')->count();
               }

               $arr = [];
               array_push($arr, $countTicket, $countOpenTicket, $countonholdTicket, $countCloseTicket);
               $ticket_arr = json_encode($arr);

               if (Auth::user()->can('Manage Ticket'))
               {
                    if ($user->type == 'company')
                    {
                         $tickets = Ticket::select('tickets.*')->join('users', 'tickets.created_by', '=', 'users.id')->where('users.created_by', '=', Auth::user()->creatorId())->orWhere('tickets.created_by', Auth::user()->creatorId())->orderBy("id", "DESC")->get();
                    }
                    else
                    {
                         $tickets = Ticket::where('employee_id', '=', Auth::user()->id)->orWhere('ticket_created', Auth::user()->id)->orderBy("id", "DESC")->get();
                    }

                    return view('ticket.index', compact('tickets', 'countTicket', 'countOpenTicket', 'countCloseTicket', 'countonholdTicket', 'ticket_arr'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Exception | Throwable $ex)
          {
               return redirect()->back()->with('error', __('something went wrong.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Ticket'))
          {
               // $employees = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
               $employees = Employee::where('created_by', \Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');

               return view('ticket.create', compact('employees'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {
          if (\Auth::user()->can('Create Ticket'))
          {

               $validator = \Validator::make(
                    $request->all(),
                    [
                         'title' => 'required',
                         'priority' => 'required',
                         // 'end_date' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $rand          = date('hms');

               DB::beginTransaction();

               $ticket        = new Ticket();
               $ticket->title = $request->title;

               if (Auth::user()->type == "company")
               {
                    $ticket->employee_id = $request->employee_id;
               }
               else
               {
                    $ticket->employee_id = Auth::user()->id;
               }

               $ticket->priority    = $request->priority;

               // $date1 = date("Y-m-d");
               // $date2 =  $request->end_date;
               // if ($date1 > $date2)
               // {
               //      return redirect()->back()->with('error', __('Please Select Today or After Date '));
               // }
               // else
               // {
               //      $ticket->end_date = $request->end_date;
               // }

               $ticket->end_date = date("Y-m-d");

               $ticket->ticket_code = $rand;
               $ticket->description = $request->description;

               $ticket->ticket_created = \Auth::user()->id;
               $ticket->created_by     = \Auth::user()->creatorId();
               $ticket->status         = 'open';
               $ticket->save();

               //notification Code

               if (Auth::user()->type == "company") // if employee creates ticket then send notification to company admin
               {
                    $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                    // $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . " you. Ticket Code $rand";
                    $msg = ("You have received new Ticket #$rand");
                    $details = ("$employee->name have raised the Ticket #$rand");

                    $notification               = new NotificationModel();
                    $notification->user_id      = $employee->user_id;
                    $notification->employee_id  = $employee->id;
                    $notification->title        = $msg;
                    $notification->details      = $details;
                    $notification->status       = 0;
                    $notification->date         = date('d-m-Y');
                    $notification->created_by   = Auth::user()->creatorId();
                    $notification->redirect   = 'ticket';
                    $notification->save();
               }
               else
               {
                    $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

                    // $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $employee->name . ". Ticket Code $rand";
                    $msg = ("You have received new Ticket #$rand");
                    $details = ("$employee->name have raised the Ticket #$rand");

                    $notification               = new NotificationModel();
                    $notification->user_id      = Auth::user()->creatorId();
                    $notification->employee_id  = $employee->id;
                    $notification->title        = $msg;
                    $notification->details      = $details;
                    $notification->status       = 0;
                    $notification->date         = date('d-m-Y');
                    $notification->created_by   = Auth::user()->creatorId();
                    $notification->redirect   = 'ticket';
                    $notification->save();
               }

               //end notification

               DB::commit();

               //slack
               $setting = Utility::settings(\Auth::user()->creatorId());
               $emp = User::where('id', $request->employee_id)->first();
               if (isset($setting['ticket_notification']) && $setting['ticket_notification'] == 1)
               {
                    $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $emp->name . ' ';
                    Utility::send_slack_msg($msg);
               }

               //telegram
               $setting = Utility::settings(\Auth::user()->creatorId());
               $emp = User::where('id', $request->employee_id)->first();
               if (isset($setting['telegram_ticket_notification']) && $setting['telegram_ticket_notification'] == 1)
               {
                    $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $emp->name . ' ';
                    Utility::send_telegram_msg($msg);
               }

               // twilio 
               $setting = Utility::settings(\Auth::user()->creatorId());
               $emp = Employee::where('id', $request->employee_id = \Auth::user()->id)->first();
               if (isset($setting['twilio_ticket_notification']) && $setting['twilio_ticket_notification'] == 1)
               {
                    $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $emp->name . ' ';
                    Utility::send_twilio_msg($emp->phone, $msg);
               }

               $setings = Utility::settings();


               if ($setings['new_ticket'] == 1)
               {
                    $employee = Employee::where('user_id', '=', $ticket->employee_id)->first();

                    $uArr = [
                         'ticket_title' => $ticket->title,
                         'ticket_name'  => $employee->name,
                         'ticket_code' => $rand,
                         'ticket_description' => $request->description,
                    ];

                    $resp = Utility::sendEmailTemplate('new_ticket', [$employee->email], $uArr);
                    return redirect()->route('ticket.index')->with('success', __('Ticket  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
               }
               return redirect()->route('ticket.index')->with('success', __('Ticket  successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Ticket $ticket)
     {
          return redirect()->route('ticket.index');
     }

     public function edit($ticket)
     {
          $ticket = Ticket::find($ticket);
          if (\Auth::user()->can('Edit Ticket'))
          {
               // $employees = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
               $employees = Employee::where('created_by', \Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');

               return view('ticket.edit', compact('ticket', 'employees'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function update(Request $request, $ticket)
     {
          if (\Auth::user()->can('Edit Ticket'))
          {
               $validationRules = [];

               if (Auth::user()->type != "company")
               {
                    $validationRules = [
                         'priority' => 'required',
                         'end_date' => 'required',
                    ];
               }
               else
               {
                    $validationRules = [
                         'priority' => 'required',
                         'status' => 'required',
                    ];
               }

               $validator = \Validator::make(
                    $request->all(),
                    $validationRules
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }


               DB::beginTransaction();
               $ticket = Ticket::find($ticket);

               if (Auth::user()->type != "company")
               {
                    $ticket->title = $request->title;
                    // $ticket->end_date    = $request->end_date;
                    $ticket->description = $request->description;
               }

               $ticket->priority    = $request->priority;
               $ticket->status      = $request->status;
               $ticket->save();

               DB::commit();

               //notification Code

               if (Auth::user()->type == "employee") // if employee creates ticket then send notification to company admin
               {
                    $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

                    $msg = ("Ticket ($ticket->ticket_code) status has been updated to $ticket->status on $ticket->priority priority.");

                    $notification               = new NotificationModel();
                    $notification->user_id      = Auth::user()->creatorId();
                    $notification->employee_id  = $employee->id;
                    $notification->title        = $msg;
                    $notification->details      = $msg;
                    $notification->status       = 0;
                    $notification->date         = date('d-m-Y');
                    $notification->created_by   = Auth::user()->creatorId();
                    $notification->redirect   = 'ticket';
                    $notification->save();
               }
               else
               {
                    $employee = Employee::where('user_id', '=', $ticket->employee_id)->first();
                    $msg = ("Ticket ($ticket->ticket_code) status has been updated to $ticket->status on $ticket->priority priority.");

                    $notification               = new NotificationModel();
                    $notification->user_id      = $employee->user_id;
                    $notification->employee_id  = $employee->id;
                    $notification->title        = $msg;
                    $notification->details      = $msg;
                    $notification->status       = 0;
                    $notification->date         = date('d-m-Y');
                    $notification->created_by   = Auth::user()->creatorId();
                    $notification->redirect   = 'ticket';
                    $notification->save();


                    // Send Push Notification to App
                    $usersData = User::where("id", $employee->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $title = $msg;
                         $body = $msg;

                         $click_action = "ticket_status_changed";
                         $extraData = ["id" => $ticket->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
                    // Send Push Notification to App
               }

               //end notification


               return redirect()->route('ticket.index', compact('ticket'))->with('success', __('Ticket successfully updated.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function destroy(Ticket $ticket)
     {
          if (\Auth::user()->can('Delete Ticket'))
          {
               if ($ticket->created_by == \Auth::user()->creatorId())
               {
                    $ticket->delete();
                    $ticketId = TicketReply::select('id')->where('ticket_id', $ticket->id)->get()->pluck('id');
                    TicketReply::whereIn('id', $ticketId)->delete();

                    return redirect()->route('ticket.index')->with('success', __('Ticket successfully deleted.'));
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

     public function reply($ticket)
     {
          $ticketreply = TicketReply::where('ticket_id', '=', $ticket)->orderBy('id', 'DESC')->get();
          $ticket      = Ticket::find($ticket);
          if (\Auth::user()->type == 'employee')
          {
               $ticketreplyRead = TicketReply::where('ticket_id', $ticket->id)->where('created_by', '!=', \Auth::user()->id)->update(['is_read' => '1']);
          }
          else
          {
               $ticketreplyRead = TicketReply::where('ticket_id', $ticket->id)->where('created_by', '!=', \Auth::user()->creatorId())->update(['is_read' => '1']);
          }


          return view('ticket.reply', compact('ticket', 'ticketreply'));
     }

     public function changereply(Request $request)
     {

          $validator = \Validator::make(
               $request->all(),
               [
                    'description' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();

               return redirect()->back()->with('error', $messages->first());
          }

          $ticket = Ticket::find($request->ticket_id);

          $ticket_reply              = new TicketReply();
          $ticket_reply->ticket_id   = $request->ticket_id;
          $ticket_reply->employee_id = $ticket->employee_id;
          $ticket_reply->description = $request->description;
          if (\Auth::user()->type == 'employee')
          {
               $ticket_reply->created_by = Auth::user()->id;
          }
          else
          {
               $ticket_reply->created_by = Auth::user()->creatorId();
          }

          $ticket_reply->save();


          // Send Push Notification to App
          if (Auth::user()->type != "employee")
          {
               $employee = Employee::where('user_id', '=', $ticket->employee_id)->first();

               if ($employee != null)
               {
                    $usersData = User::where("id", $employee->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $title = __("Admin Has Replied to Your Ticket #$ticket->ticket_code");
                         $body = __("Admin Has Replied to Your Ticket #$ticket->ticket_code");

                         $click_action = "ticket_replied";
                         $extraData = ["id" => $ticket->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
          }
          // Send Push Notification to App

          return redirect()->route('ticket.reply', $ticket_reply->ticket_id)->with('success', __('Ticket Reply successfully Send.'));
     }
}
