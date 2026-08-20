<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Mail\TicketSend;
use App\Models\NotificationModel;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use DB;
use App\Models\Utility;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TicketsController extends Controller
{
     public function get_tickets(request $request)
     {
          try
          {
               $countTicketData = Ticket::where('employee_id', '=', Auth::user()->id)->orWhere('ticket_created', Auth::user()->id)->get();

               $countTicket      = $countTicketData->count();
               $countOpenTicket  = $countTicketData->where('status', '=', 'open')->count();
               $countonholdTicket  = $countTicketData->where('status', '=', 'onhold')->count();
               $countCloseTicket = $countTicketData->where('status', '=', 'close')->count();

               $data = Ticket::select("*", "created_at as created_at_formatted", "updated_at as updated_at_formatted")->where('employee_id', '=', \Auth::user()->id)->orWhere('ticket_created', \Auth::user()->id)->orderBy('id', 'desc')->get();
               if (!empty($data))
               {
                    foreach ($data as $row)
                    {
                         $row->created_by_name = !empty(\Auth::user()->getUser($row->employee_id)) ? \Auth::user()->getUser($row->employee_id)->name : '';
                         $row->end_date = \Auth::user()->dateFormat($row->end_date);
                         $row->ticket_read  = $row->ticketUnread();
                    }
                    $response = [
                         'status' => 1,
                         'message' => 'success',
                         'data'    => [
                              'tickets' => $data,
                              'countTicket' => $countTicket,
                              'countOpenTicket' => $countOpenTicket,
                              'countonholdTicket' => $countonholdTicket,
                              'countCloseTicket' => $countCloseTicket,
                         ]
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data Not Found',
                    ];
               }
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }

     public function create_ticket(request $request)
     {
          try
          {
               DB::beginTransaction();
               $rand          = date('hms');
               $ticket        = new Ticket();
               $ticket->title = $request->title;

               $ticket->employee_id = \Auth::user()->id;

               $ticket->priority    = $request->priority;
               $ticket->end_date = date("Y-m-d");
               $ticket->ticket_code = $rand;
               $ticket->description = $request->description;

               $ticket->ticket_created = \Auth::user()->id;
               $ticket->created_by     = \Auth::user()->creatorId();
               $ticket->status         = 'open';
               $ticket->save();




               //notification Code
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

               //notification Code


               //slack
               // $setting = Utility::settings(\Auth::user()->creatorId());
               // $emp = User::where('id', $request->employee_id)->first();
               // if (isset($setting['ticket_notification']) && $setting['ticket_notification'] == 1) {
               //     $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $emp->name . ' ';
               //     Utility::send_slack_msg($msg);
               // }

               //telegram
               // $setting = Utility::settings(\Auth::user()->creatorId());
               // $emp = User::where('id', $request->employee_id)->first();
               // if (isset($setting['telegram_ticket_notification']) && $setting['telegram_ticket_notification'] == 1) {
               //     $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $emp->name . ' ';
               //     Utility::send_telegram_msg($msg);
               // }

               // twilio 
               // $setting = Utility::settings(\Auth::user()->creatorId());
               // $emp = Employee::where('id', $request->employee_id = \Auth::user()->id)->first();
               // if (isset($setting['twilio_ticket_notification']) && $setting['twilio_ticket_notification'] == 1) {
               //     $msg = ("New Support ticket created of") . ' ' . $request->priority . ' ' . __("priority for") . ' ' . $emp->name . ' ';
               //     Utility::send_twilio_msg($emp->phone,$msg);
               // }
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
                    DB::commit();
                    $response = [
                         'status' => 1,
                         'message' => __('Ticket successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),
                    ];
                    return ApiResponse($response);
               }
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __('Ticket successfully created.'),
               ];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }

     public function update_ticket(request $request)
     {
          try
          {
               DB::beginTransaction();
               $ticket = Ticket::find($request->id);
               $ticket->title = $request->title;

               // $ticket->employee_id = \Auth::user()->id;

               $ticket->priority    = $request->priority;
               $ticket->end_date    = $request->end_date;
               $ticket->description = $request->description;
               $ticket->status      = $request->status;
               $ticket->save();
               $response = [
                    'status' => 1,
                    'message' => __('Ticket successfully updated.')
               ];
               DB::commit();
               return ApiResponse($response);
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }

     public function delete_ticket(request $request)
     {
          try
          {
               DB::beginTransaction();
               $ticket = Ticket::find($request->id);
               $ticketId = TicketReply::select('id')->where('ticket_id', $ticket->id)->get()->pluck('id');
               TicketReply::whereIn('id', $ticketId)->delete();
               $ticket->delete();
               if (!empty($ticket))
               {
                    $response = [
                         'status' => 1,
                         'message' =>  __('Ticket successfully deleted.')
                    ];
                    DB::commit();
                    return ApiResponse($response);
               }
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }

     public function get_ticket_reply(request $request)
     {
          try
          {
               DB::beginTransaction();
               $ticket      = Ticket::find($request->id);
               $data = TicketReply::where('ticket_id', '=', $ticket->id)->orderBy('id', 'DESC')->get();
               if (count($data) > 0)
               {
                    foreach ($data as $row)
                    {
                         $row->time = $row->created_at->diffForHumans();
                         $row->employee_name = !empty(\Auth::user()->getUser($row->created_by)) ? \Auth::user()->getUser($row->created_by)->name : '';
                    }
                    $response = [
                         'status' => 1,
                         'message' =>  __('success.'),
                         'data'  => $data
                    ];
                    return ApiResponse($response);
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' =>  'data not found'
                    ];
                    return ApiResponse($response);
               }
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }

     public function update_ticket_status(request $request)
     {
          try
          {
               DB::beginTransaction();
               $ticketreplyRead = TicketReply::where('ticket_id', $request->ticket_id)->where('created_by', '!=', \Auth::user()->id)->update(['is_read' => '1']);
               $response = [
                    'status' => 1,
                    'message' =>  __('success')
               ];
               DB::commit();
               return ApiResponse($response);
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }
     public function create_ticket_reply(request $request)
     {
          try
          {
               DB::beginTransaction();
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
               $response = [
                    'status' => 1,
                    'message' =>  __('Ticket Reply successfully Send.')
               ];
               DB::commit();
               return ApiResponse($response);
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  error_msg();
               return ApiResponse($error_msg);
          }
     }
}
