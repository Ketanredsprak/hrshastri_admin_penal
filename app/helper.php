<?php // Code within app\Helpers\Helper.php

use App\Models\AllowanceOption;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\NotificationModel;
use App\Models\Resignation;
use App\Models\User;
use App\Models\Leave;
use App\Models\ApplyWFM;
use App\Models\GetPass;
use App\Models\District;
use App\Models\MyVisitModel;
use App\Models\MyClaimModel;
use App\Models\Country;
use App\Models\State;
use App\Models\EarlyLateCommingLeave;
use App\Models\AttendanceEmployee;
use App\Models\Commission_option;
use App\Models\LeaveType;
use App\Models\UserLeaveBalance;
use App\Models\UserLeaveBalanceYear;
use App\Models\Utility;
use App\Models\WorkingShiftEmployees;
use App\Models\WorkingShifts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Facades\Crypt;

function selectedEmployee()
{
     $user = User::find(Auth::id());
     $selectemployees = [];
     if (!empty($user->assign_employees))
     {
          $selectemployees = json_decode($user->assign_employees);
     }

     if (is_null($selectemployees))
     {
          $selectemployees = [];
     }
     return $selectemployees;
}

function findEmployeeByUser($id)
{
     $employee = Employee::where('user_id', $id)->first();
     if (!empty($employee))
     {
          return $employee->name;
     }
     return '';
}

function paginate(object $getData, $page_no, $no_of_data = 10)
{
     $status = 1;
     $message = 'success';
     $response_code = 200;
     $data = [];
     $response = [];

     if (!empty($page_no))
     {
          $order_total = $getData->count();
          $offset = ($page_no * $no_of_data) - $no_of_data;
          $getData = $getData->offset($offset)->limit($no_of_data)->get();
          $page_limit = ceil($order_total / $no_of_data);

          $data  = $getData;
          $response = [
               'status'    => $status,
               'message'   => $message,
               'data'      => $data,
               'total_data' => $order_total,
               'total_page' =>  $page_limit,
               'per_page'  =>  $no_of_data
          ];
     }
     else
     {
          $getData = $getData->get();
          $data  = $getData;
          $response = [
               'status'    => $status,
               'message'   => $message,
               'data'      => $data,
          ];
     }
     if (count($getData) != 0)
     {
          return $response;
     }
     else
     {
          $status = 0;
          $message = 'No Data Found';
          $response_code = 200;
          $response = [
               'status' => $status,
               'message' => $message,
               'data'      => [],
          ];
          return $response;
     }
}

function ApiResponse($response, $response_code = 200)
{
     if (empty($response['data']))
     {
     }
     return response()->json($response, $response_code);
}

function error_msg($ex = '')
{
     $response = [
          'status' => 0,
          'message' => ($ex == "" ? trans('data not found') : $ex),
          'data' => []
     ];
     return $response;
}

//BirthDay Employee
function b_day()
{
     $employeesBithday = Employee::whereDay('dob', Carbon::now())->whereMonth('dob', Carbon::now())->where('created_by', \Auth::user()->creatorId())->count();
     return $employeesBithday;
}
function work_anivarsary()
{
     $total_anivaersry = Employee::whereDay('company_doj', Carbon::now())->whereMonth('company_doj', Carbon::now())->where('created_by', \Auth::user()->creatorId())->count();
     return $total_anivaersry;
}

//count Bday notification 
function bdaynotification()
{
     return NotificationModel::where('user_id', Auth::id())->where('status', 0)->where('created_by', \Auth::user()->creatorId())->get()->count();

     if (\Auth::user()->type == 'company') //company wise
     {
          // return NotificationModel::where('created_by',\Auth::user()->creatorId())->groupBy('employee_id')->get()->count();
     }
     else
     {
          $daya =  NotificationModel::where('user_id', Auth::id())->where('status', 0)->where('created_by', \Auth::user()->creatorId())->count();
          return $daya;
     }
}

function employeeBdayGet()
{
     return NotificationModel::where('user_id', Auth::id())->where('status', 0)->where('created_by', \Auth::user()->creatorId())->orderBy('id', 'DESC')->get();

     if (\Auth::user()->type == 'company') //company wise
     {
          // $data = NotificationModel::where('created_by', \Auth::user()->creatorId())->groupBy('employee_id')->orderBy('id', 'DESC')->get();
     }
     else
     {
          $data = NotificationModel::where('user_id', Auth::id())->where('status', 0)->where('created_by', \Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
          return $data;
     }
}

function countresignationApply()
{
     $data = Employee::where('user_id', Auth::id())->first();
     if (!empty($data))
     {
          return  Resignation::where('employee_id', $data->id)->where('status', 1)->where('created_by', \Auth::user()->creatorId())->get()->count();
     }
}

//new coe sen 
function company_flexi_hours()
{
     $data = DB::table('settings')->where('name', 'company_flexi_hours')->where('created_by', \Auth::user()->creatorId())->first();
     $value = 00;
     if (!empty($data))
     {
          $value = $data->value;
     }
     return $value;
}

function company_flexi_days()
{
     $data = DB::table('settings')->where('name', 'company_flexi_days')->where('created_by', \Auth::user()->creatorId())->first();
     $value = 00;
     if (!empty($data))
     {
          $value = $data->value;
     }
     return $value;
}
function get_flaxihourescount()
{
     // 16:27:30 in
     $emp = Employee::where('user_id', Auth::id())->first();
     $attendanceEmployee = $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp->id);
     $month      = date('m');
     $year       = date('Y');
     $start_date = date($year . '-' . $month . '-01');
     $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));
     $attendanceEmployee->whereTime('clock_in', '>', company_flexi_hours())->whereBetween('date', [$start_date, $end_date,]);
     $attendanceEmployee = $attendanceEmployee->count();
     return $attendanceEmployee;
}

function get_employee_flaxihourescount($id)
{

     // 16:27:30 in
     $emp = $id;
     $attendanceEmployee = $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp);
     $month      = date('m');
     $year       = date('Y');
     $start_date = date($year . '-' . $month . '-01');
     $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));
     $attendanceEmployee->whereTime('clock_in', '>', company_flexi_hours())->whereBetween('date', [$start_date, $end_date]);
     $attendanceEmployee = $attendanceEmployee->count();
     return $attendanceEmployee;
}


function redirectUrl($obj)
{
     $url = '';


     if ($obj->redirect == 'my_visit')
     {
          if (Auth::user()->type == "employee")
          {
               $url = route('my_visit.index');
          }
          else
          {
               $url = route('my_visit_request');
          }
     }
     else if ($obj->redirect == 'my_claim')
     {
          if (Auth::user()->type == "employee")
          {
               $url = route('my_claim.index');
          }
          else
          {
               $url = route('my_claim_request');
          }
     }

     // if($obj->redirect == 'my_visit'){
     //     $url = route('my_visit.index');
     // }else if($obj->redirect == 'my_claim'){
     //     $url = route('my_claim.index');
     // }
     else if ($obj->redirect == 'applywfm')
     {
          $url = route('applywfm_request');
     }
     else if ($obj->redirect == 'applywfm_employee')
     {
          $url = route('applywfm.index');
     }
     else if ($obj->redirect == 'earlyandlate_leave')
     {
          $url = route('earlyandlate_leave_request');
     }
     else if ($obj->redirect == 'earlyandlate_leave_employee')
     {
          $url = route('earlyandlate_leave.index');
     }
     else if ($obj->redirect == 'employee')
     {
          $url = route('employee.index');
     }
     else if ($obj->redirect == 'employee birthday')
     {
          $url = route('employee_birthday', $obj->id);
     }
     else if ($obj->redirect == 'employee work anniversary')
     {
          $url = route('employee_birthday', $obj->id);
     }
     else if ($obj->redirect == 'getpass')
     {
          $url = route('getpass_request');
     }
     else if ($obj->redirect == 'getpass_employee')
     {
          $url = route('getpass.index');
     }
     else if ($obj->redirect == 'leave')
     {
          $url = route('leaverequest');
     }
     else if ($obj->redirect == 'leave_employee')
     {
          $url = route('leave.index');
     }
     else if ($obj->redirect == 'resignation')
     {
          $url = route('resignation_request');
     }
     else if ($obj->redirect == 'complaint')
     {
          $url = route('complaint.index');
     }
     else if ($obj->redirect == 'ticket')
     {
          $url = route('ticket.index');
     }
     else
     {
          $url = route('employee_birthday', Crypt::encryptString($obj->id));
     }
     return $url;
}

function redirectUrlBk($obj)
{
     $url = '';

     if ($obj->redirect == 'my_visit')
     {
          if (Auth::user()->type == "employee")
          {
               $url = route('my_visit.index');
          }
          else
          {
               $url = route('my_visit_request');
          }
     }
     else if ($obj->redirect == 'my_claim')
     {
          if (Auth::user()->type == "employee")
          {
               $url = route('my_claim.index');
          }
          else
          {
               $url = route('my_claim_request');
          }
     }
     else if ($obj->redirect == 'applywfm')
     {
          $url = route('applywfm_request');
     }
     else if ($obj->redirect == 'earlyandlate_leave')
     {
          $url = route('earlyandlate_leave_request');
     }
     else if ($obj->redirect == 'employee')
     {
          $url = route('employee.index');
     }
     else if ($obj->redirect == 'employee birthday')
     {
          $url = route('employee_birthday', $obj->id);
     }
     else if ($obj->redirect == 'employee work anniversary')
     {
          $url = route('employee_birthday', $obj->id);
     }
     else if ($obj->redirect == 'getpass')
     {
          $url = route('getpass_request');
     }
     else if ($obj->redirect == 'leave')
     {
          $url = route('leaverequest');
     }
     else if ($obj->redirect == 'resignation')
     {
          $url = route('resignation_request');
     }
     else
     {
          $url = route('employee_birthday', $obj->id);
     }
     return $url;
}

function get_employee_id()
{
     if (Auth::user()->type != 'company')
     {
          $data = Employee::where('user_id', Auth::id())->first();
          return $data->id;
     }
     else
     {
          return Auth::user()->id;
     }
}
//leave
function leave_approved()
{
     if (Auth::user()->type == 'company')
     {
          return Leave::where('status', 'Approved')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return Leave::where('status', 'Approved')->where('employee_id', get_employee_id())->get()->count();
     }
}
function leave_rejected()
{
     if (Auth::user()->type == 'company')
     {
          return Leave::where('status', 'Reject')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return Leave::where('status', 'Reject')->where('employee_id', get_employee_id())->get()->count();
     }
}
function leave_pending()
{
     if (Auth::user()->type == 'company')
     {
          return Leave::where('status', 'Pending')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return Leave::where('status', 'Pending')->where('employee_id', get_employee_id())->get()->count();
     }
}
function re_leave_pending()
{
     return Leave::where('status', 'Pending')->whereIn('employee_id', selectedEmployee())->get()->count();
}
function re_leave_approved()
{
     return Leave::where('status', 'Approved')->whereIn('employee_id', selectedEmployee())->get()->count();
}
function re_leave_rejected()
{
     return Leave::where('status', 'Reject')->whereIn('employee_id', selectedEmployee())->get()->count();
}




//Apply WFH
function applywfh_pending()
{
     if (Auth::user()->type == 'company')
     {
          return ApplyWFM::where('status', '0')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return ApplyWFM::where('status', '0')->where('employee_id', get_employee_id())->get()->count();
     }
}
function applywfh_approved()
{
     if (Auth::user()->type == 'company')
     {
          return ApplyWFM::where('status', '1')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return ApplyWFM::where('status', '1')->where('employee_id', get_employee_id())->get()->count();
     }
}
function applywfh_rejected()
{
     if (Auth::user()->type == 'company')
     {
          return ApplyWFM::where('status', '2')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return ApplyWFM::where('status', '2')->where('employee_id', get_employee_id())->get()->count();
     }
}
function re_applywfh_pending()
{
     return ApplyWFM::where('status', '0')->whereIn('employee_id', selectedEmployee())->get()->count();
}
function re_applywfh_approved()
{
     return ApplyWFM::where('status', '1')->whereIn('employee_id', selectedEmployee())->get()->count();
}
function re_applywfh_rejected()
{
     return ApplyWFM::where('status', '2')->whereIn('employee_id', selectedEmployee())->get()->count();
}


//Earlyandlate Leave
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function earlyandlate_leave_pending()
{
     if (Auth::user()->type == 'company')
     {
          return EarlyLateCommingLeave::where('status', '0')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return EarlyLateCommingLeave::where('status', '0')->where('employee_id', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function earlyandlate_leave_approved()
{
     if (Auth::user()->type == 'company')
     {
          return EarlyLateCommingLeave::where('status', '1')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return EarlyLateCommingLeave::where('status', '1')->where('employee_id', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function earlyandlate_leave_rejected()
{
     if (Auth::user()->type == 'company')
     {
          return EarlyLateCommingLeave::where('status', '2')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return EarlyLateCommingLeave::where('status', '2')->where('employee_id', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function re_earlyandlate_leave_pending()
{
     return EarlyLateCommingLeave::where('status', '0')->whereIn('employee_id', selectedEmployee())->get()->count();
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function re_earlyandlate_leave_approved()
{
     return EarlyLateCommingLeave::where('status', '1')->whereIn('employee_id', selectedEmployee())->get()->count();
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function re_earlyandlate_leave_rejected()
{
     return EarlyLateCommingLeave::where('status', '2')->whereIn('employee_id', selectedEmployee())->get()->count();
}

//Gate Pass
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function gate_pass_pending()
{
     if (Auth::user()->type == 'company')
     {
          return GetPass::where('status', '0')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return GetPass::where('status', '0')->where('employee_id', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function gate_pass_approved()
{
     if (Auth::user()->type == 'company')
     {
          return GetPass::where('status', '1')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return GetPass::where('status', '1')->where('employee_id', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function gate_pass_rejected()
{
     if (Auth::user()->type == 'company')
     {
          return GetPass::where('status', '2')->where('employee_id', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return GetPass::where('status', '2')->where('employee_id', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function re_gate_pass_pending()
{
     return GetPass::where('status', '0')->whereIn('employee_id', selectedEmployee())->get()->count();
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function re_gate_pass_approved()
{
     return GetPass::where('status', '1')->whereIn('employee_id', selectedEmployee())->get()->count();
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function re_gate_pass_rejected()
{
     return GetPass::where('status', '2')->whereIn('employee_id', selectedEmployee())->get()->count();
}

//My Visit
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function visit_pending()
{
     $count = MyVisitModel::where('status', 'Pending');
     if (Auth::user()->type == 'company')
     {
          return MyVisitModel::where('status', 'Pending')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return MyVisitModel::where('status', 'Pending')->where('userid', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function visit_approved()
{
     if (Auth::user()->type == 'company')
     {
          return MyVisitModel::where('status', 'Approved')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return MyVisitModel::where('status', 'Approved')->where('userid', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function visit_rejected()
{
     if (Auth::user()->type == 'company')
     {
          return MyVisitModel::where('status', 'Reject')->where('employee_id', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return MyVisitModel::where('status', 'Reject')->where('userid', get_employee_id())->get()->count();
     }
}
function re_visit_pending()
{
     return MyVisitModel::where('status', 'Pennding')->whereIn('userid', selectedEmployee())->get()->count();
}
function re_visit_approved()
{
     return MyVisitModel::where('status', 'Approved')->whereIn('userid', selectedEmployee())->get()->count();
}
function re_visit_rejected()
{
     return MyVisitModel::where('status', 'Reject')->whereIn('userid', selectedEmployee())->get()->count();
}

//cliam
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function claim_pending()
{
     if (Auth::user()->type == 'company')
     {
          return MyClaimModel::where('status', 'Pennding')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return MyClaimModel::where('status', 'Pennding')->where('userid', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function claim_approved()
{
     if (Auth::user()->type == 'company')
     {
          return MyClaimModel::where('status', 'Approved')->where('created_by', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return MyClaimModel::where('status', 'Approved')->where('userid', get_employee_id())->get()->count();
     }
}
//vikas comment :- this was used in my_visit_request blade file i removed it. (so i dont think this is used anywhere else)
function claim_rejected()
{
     if (Auth::user()->type == 'company')
     {
          return MyClaimModel::where('status', 'Rejected')->where('employee_id', get_employee_id())->get()->count();
     }
     if (Auth::user()->type == 'employee' || Auth::user()->type != 'company')
     {
          return MyClaimModel::where('status', 'Rejected')->where('userid', get_employee_id())->get()->count();
     }
}
function re_claim_pending()
{
     return MyClaimModel::where('status', 'Pennding')->whereIn('userid', selectedEmployee())->get()->count();
}
function re_claim_approved()
{
     return MyClaimModel::where('status', 'Approved')->whereIn('userid', selectedEmployee())->get()->count();
}
function re_claim_rejected()
{
     return MyClaimModel::where('status', 'Rejected')->whereIn('userid', selectedEmployee())->get()->count();
}

function showExceptionMessage(Object $ex)
{
     try
     {
          if (config('app.debug'))
          {
               return $ex->getMessage() . " | " . $ex->getLine();
               // return $ex->getMessage() . " | " . $ex->getLine() . " | " . $ex->getFile();
               // return $ex->getMessage();
          }
          else
          {
               return __("Some error occur, sorry for inconvenient");
          }
     }
     catch (Throwable $e)
     {
          return $ex->getMessage() . " | " . $ex->getLine() . " | " . $ex->getFile();
     }
     catch (Exception $e)
     {
          return $ex->getMessage() . " | " . $ex->getLine() . " | " . $ex->getFile();
     }
}

function sendNotification($notificationData)
{

     NotificationModel::create([
          'user_id' => '',
          'employee_id' => '',
          'title' => '',
          'details' => '',
          'status' => '',
          'date' => '',
          'created_by' => '',
          'redirect' => '',
     ]);
}

if (!function_exists('pushNotification'))
{
     function pushNotification($notificationMessage, $extraData, $deviceToken)
     {
          $firebase_server_key = 'AAAAhIfybus:APA91bFNyQ3r_F71F0bYsPGxLoUnHT1r9xipUQ5g3GP6hmYZSTWS2TAmUXZFF8ARsKaFrKhFyA5ltLZtluRVg4Y7BPYB92lJoatvPCs-HwA1Hq_XuFRFIfkDtpPLXi9eGKU2JwBtJJpm';

          if ($deviceToken == '' && $firebase_server_key <= 0)
          {
               return 0;
          }
          if (!empty($notificationMessage))
          {
               $postsfields = [
                    "notification" => $notificationMessage,
                    "data" => $extraData,
                    "to" => $deviceToken,
               ];
          }
          else
          {
               $postsfields = [
                    "data" => $extraData,
                    "to" => $deviceToken,
               ];
          }

          $ch = curl_init("https://fcm.googleapis.com/fcm/send");

          $header = array('Content-Type: application/json', "Authorization: key=$firebase_server_key");
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

          curl_setopt($ch, CURLOPT_POST, 0);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postsfields));

          $result = curl_exec($ch);

          if (curl_errno($ch))
          {
               $error_msg = curl_error($ch);
          }

          curl_close($ch);


          if ($result === false)
          {
               return 0;
          }
          else
          {
               $result = json_decode($result);
               return $result;
          }
     }
}


if (!function_exists('getAccessToken'))
{
     function getAccessToken($projectId = "hr-shastri")
     {
          try
          {
               Log::channel('schedular_logs')->info("in getAccessToken");

               $serviceAccountPath = config_path("firebase-credentials/project-$projectId.json");

               // Load the Firebase credentials JSON file
               $credentials = json_decode(file_get_contents($serviceAccountPath), true);

               // Create the JWT
               $now = time();
               $expiration = $now + 3600; // Token valid for 1 hour

               // JWT header
               $header = json_encode([
                    'alg' => 'RS256',
                    'typ' => 'JWT'
               ]);

               // JWT payload
               $payload = json_encode([
                    'iss' => $credentials['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/cloud-platform',
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'iat' => $now,
                    'exp' => $expiration
               ]);

               // Base64 encode the header and payload
               $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
               $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

               // Sign the JWT using the private key
               $signature = '';
               openssl_sign(
                    $base64UrlHeader . '.' . $base64UrlPayload,
                    $signature,
                    $credentials['private_key'],
                    'SHA256'
               );

               $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

               // Complete JWT
               $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

               // Request an access token from Google's OAuth 2.0 server
               $url = 'https://oauth2.googleapis.com/token';
               $fields = [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt
               ];

               // Use Laravel HTTP client to make the POST request
               $response = Http::asForm()->post($url, $fields);

               $responseArray = $response->json();

               Log::channel('schedular_logs')->info("in getAccessToken | After Send Response", [$responseArray]);

               // Return the access token
               return $responseArray['access_token'] ?? null;
          }
          catch (Exception | Throwable $ex)
          {
               Log::channel('schedular_logs')->info("in getAccessToken | Notification GENERATE SERVER KEY ERROR", [$ex]);
               return "";
          }
     }
}

if (!function_exists('sendpPushNotification'))
{
     function sendpPushNotification($title, $body, $usersData, $click_action = "", $extraData = [], $isDebug = false)
     {

          try
          {
               //added by monali
               $projectId = "hr-shastri";
               $userTokens = implode(',', $usersData->pluck("device_token")->toArray());

               $click_action = $click_action ?? "";
               $sound = $extraData["sound"] ?? "default";

               $device_type = implode(',', $usersData->pluck('device_type')->toArray());
               if ($device_type == 1) // 0 = web , 1 = ios , 2 = android
               {
                    $extraData = [
                         "message" => [
                              "token" => $userTokens,
                              "data" => $extraData,
                         ]
                    ];
                    $extraData["message"]["apns"] =
                         [
                              "payload" => [
                                   "aps" => [
                                        "sound" => $sound,
                                   ]
                              ]
                         ];

                    $extraData["message"]["notification"] = [
                         "title" => $title,
                         "body" => $body,
                    ];
               }

               // Device Type | 0 = web , 1 = ios , 2 = android
               // Device Plateform | 0 = android, 1 = ios, 2 = ionic, 3 = angular

               if ($device_type == 2)
               {
                    $ticket_id = $extraData['id'];
                    $extraData = [
                         "message" => [
                              "token" => $userTokens,
                         ]
                    ];
                    $extraData["message"]["android"]["data"] =
                         [
                              "title" => $title,
                              "body" => $body,
                              "ticket_id" => $ticket_id ?? '',
                              "click_action" => $click_action
                         ];
               }

               $firebase_server_key = getAccessToken($projectId);

               if (!$firebase_server_key)
               {
                    return "";
               }

               if ($isDebug == true)
               {
                    // dd([
                    //     "URL" => "https://fcm.googleapis.com/v1/projects/" . $projectId . "/messages:send",
                    //     "Token" => $firebase_server_key,
                    //     "body data"=> json_encode($extraData)
                    // ]);
               }
               $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer $firebase_server_key",
               ])->post("https://fcm.googleapis.com/v1/projects/" . $projectId . "/messages:send", $extraData);

               $body = $response->body();
               $statusCode = $response->status();
               //added by monali     

               // $firebase_server_key = "AAAAhIfybus:APA91bFNyQ3r_F71F0bYsPGxLoUnHT1r9xipUQ5g3GP6hmYZSTWS2TAmUXZFF8ARsKaFrKhFyA5ltLZtluRVg4Y7BPYB92lJoatvPCs-HwA1Hq_XuFRFIfkDtpPLXi9eGKU2JwBtJJpm";

               // $title = $title;
               // $body = $body;

               // $notificationMessage = array(
               //      'title' => $title,
               //      'body' => $body,
               // );



               // $extraData['click_action'] = $click_action;

               // $postsfields = [
               //      "notification" => $notificationMessage,
               //      "data" => $extraData,
               //      "to" => $userTokens,
               // ];

               // // dd(json_encode($postsfields));

               // $response = Http::withHeaders([
               //      'Authorization' => "key=$firebase_server_key",
               // ])->post('https://fcm.googleapis.com/fcm/send', $postsfields);

               // $body = $response->body();
               // $statusCode = $response->status();

               // dd($response);

               return ["data" => $body, "statusCode" => $statusCode, "message" => __("success")];
          }
          catch (Exception $ex)
          {
               return ["data" => [], "statusCode" => 500, "message" => showExceptionMessage($ex)];
          }
          catch (Throwable $ex)
          {
               return ["data" => [], "statusCode" => 500, "message" => showExceptionMessage($ex)];
          }
     }
     function sendpPushNotification_old($title, $body, $usersData, $click_action = "", $extraData = [])
     {
          try
          {
               $firebase_server_key = "AAAAhIfybus:APA91bFNyQ3r_F71F0bYsPGxLoUnHT1r9xipUQ5g3GP6hmYZSTWS2TAmUXZFF8ARsKaFrKhFyA5ltLZtluRVg4Y7BPYB92lJoatvPCs-HwA1Hq_XuFRFIfkDtpPLXi9eGKU2JwBtJJpm";

               $title = $title;
               $body = $body;

               $notificationMessage = array(
                    'title' => $title,
                    'body' => $body,
               );

               $userTokens = implode(',', $usersData->pluck("device_token")->toArray());

               $extraData['click_action'] = $click_action;

               $postsfields = [
                    "notification" => $notificationMessage,
                    "data" => $extraData,
                    "to" => $userTokens,
               ];

               // dd(json_encode($postsfields));

               $response = Http::withHeaders([
                    'Authorization' => "key=$firebase_server_key",
               ])->post('https://fcm.googleapis.com/fcm/send', $postsfields);

               $body = $response->body();
               $statusCode = $response->status();

               return ["data" => $body, "statusCode" => $statusCode, "message" => __("success")];
          }
          catch (Exception $ex)
          {
               return ["data" => [], "statusCode" => 500, "message" => showExceptionMessage($ex)];
          }
          catch (Throwable $ex)
          {
               return ["data" => [], "statusCode" => 500, "message" => showExceptionMessage($ex)];
          }
     }
}

if (!function_exists('getLeaveTypeDays'))
{
     function getLeaveTypeDays()
     {
          $leaveDays = [];

          for ($i = 1; $i <= 30; $i++)
          {
               $leaveDays[] = $i;
          }
          return $leaveDays;
     }
}


function employeeIdFormat($number)
{
     $settings = Utility::settings();
     return $settings["employee_prefix"] . sprintf("%05d", $number);
}

function dateFormat($date)
{
     $settings = Utility::settings();

     if ($date == "0000-00-00" || $date == "")
     {
          return  "";
     }
     return date($settings['site_date_format'], strtotime($date));
}

function timeFormat($time)
{
     try
     {
          $timeFormat = "";
          $settings = Utility::settings();

          if (empty(trim($time)))
          {
               return  "";
          }

          return date($settings['site_time_format'], strtotime($time));
     }
     catch (Exception | Throwable $ex)
     {
          return  "";
     }
}

function priceFormat($price)
{
     $settings = Utility::settings();

     return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
}


function formatNumber($number)
{
     return strpos($number, '.') !== false ? number_format($number, 2) : number_format($number, 0);
}


function getWorkingDaysInMonth($customMonth = null)
{
     try
     {
          $customMonth = $customMonth ?? Carbon::now()->month;

          $settings = Utility::settings();

          // Specify custom weekend days (e.g., Friday and Saturday)
          // $customWeekendDays = [Carbon::FRIDAY, Carbon::SATURDAY];
          $customWeekendDays = [];
          $weekendDaysList = [];

          $customWeekendDays[] = $settings["company_office_week_off_monday"] == "on" ? Carbon::MONDAY : "";
          $customWeekendDays[] = $settings["company_office_week_off_tuesday"] == "on" ? Carbon::TUESDAY : "";
          $customWeekendDays[] = $settings["company_office_week_off_wednesday"] == "on" ? Carbon::WEDNESDAY : "";
          $customWeekendDays[] = $settings["company_office_week_off_thusday"] == "on" ? Carbon::THURSDAY : "";
          $customWeekendDays[] = $settings["company_office_week_off_friday"] == "on" ? Carbon::FRIDAY : "";
          $customWeekendDays[] = $settings["company_office_week_off_saturday"] == "on" ? Carbon::SATURDAY : "";
          $customWeekendDays[] = $settings["company_office_week_off_sunday"] == "on" ? Carbon::SUNDAY : "";

          // $customWeekendDays = array_values(array_filter($customWeekendDays));

          // Get the first day of the current month
          $firstDay = Carbon::now()->startOfMonth()->setMonth($customMonth);


          // Get the last day of the current month
          $lastDay = Carbon::now()->endOfMonth()->setMonth($customMonth);

          // Calculate the difference in days between the first and last day
          $totalDays = Carbon::now()->endOfMonth()->setMonth($customMonth)->daysInMonth;

          // Calculate the number of custom weekend days in the month
          $customWeekends = $firstDay->diffInDaysFiltered(function (Carbon $date) use ($customWeekendDays)
          {
               return in_array($date->dayOfWeek, $customWeekendDays);
          }, $lastDay);

          // Calculate the number of working days
          $workingDays = $totalDays - $customWeekends;

          return [
               'totalDays' => $totalDays,
               'customWeekends' => $customWeekends,
               'workingDays' => $workingDays,
          ];
     }
     catch (Exception | Throwable $e)
     {
          return [
               'totalDays' => 0,
               'customWeekends' => 0,
               'workingDays' => 0,
          ];
     }
}

function getCompanyWorkingDays($customMonth = null)
{
     $workingDays = 0;
     try
     {
          $customMonth = $customMonth ?? Carbon::now()->month;

          $settings = Utility::settings();
          $working_days_type = $settings["working_days_type"];
          $company_working_days = $settings["company_working_days"];

          $getWorkingDaysInMonth = getWorkingDaysInMonth();

          $totalDaysinCurrentMonth = Carbon::now()->setMonth($customMonth)->daysInMonth;

          if ($working_days_type == 0) // 0 = current month working days, 1 = company working days defined by company,
          {
               // $workingDays = $getWorkingDaysInMonth['workingDays'];
               // $workingDays = Carbon::now()->setMonth($customMonth)->daysInMonth;
               $workingDays = $totalDaysinCurrentMonth;
          }
          else
          {
               // if ($company_working_days > $getWorkingDaysInMonth['workingDays'])
               if ($company_working_days > $totalDaysinCurrentMonth)
               {
                    // $workingDays = $getWorkingDaysInMonth['workingDays'];
                    $workingDays = $totalDaysinCurrentMonth;
               }
               else
               {
                    $workingDays = $company_working_days;
               }
          }
     }
     catch (Exception | Throwable $ex)
     {
     }

     return (int)$workingDays;
}

function calculateAttendanceData_old($employeeId, $attendanceMonth = null, $inTime = null, $outTime = null) // clock_type | 0 = clock in, 1 = clock out
{
     $data = [];
     $response = [];

     try
     {
          $attendanceMonth = ($attendanceMonth == null ? date("Y-m-d") : $attendanceMonth);

          $year = date("Y", strtotime($attendanceMonth));
          $month = date("m", strtotime($attendanceMonth));

          $startTime = date("H:i", strtotime(Utility::getValByName('company_start_time')));
          $endTime = date("H:i", strtotime(Utility::getValByName('company_end_time')));

          // check if attendance is already added for today
          $attendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->whereDate('date', $attendanceMonth)->first();

          $attendanceId = ($attendance->id ?? 0);

          // check how flexi hours are used in defiened month
          $usedFlexiHoursDaysCount = AttendanceEmployee::where('employee_id', '=', $employeeId)
               ->whereYear('date', $year)
               ->whereMonth('date', $month)
               ->when($attendanceId > 0, function ($query) use ($attendanceId)
               {
                    return $query->where("id", "!=", $attendanceId);
               })
               ->get()
               ->sum("is_flexi_hours_used");

          // get company flexi hours and flexi days

          $flexiHours = Utility::getValByName('company_flexi_hours') ?? "";
          $flexiDays = Utility::getValByName('company_flexi_days') ?? "";

          // check if employee is assigned to any shift calculate according that shift
          $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employeeId)->first();
          $employeeShift = null;
          if ($workingShiftEmployees != null)
          {
               $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
               if ($employeeShift != null)
               {
                    $startTime = date("H:i", strtotime($employeeShift->start_time));
                    $endTime = date("H:i", strtotime($employeeShift->end_time));

                    $flexiHours = $employeeShift->flexi_hours;
                    $flexiDays = $employeeShift->flexi_days;
               }
          }

          $late = "00:00:00";
          $isFlexiHourApplied = false;

          // $clockInTime = date("H:i:s", strtotime($attendance->clock_in ?? $inTime));
          $clockInTime = $inTime;

          // late comming

          // dd(
          //      [
          //           "startTime = $startTime",
          //           "clockInTime = $clockInTime",
          //           strtotime(date("H:i", strtotime($clockInTime))) > strtotime(date("H:i", strtotime($startTime)))
          //      ]
          // );

          if (strtotime(date("H:i", strtotime($clockInTime))) > strtotime(date("H:i", strtotime($startTime))))
          {
               $totalLateSeconds = strtotime(date("H:i", strtotime($clockInTime))) - strtotime($startTime);
               $hours = floor($totalLateSeconds / 3600);
               $mins = floor($totalLateSeconds / 60 % 60);
               $secs = floor($totalLateSeconds % 60);
               $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

               // dd($late, $flexiHours, (!empty($flexiHours) && $flexiHours > 0));

               // flexi hours and days calculation
               if (!empty($flexiHours) && $flexiHours > 0)
               {
                    $lateCarbon = Carbon::createFromFormat('H:i:s', $late);
                    $lateInMinutes = $lateCarbon->hour * 60 + $lateCarbon->minute;

                    // check if late minutes are grater than flexi hours minutes if ye then add late hours else do not consider late coming
                    // dd([
                    //      "lateInMinutes" => $lateInMinutes,
                    //      "flexiHours" => $flexiHours,
                    //      '$lateInMinutes <= $flexiHours' => $lateInMinutes <= $flexiHours,
                    //      "-------------" => "-------------",
                    //      'usedFlexiHoursDaysCount' => $usedFlexiHoursDaysCount,
                    //      'flexiDays' => $flexiDays,
                    //      "condition" => (($usedFlexiHoursDaysCount < $flexiDays) ||
                    //           (empty($flexiDays) || $flexiDays <= 0))
                    // ]);

                    if (
                         $lateInMinutes <= $flexiHours &&
                         (
                              ($usedFlexiHoursDaysCount < $flexiDays) ||
                              (empty($flexiDays) || $flexiDays <= 0)
                         )
                    )
                    {
                         $late = "00:00:00";
                         $isFlexiHourApplied = true;
                    }
               }
               // flexi hours and days calculation
          }
          // late comming


          $earlyLeaving = "00:00:00";
          $overtime = '00:00:00';

          if ($attendance != null || $outTime != null)
          {
               // add flexi hour time in enc time of company / shift
               // dd($attendance->is_flexi_hours_used ?? 0, $isFlexiHourApplied);
               if (($attendance->is_flexi_hours_used ?? 0) == 1 || $isFlexiHourApplied)
               {
                    try
                    {
                         $startTimeDiff = new DateTime(date("H:i", strtotime($startTime)));
                         $clockInTimeDiff = new DateTime(date("H:i", strtotime($clockInTime)));

                         $flexiHoursDiffrence = $startTimeDiff->diff($clockInTimeDiff);

                         $diffHours = $flexiHoursDiffrence->format('%h');      // Hours
                         $diffMinutes = $flexiHoursDiffrence->format('%i');    // Minutes
                         $diffSeconds = $flexiHoursDiffrence->format('%s');    // Seconds

                         $endTimeDiff = new DateTime($endTime);
                         $endTimeHours = $endTimeDiff->format('H');
                         $endTimeMinutes = $endTimeDiff->format('i');
                         $endTimeSeconds = $endTimeDiff->format('s');

                         $sumHours = $endTimeHours + $diffHours;
                         $sumMinutes = $endTimeMinutes + $diffMinutes;
                         $sumSeconds = $endTimeSeconds + $diffSeconds;

                         $sumMinutes += floor($sumSeconds / 60);
                         $sumSeconds %= 60;

                         $sumHours += floor($sumMinutes / 60);
                         $sumMinutes %= 60;

                         $sumTime = new DateTime();
                         $sumTime->setTime($sumHours, $sumMinutes, $sumSeconds);

                         $endTime = $sumTime->format('H:i');
                    }
                    catch (Exception $ex)
                    {
                    }
               }
               // add flexi hour time in enc time of company / shift


               //early Leaving

               // dd(
               //      [
               //           "startTime" => date("H:i", strtotime($startTime)),
               //           "endTime" => date("H:i", strtotime($endTime)),
               //           "outTime" => date("H:i", strtotime($outTime)),
               //           date("H:i", strtotime($endTime)) > date("H:i", strtotime($outTime)),
               //           date("H:i", strtotime($outTime)) > date("H:i", strtotime($startTime)),
               //      ]
               // );

               // dd($earlyLeaving, [
               //      "Company End Time" => date("H:i", strtotime($endTime)),
               //      "Employe Leaving time" => date("H:i", strtotime($outTime))
               // ]);

               if (date("H:i", strtotime($endTime)) > date("H:i", strtotime($outTime)))
               {
                    $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($outTime);
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
               }
               //early Leaving


               //Overtime
               // dd($outTime, $endTime, strtotime($outTime) > strtotime($endTime));
               if (strtotime($outTime) > strtotime($endTime))
               {
                    $totalOvertimeSeconds = strtotime($outTime) - strtotime($endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
               }
               //Overtime
          }

          $response["late_comming"] = $late;
          $response["is_flexi_hour_applied"] = $isFlexiHourApplied;

          $response["early_leaving"] = $earlyLeaving;
          $response["overtime"] = $overtime;

          $data = ["status" => 1, "message" => __("success"), "data" => $response];
     }
     catch (Exception $ex)
     {
          $data = ["status" => 0, "message" => showExceptionMessage($ex), "data" => []];
     }
     catch (Throwable $ex)
     {
          $data = ["status" => 0, "message" => showExceptionMessage($ex), "data" => []];
     }

     return $data;
}

function calculateAttendanceData(
     $employeeId,
     $attendanceMonth = null,
     $inDateTime = null,
     $outDateTime = null,
     $companyId = null,
     $come_from = ''
)
{
     static $companySettingsCache = [];
     static $shiftAssignmentCache = [];
     static $monthlyFlexiUsageCache = [];

     $attendanceMonth = $attendanceMonth ?? date("Y-m-d");
     $year  = date("Y", strtotime($attendanceMonth));
     $month = date("m", strtotime($attendanceMonth));

     $cacheKey = "{$employeeId}-{$year}-{$month}";

     // ✅ 1. Cache company settings
     if (!isset($companySettingsCache[$companyId]))
     {
          $companySettingsCache[$companyId] = [
               'company_start_time' => Utility::getValByName('company_start_time'),
               'company_end_time'   => Utility::getValByName('company_end_time'),
               'company_flexi_hours' => Utility::getValByName('company_flexi_hours'),
               'company_flexi_days' => Utility::getValByName('company_flexi_days'),
          ];
     }

     $settings = $companySettingsCache[$companyId];

     // ✅ 2. Cache employee shift assignment
     if (!isset($shiftAssignmentCache[$employeeId]))
     {
          $shiftAssignmentCache[$employeeId] =
               \App\Models\WorkingShiftEmployees::where("employee_id", $employeeId)->first();
     }

     $shiftEmp = $shiftAssignmentCache[$employeeId] ?? null;
     $shift = $shiftEmp
          ? \App\Models\WorkingShifts::find($shiftEmp->working_shifts_id)
          : null;

     // ✅ Determine base start/end time
     $startCfg = $shift ? $shift->start_time : $settings['company_start_time'];
     $endCfg   = $shift ? $shift->end_time   : $settings['company_end_time'];

     $startTime = date("Y-m-d H:i:s", strtotime($attendanceMonth . " " . $startCfg));
     $endTime   = date("Y-m-d H:i:s", strtotime($attendanceMonth . " " . $endCfg));

     if (strtotime($endTime) < strtotime($startTime))
     {
          $endTime = date("Y-m-d H:i:s", strtotime($endTime . " +1 day"));
     }

     // ✅ 3. Cache monthly flexi usage
     if (!isset($monthlyFlexiUsageCache[$cacheKey]))
     {
          $monthlyFlexiUsageCache[$cacheKey] = \App\Models\AttendanceEmployee::where('employee_id', $employeeId)
               ->whereYear('date', $year)
               ->whereMonth('date', $month)
               ->sum("is_flexi_hours_used");
     }

     $usedFlexiHoursDaysCount = $monthlyFlexiUsageCache[$cacheKey];

     $flexiHours = $shift ? $shift->flexi_hours : $settings['company_flexi_hours'];
     $flexiDays  = $shift ? $shift->flexi_days  : $settings['company_flexi_days'];

     $late = "00:00:00";
     $earlyLeaving = "00:00:00";
     $overtime = "00:00:00";
     $isFlexiApplied = false;

     // ✅ LATE CALCULATION
     if ($inDateTime && strtotime($inDateTime) > strtotime($startTime))
     {
          $lateSeconds = strtotime($inDateTime) - strtotime($startTime);
          $late = gmdate('H:i:s', $lateSeconds);

          if ($flexiHours > 0)
          {
               $lateMinutes = intdiv($lateSeconds, 60);
               if (
                    $lateMinutes <= $flexiHours &&
                    ($usedFlexiHoursDaysCount < $flexiDays || $flexiDays <= 0)
               )
               {
                    $late = "00:00:00";
                    $isFlexiApplied = true;

                    // extend end time by late minutes
                    $endTime = date("Y-m-d H:i:s", strtotime($endTime . " +{$lateMinutes} minutes"));
               }
          }
     }

     // ✅ EARLY LEAVING
     if ($outDateTime && strtotime($endTime) > strtotime($outDateTime))
     {
          $earlySeconds = strtotime($endTime) - strtotime($outDateTime);
          $earlyLeaving = gmdate("H:i:s", $earlySeconds);
     }

     // ✅ OVERTIME
     if ($outDateTime && strtotime($outDateTime) > strtotime($endTime))
     {
          $otSeconds = strtotime($outDateTime) - strtotime($endTime);
          $overtime = gmdate("H:i:s", $otSeconds);
     }

     return [
          "status" => 1,
          "message" => "success",
          "data" => [
               "startTime" => $startTime,
               "endTime" => $endTime,
               "clockInTime" => $inDateTime,
               "clockOutTime" => $outDateTime,
               "late_comming" => $late,
               "early_leaving" => $earlyLeaving,
               "overtime" => $overtime,
               "is_flexi_hour_applied" => $isFlexiApplied,
          ]
     ];
}

function calculateAttendanceData_bk($employeeId, $attendanceMonth = null, $inDateTime = null, $outDateTime = null, $companyId = null, $come_from = '')
{
     $data = [];
     $response = [];

     try
     {
          $attendanceMonth = ($attendanceMonth == null ? date("Y-m-d") : $attendanceMonth);

          $year = date("Y", strtotime($attendanceMonth));
          $month = date("m", strtotime($attendanceMonth));
          if ($come_from == 'cron')
          {
               $utility = Utility::where('created_by', $companyId)->get();
               $configuration = [
                    "company_start_time",
                    "company_end_time",
               ];
               foreach ($utility as $row)
               {
                    if (in_array($row->name, $configuration))
                    {
                         $settings[$row->name] = $row->value;
                    }
               }
               $startTime = date("Y-m-d H:i:s", strtotime($settings['company_start_time']));
               $endTime = date("Y-m-d H:i:s", strtotime($settings['company_end_time']));
          }
          else
          {
               $startTime = date("Y-m-d H:i:s", strtotime(Utility::getValByName('company_start_time')));
               $endTime = date("Y-m-d H:i:s", strtotime(Utility::getValByName('company_end_time')));
          }

          // if ($inDateTime != null)
          // {
          //      $inTimeDateForCompany = date("Y-m-d", strtotime($inDateTime));
          //      $startTime = date("Y-m-d H:i:s", strtotime($inTimeDateForCompany . " " . Utility::getValByName('company_start_time')));
          // }
          // if ($outDateTime != null)
          // {
          //      $outTimeDateForCompany = date("Y-m-d", strtotime($outDateTime));
          //      $endTime = date("Y-m-d H:i:s", strtotime($outTimeDateForCompany . " " . Utility::getValByName('company_end_time')));
          // }
          // dd($startTime, $endTime);

          if (strtotime($endTime) < strtotime($startTime))
          {
               $endTime = date("Y-m-d H:i:s", strtotime($endTime . " +1 day"));
          }
          // check if attendance is already added for today
          $attendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->whereDate('date', $attendanceMonth)->first();

          $attendanceId = ($attendance->id ?? 0);

          // check how flexi hours are used in defined month
          $usedFlexiHoursDaysCount = AttendanceEmployee::where('employee_id', '=', $employeeId)
               ->whereYear('date', $year)
               ->whereMonth('date', $month)
               ->when($attendanceId > 0, function ($query) use ($attendanceId)
               {
                    return $query->where("id", "!=", $attendanceId);
               })
               ->get()
               ->sum("is_flexi_hours_used");

          // get company flexi hours and flexi days
          $flexiHours = Utility::getValByName('company_flexi_hours') ?? "";
          $flexiDays = Utility::getValByName('company_flexi_days') ?? "";

          // check if employee is assigned to any shift calculate according that shift
          $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employeeId)->first();
          $employeeShift = null;
          if ($workingShiftEmployees != null)
          {
               $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
               if ($employeeShift != null)
               {
                    $startTime = date("Y-m-d H:i:s", strtotime($employeeShift->start_time));
                    $endTime = date("Y-m-d H:i:s", strtotime($employeeShift->end_time));

                    $flexiHours = $employeeShift->flexi_hours;
                    $flexiDays = $employeeShift->flexi_days;
               }
          }


          $late = "00:00:00";
          $isFlexiHourApplied = false;

          $clockInTime = $inDateTime;

          // late coming
          // dd(
          //      [
          //           "startTime = $startTime",
          //           "clockInTime = $clockInTime",
          //           strtotime($clockInTime) > strtotime($startTime),
          //           strtotime(date("H:i", strtotime($clockInTime))) > strtotime(date("H:i", strtotime($startTime)))
          //      ]
          // );
          if (strtotime($clockInTime) > strtotime($startTime))
          {
               $totalLateSeconds = strtotime($clockInTime) - strtotime($startTime);
               $hours = floor($totalLateSeconds / 3600);
               $mins = floor($totalLateSeconds / 60 % 60);
               $secs = floor($totalLateSeconds % 60);
               $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

               // flexi hours and days calculation
               if (!empty($flexiHours) && $flexiHours > 0)
               {
                    $lateCarbon = Carbon::createFromFormat('H:i:s', $late);
                    $lateInMinutes = $lateCarbon->hour * 60 + $lateCarbon->minute;

                    if (
                         $lateInMinutes <= $flexiHours &&
                         (
                              ($usedFlexiHoursDaysCount < $flexiDays) ||
                              (empty($flexiDays) || $flexiDays <= 0)
                         )
                    )
                    {
                         $late = "00:00:00";
                         $isFlexiHourApplied = true;
                    }
               }
          }

          $earlyLeaving = "00:00:00";
          $overtime = '00:00:00';

          if ($attendance != null || $outDateTime != null)
          {
               if (($attendance->is_flexi_hours_used ?? 0) == 1 || $isFlexiHourApplied)
               {
                    try
                    {
                         $startTimeDiff = new DateTime($startTime);
                         $clockInTimeDiff = new DateTime($clockInTime);

                         $flexiHoursDiffrence = $startTimeDiff->diff($clockInTimeDiff);

                         $diffHours = $flexiHoursDiffrence->format('%h');      // Hours
                         $diffMinutes = $flexiHoursDiffrence->format('%i');    // Minutes
                         $diffSeconds = $flexiHoursDiffrence->format('%s');    // Seconds

                         $endTimeDiff = new DateTime($endTime);
                         $endTimeHours = $endTimeDiff->format('H');
                         $endTimeMinutes = $endTimeDiff->format('i');
                         $endTimeSeconds = $endTimeDiff->format('s');

                         $sumHours = $endTimeHours + $diffHours;
                         $sumMinutes = $endTimeMinutes + $diffMinutes;
                         $sumSeconds = $endTimeSeconds + $diffSeconds;

                         $sumMinutes += floor($sumSeconds / 60);
                         $sumSeconds %= 60;

                         $sumHours += floor($sumMinutes / 60);
                         $sumMinutes %= 60;

                         $sumTime = new DateTime();
                         $sumTime->setTime($sumHours, $sumMinutes, $sumSeconds);

                         $endTime = $sumTime->format('Y-m-d H:i:s');
                    }
                    catch (Exception $ex)
                    {
                    }
               }

               // early Leaving
               if ($outDateTime != null && strtotime($endTime) > strtotime($outDateTime))
               {
                    $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($outDateTime);
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
               }

               // Overtime

               // inDateTime
               // dd($outDateTime, $endTime);
               if ($outDateTime != null && strtotime($outDateTime) > strtotime($endTime))
               {
                    $totalOvertimeSeconds = strtotime($outDateTime) - strtotime($endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
               }
          }

          $response["startTime"] = $startTime;
          $response["endTime"] = $endTime;

          $response["clockInTime"] = $clockInTime;
          $response["clockOutTime"] = $outDateTime;

          $response["-------------------"] = "-------------------";

          $response["late_comming"] = $late;
          $response["is_flexi_hour_applied"] = $isFlexiHourApplied;

          $response["early_leaving"] = $earlyLeaving;
          $response["overtime"] = $overtime;

          $data = ["status" => 1, "message" => __("success"), "data" => $response];
     }
     catch (Exception $ex)
     {
          $data = ["status" => 0, "message" => showExceptionMessage($ex), "data" => []];
     }
     catch (Throwable $ex)
     {
          $data = ["status" => 0, "message" => showExceptionMessage($ex), "data" => []];
     }

     return $data;
}


// upload file functions

if (!function_exists('ImageCompressionQualityRation'))
{
     function ImageCompressionQualityRation()
     {
          return 55;
     }
}

if (!function_exists('uploadFile'))
{

     /**
      * uploadFile | This function is used to upload file on server or clouod based on .env file DEFAULT_STORAGE_DRIVER key configurations
      *
      * @param  mixed $rawFile | add file path or pass direct request file (get request file with file name key and pass it to this function)
      * @param  mixed $uploadFileName | Upload file File Name only
      * @param  mixed $uploadFilePath | Upload file path with slash at the end
      * @param  mixed $isImage | is Image (true = yes| false = no) | Default False
      * @param  mixed $optimizeImage  | Optimize Image (true = yes| false = no), this will only work if isImage is true | Default true
      * @param  mixed $defaultStorageDriver | Set defaultStorageDriver (if empty it will pick defaultStorageDriver from .env file key:DEFAULT_STORAGE_DRIVER)
      * @return array | this will return array with status, message, Uploaded Image Name, Uploaded Image Path (status 1 = success)
      */
     function uploadFile($rawFile, $uploadFileName, $uploadFilePath, $isImage = false, $optimizeImage = true, $defaultStorageDriver = "")
     {
          $response = [];
          $status = 0;
          try
          {
               // check defaultStorageDriver is local or any cloud | if local store in own server else store according
               $defaultStorageDriver = empty(trim($defaultStorageDriver)) ? env("DEFAULT_STORAGE_DRIVER", "public") : $defaultStorageDriver;
               $defaultStorageDriver = "s3";

               $uploadFilePath = $uploadFilePath . $uploadFileName;

               $bucket = config("filesystems.disks.s3.bucket");

               // $rawFile_name = $rawFile->getClientOriginalName();
               $uploadImageData = "";

               if ($isImage && $optimizeImage) // Optimize Image and reduce image file size
               {
                    try
                    {
                         $im = new \Imagick();
                         $im->readImage($rawFile);
                         // $im->setImageCompression(\Imagick::COMPRESSION_JPEG);
                         $im->setImageCompressionQuality(ImageCompressionQualityRation());
                         // $im->setImageFormat("png");
                         $im->stripImage();
                         $uploadImageData = $im->getimageblob();
                         $im->destroy();
                    }
                    catch (Exception $e)
                    {
                         $uploadImageData = file_get_contents((string)$rawFile);
                    }
                    catch (Throwable $e)
                    {
                         $uploadImageData = file_get_contents((string)$rawFile);
                    }
               }
               else
               {
                    $uploadImageData = file_get_contents((string)$rawFile);
               }

               $uploaded_image_path = "";
               if ($defaultStorageDriver == "s3")
               {
                    Storage::disk('s3')->put($uploadFilePath, $uploadImageData);
                    $uploaded_image_path = Storage::disk('s3')->url($bucket . "/" . $uploadFilePath);
                    $status = 1;
               }
               else if ($defaultStorageDriver == "public")
               {
                    Storage::disk('public')->put($uploadFilePath, $uploadImageData);
                    $uploaded_image_path = Storage::disk('public')->url("app/public/" . $uploadFilePath);
                    $status = 1;
               }

               $response = [
                    "status" => $status,
                    "message" => trans("file uploaded successfully."),
                    "image_name" => $uploadFileName,
                    "uploaded_image_path" => $uploaded_image_path
               ];
          }
          catch (Exception $e)
          {
               $response = [
                    "status" => $status,
                    "message" => $e->getMessage(),
                    "image_name" => "",
                    "uploaded_image_path" => "",
               ];
          }
          catch (Throwable $e)
          {
               $response = [
                    "status" => $status,
                    "message" => $e->getMessage(),
                    "image_name" => "",
                    "uploaded_image_path" => "",
               ];
          }

          return $response;
     }
}

if (!function_exists('deleteFile'))
{

     /**
      * deleteFile | This function is used to delete file on server or clouod based on .env file DEFAULT_STORAGE_DRIVER key configurations
      *
      * @param  mixed $deleteFilePath delete file path or pass direct request file (get request file with file name key and pass it to this function)
      * @param  mixed $defaultStorageDriver Set defaultStorageDriver (if empty it will pick defaultStorageDriver from .env file key:DEFAULT_STORAGE_DRIVER)
      * @return array  this will return array with status, message, Uploaded Image Name, Uploaded Image Path (status 1 = success)
      */
     function deleteFile($deleteFilePath, $defaultStorageDriver = "")
     {
          $response = [];
          $status = 0;
          $deletedImagesCount = 0;
          try
          {
               // check defaultStorageDriver is local or any cloud | if local store in own server else store according
               $defaultStorageDriver = empty(trim($defaultStorageDriver)) ? env("DEFAULT_STORAGE_DRIVER", "public") : $defaultStorageDriver;
               $defaultStorageDriver = "s3";

               if (!is_array($deleteFilePath))
               {
                    $deleteFilePath = [$deleteFilePath];
               }

               foreach ($deleteFilePath as $deleteFilePathRow)
               {
                    if ($defaultStorageDriver == "s3")
                    {
                         Storage::disk('s3')->delete($deleteFilePathRow);
                         $deletedImagesCount++;
                    }
                    else if ($defaultStorageDriver == "public")
                    {
                         Storage::disk('public')->delete($deleteFilePathRow);
                         $deletedImagesCount++;
                    }
               }

               $response = [
                    "status" => $status,
                    "no_of_files" => $deletedImagesCount,
                    "message" => trans("file.files deleted successfully."),
               ];
          }
          catch (Exception $e)
          {
               $response = [
                    "status" => $status,
                    "message" => $e->getMessage(),
                    "no_of_files" => 0,
               ];
          }
          catch (Throwable $e)
          {
               $response = [
                    "status" => $status,
                    "message" => $e->getMessage(),
                    "no_of_files" => 0,
               ];
          }

          return $response;
     }
}

if (!function_exists('getFilePathPrefix'))
{

     /**
      * getFilePathPrefix | This function is used to delete file on server or clouod based on .env file DEFAULT_STORAGE_DRIVER key configurations
      *
      * @param  mixed $business_id
      * @param  mixed $store_id
      * @param  mixed $defaultStorageDriver | Set defaultStorageDriver (if empty it will pick defaultStorageDriver from .env file key:DEFAULT_STORAGE_DRIVER)
      * @return string | this will return array with status, message, Uploaded Image Name, Uploaded Image Path (status 1 = success)
      */
     function getFilePathPrefix($business_id = "", $store_id = "", $defaultStorageDriver = "")
     {
          $response = "";

          try
          {
               $defaultStorageDriver = empty(trim($defaultStorageDriver)) ? env("DEFAULT_STORAGE_DRIVER", "public") : $defaultStorageDriver;
               $defaultStorageDriver = "s3";

               if ($defaultStorageDriver == "s3")
               {
                    $s4BucketUrl = config("filesystems.disks.s3.url");
                    $s4BucketName = config("filesystems.disks.s3.bucket");

                    $business_id_key = !empty($business_id) ? base64_encode($business_id) . "/" : "";
                    $store_id_key = !empty($business_id_key) ? (!empty($store_id) ? "stores/" . base64_encode($store_id) . "/" : "") : "";

                    $response = $s4BucketUrl . $s4BucketName . "/" . $business_id_key . $store_id_key;
               }
               else if ($defaultStorageDriver == "public")
               {
                    $business_id_key = !empty($business_id) ? base64_encode($business_id) . "/" : "";
                    $store_id_key = !empty($business_id_key) ? (!empty($store_id) ? base64_encode($store_id) . "/" : "") : "";

                    $response = Storage::disk('public')->url("app/public/" . $business_id_key . $store_id_key);
               }
          }
          catch (Exception $e)
          {
               $response = "";
          }
          catch (Throwable $e)
          {
               $response = "";
          }

          return $response;
     }
}

if (!function_exists('getFilePathPrefixForOperations'))
{

     /**
      * getFilePathPrefix | This function is used to delete file on server or clouod based on .env file DEFAULT_STORAGE_DRIVER key configurations
      *
      * @param  mixed $business_id
      * @param  mixed $store_id
      * @param  mixed $defaultStorageDriver | Set defaultStorageDriver (if empty it will pick defaultStorageDriver from .env file key:DEFAULT_STORAGE_DRIVER)
      * @return array | this will return array with status, message, Uploaded Image Name, Uploaded Image Path (status 1 = success)
      */
     function getFilePathPrefixForOperations($business_id = "", $store_id = "")
     {
          $response = "";

          try
          {
               $business_id_key = !empty($business_id) ? base64_encode($business_id) . "/" : "";
               $store_id_key = !empty($business_id_key) ? (!empty($store_id) ? "stores/" . base64_encode($store_id) . "/" : "") : "";

               $response = $business_id_key . $store_id_key;
          }
          catch (Exception $e)
          {
               $response = "";
          }
          catch (Throwable $e)
          {
               $response = "";
          }

          return $response;
     }
}

// upload file functions


function getCompanyCtc($companyId)
{
     $ctc_percentage = 0;
     try
     {
          $ctc_percentage = User::where("id", $companyId)->first()->ctc_percentage ?? 40;
     }
     catch (Exception $e)
     {
          $ctc_percentage = 40;
     }
     catch (Throwable $e)
     {
          $ctc_percentage = 40;
     }
     return $ctc_percentage;
}


function get_allowance_value_2($id, $parent_id, $employess)
{
     // dd("in helper");
     try
     {
          $get_parentData = AllowanceOption::find($parent_id);
          if (!empty($get_parentData))
          {

               if ($get_parentData->allowance_types == 0 || $get_parentData->allowance_types == null)
               {
                    if ($get_parentData->type == 'percentage')
                    {
                         $empBasicSalary = ($get_parentData->amount * $employess->salary) / 100;
                         return $empBasicSalary;
                    }
                    else
                    {
                         return $get_parentData->amount;
                    }
               }
               else
               {
                    // dd("in");
                    get_allowance_value_2($id, $get_parentData->id, $employess);
               }
          }
     }
     catch (Exception | Throwable $ex)
     {
          // dd("helper", $ex);
          return 0;
     }
}

function get_commission_value($id, $parent_id, $employess)
{
     try
     {
          $get_parentData = Commission_option::find($parent_id);
          // $get_CurruntData = \App\Models\Commission_option::find($id);

          if (!empty($get_parentData))
          {

               if ($get_parentData->commision_types == 0 || $get_parentData->commision_types == null)
               {
                    if ($get_parentData->type == 'percentage')
                    {
                         $empBasicSalary = ($get_parentData->amount * $employess->salary) / 100;
                         return $empBasicSalary;
                    }
                    else
                    {
                         return $get_parentData->amount;
                    }
               }
               else
               {
                    get_commission_value($id, $get_parentData->id, $employess);
               }
          }
     }
     catch (Exception | Throwable $ex)
     {
          return 0;
     }
}

if (!function_exists('numberFormat'))
{
     function numberFormat($number, $deminmal_point = 2)
     {
          return number_format((float) $number, $deminmal_point, '.', '');
     }
}

if (!function_exists('getSalarySlipPreviewAmountFormatConfig'))
{
     function getSalarySlipPreviewAmountFormatConfig(): array
     {
          return [
               'showDecimalPoints' => false,
               'decimalPoints'     => 0,
               // 'showDecimalPoints' => true,
               // 'decimalPoints'     => 2,
          ];
     }
}

if (!function_exists('formatSalarySlipPreviewAmount'))
{
     function formatSalarySlipPreviewAmount($amount, ?bool $showDecimalPoints = null, ?int $decimalPoints = null)
     {
          $config = getSalarySlipPreviewAmountFormatConfig();
          $showDecimalPoints = $showDecimalPoints ?? $config['showDecimalPoints'];
          $decimalPoints = (int) ($decimalPoints ?? $config['decimalPoints']);

          if ($decimalPoints < 0 || $decimalPoints > 4)
          {
               $decimalPoints = 2;
          }

          $decimals = $showDecimalPoints ? $decimalPoints : 0;

          return numberFormat(round((float) $amount, $decimals), $decimals);
     }
}

if (!function_exists('getEmployeeSalaryStructure'))
{
     /**
      * getEmployeeSalaryStructure | this function is only for view employees salary structure, this should and will not change or update any data related to salary
      *
      * @param  mixed $employeeId
      * @param  mixed $formate_month_year
      * @return void
      */
     function getEmployeeSalaryStructure($employeeId, $formate_month_year = "")
     {

          $payslipDetail = [];
          try
          {
               if ($formate_month_year == "")
               {
                    $formate_month_year = date("Y") . '-' . date("m");
               }
               // Salary Structure Start

               $employeeDetails = Employee::where("id", $employeeId)->first();

               $basicSalary = $employeeDetails->salary;

               $companyWorkingDays = getCompanyWorkingDays();
               // $companyWorkingDays = 31;

               $extraLeaveDays = 0;

               $employeeWorkingDays = abs($companyWorkingDays - $extraLeaveDays);
               // $employeeWorkingDays = 28;

               $payableSalary = $basicSalary;

               $extraData = [
                    "companyWorkingDays" => $companyWorkingDays,
                    "employeeWorkingDays" => $employeeWorkingDays,
                    "formate_month_year" => $formate_month_year,
                    "extraLeaveDaysTotalAmount" => 0
               ];

               $paySlipData = [];

               $basicSalary = $employeeDetails->salary;
               $paySlipData["employeeBasicSalary"] = $basicSalary;

               $yearlyCtc = $employeeDetails->ctc_annual;
               $monthlyCtc = numberFormat($yearlyCtc / 12);
               $monthelySalaryByCtc = numberFormat(
                    ($monthlyCtc * $employeeDetails->ctc_percentage) / 100,
               );
               $yearlySalaryByCtc = numberFormat($monthelySalaryByCtc * 12);

               $paySlipData['yearlyCtc'] = $yearlyCtc;
               $paySlipData['monthlyCtc'] = $monthlyCtc;
               $paySlipData['monthelySalaryByCtc'] = $monthelySalaryByCtc;
               $paySlipData['yearlySalaryByCtc'] = $yearlySalaryByCtc;

               $paySlipData['ctcPercentage'] = $employeeDetails->ctc_percentage;

               // Earnings
               $allowancesTotal = 0;
               $commissionTotal = 0;
               $overtimeTotal = 0;
               $otherPaymentsTotal = 0;
               $totalEarnings = 0; // Total of all earnings

               $allowancesDataRaw = Employee::payslipAllowanceEmployeeWise($employeeDetails->id, $extraData, [], false);
               $allowancesTotal = $allowancesDataRaw["total"];
               $allowancesData = $allowancesDataRaw["data"];

               $commissionDataRaw = Employee::paySlipCommissionEmployeeWise($employeeDetails->id, $extraData, false);
               $commissionTotal = $commissionDataRaw["total"];
               $commissionData = $commissionDataRaw["data"];

               // overtime calculation is pending | need to discuss then calculate this
               $overtimeDataRaw = Employee::paySlipOvertime($employeeDetails->id);
               $overtimeTotal = $overtimeDataRaw["total"];
               $overtimeData = $overtimeDataRaw["data"];

               $otherPaymentsDataRaw = Employee::paySlipOtherPaymentEarningsEmployeeWise($employeeDetails->id, $extraData, false);
               $otherPaymentsTotal = $otherPaymentsDataRaw["total"];
               $otherPaymentsData = $otherPaymentsDataRaw["data"];

               $totalEarnings = ($allowancesTotal + $commissionTotal + $overtimeTotal + $otherPaymentsTotal);

               $paySlipData["earnings"] = [
                    "allowances" => [
                         "total" => $allowancesTotal,
                         "data" => $allowancesData
                    ],
                    "commission" => [
                         "total" => $commissionTotal,
                         "data" => $commissionData
                    ],

                    // calculation is pending | need to discuss then calculate this
                    "overtime" => [
                         "total" => $overtimeTotal,
                         "data" => $overtimeData
                    ],
                    "otherPayments" => [
                         "total" => $otherPaymentsTotal,
                         "data" => $otherPaymentsData
                    ],
                    // "totalEarnings" => $totalEarnings,
               ];

               $statutoryBonusDataRaw = Employee::statutoryBonusEarningsEmployeeWise($employeeDetails->id, $extraData);
               $statutoryBonusTotal = $statutoryBonusDataRaw["total"];
               $statutoryBonusData = $statutoryBonusDataRaw["data"];

               $paySlipData["statutoryBonus"] = [
                    "total" => $statutoryBonusTotal,
                    "data" => $statutoryBonusData
               ];

               $paySlipData["totalEarnings"] = $totalEarnings;
               // Earnings



               // Deductions

               $saturationTotal = 0;
               $loanTotal = 0;
               $leaveTotal = 0;
               $totalDeductions = 0; // total of all deductions

               $saturationDataRaw = Employee::paySlipSaturationDeductionEmployeeWise($employeeDetails->id, $extraData, false);
               $saturationTotal = $saturationDataRaw["total"];
               $saturationData = $saturationDataRaw["data"];

               // Loan Deduction
               $loanDataRaw = Employee::paySlipLoanDeduction($employeeDetails->id, $formate_month_year, false);
               $loanTotal = $loanDataRaw["total"];
               $loanData = $loanDataRaw["data"];

               // Leave Deduction
               $leaveDataRaw = Employee::paySlipLeaveDeduction($employeeDetails->id, $formate_month_year, false);
               $leaveTotal = $leaveDataRaw["total"];
               $leaveData = $leaveDataRaw["data"];

               // Other Payment Deductions Calculation Like (Employee Provident Fund, Employees State Insurance, Employees Professional Tax)
               $grossSalary = numberFormat(round($payableSalary + $totalEarnings));
               $paySlipData["grossSalary"] = $grossSalary;
               // $grossSalary = $basicSalary;

               // Employee Provident Fund of Employee
               $providentFundEmployeeDeductionDataRaw = Employee::providentFundEmployeeDeduction($employeeDetails->id, $grossSalary, $extraData, false);
               $providentFundEmployeeDeductionTotal = $providentFundEmployeeDeductionDataRaw["total"];
               $providentFundEmployeeDeductionData = $providentFundEmployeeDeductionDataRaw["data"];
               // Employee Provident Fund of Employee

               // Employees State Insurance of Employee
               $employeesStateInsuranceEmployeeDeductionDataRaw = Employee::employeesStateInsuranceEmployeeDeduction($employeeDetails->id, $grossSalary, $extraData);
               $employeesStateInsuranceEmployeeDeductionTotal = $employeesStateInsuranceEmployeeDeductionDataRaw["total"];
               $employeesStateInsuranceEmployeeDeductionData = $employeesStateInsuranceEmployeeDeductionDataRaw["data"];
               // Employees State Insurance of Employee


               // Employees Professional Tax
               $employeeProfessionalTaxDeductionDataRaw = Employee::employeeProfessionalTaxDeduction($employeeDetails->id, $grossSalary, $extraData);
               $employeeProfessionalTaxDeductionTotal = $employeeProfessionalTaxDeductionDataRaw["total"];
               $employeeProfessionalTaxDeductionData = $employeeProfessionalTaxDeductionDataRaw["data"];
               // Employees Professional Tax

               $tdsDeductionDataRaw = Employee::tdsDeductionEmployeeWise($employeeDetails->id, $extraData, false);
               $tadDeductionTotal = $tdsDeductionDataRaw["total"];
               $tadDeductionData = $tdsDeductionDataRaw["data"];


               // $totalDeductions = ($saturationTotal + $loanTotal + $leaveTotal + $providentFundEmployeeDeductionTotal + $employeesStateInsuranceEmployeeDeductionTotal + $employeeProfessionalTaxDeductionTotal + $tadDeductionTotal);

               $totalDeductions = ($saturationTotal + $loanTotal + $providentFundEmployeeDeductionTotal + $employeesStateInsuranceEmployeeDeductionTotal + $employeeProfessionalTaxDeductionTotal + $tadDeductionTotal);

               $paySlipData["deductions"] = [
                    "saturation" => [
                         "total" => $saturationTotal,
                         "data" => $saturationData
                    ],

                    // Loan Deduction
                    "loan" => [
                         "total" => $loanTotal,
                         "data" => $loanData
                    ],
                    // Loan Deduction

                    // Employee Provident Fund of Employee
                    "providentFundEmployeeDeduction" => [
                         "total" => $providentFundEmployeeDeductionTotal,
                         "data" => $providentFundEmployeeDeductionData
                    ],
                    // Employee Provident Fund of Employee

                    // Employees State Insurance of Employee
                    "employeesStateInsuranceEmployeeDeduction" => [
                         "total" => $employeesStateInsuranceEmployeeDeductionTotal,
                         "data" => $employeesStateInsuranceEmployeeDeductionData
                    ],
                    // Employees State Insurance of Employee

                    // Employees Professional Tax
                    "employeeProfessionalTaxDeduction" => [
                         "total" => $employeeProfessionalTaxDeductionTotal,
                         "data" => $employeeProfessionalTaxDeductionData
                    ],
                    // Employees Professional Tax


                    // Tax Deduction at Sourse
                    "tdsDeductions" => [
                         "total" => $tadDeductionTotal,
                         "data" => $tadDeductionData
                    ],
                    // Tax Deduction at Sourse
               ];

               // Leave Deduction
               $paySlipData["leave"] = [
                    "total" => $leaveTotal,
                    "data" => $leaveData
               ];
               // Leave Deduction

               $paySlipData["totalDeductions"] = $totalDeductions;


               // Employee Provident Fund of Company
               $providentFundEompanyDeductionDataRaw = Employee::providentFundCompanyDeduction($employeeDetails->id, $grossSalary, $extraData);
               $providentFundCompanyDeductionTotal = $providentFundEompanyDeductionDataRaw["total"];
               $providentFundCompanyDeductionData = $providentFundEompanyDeductionDataRaw["data"];

               $paySlipData["providentFundCompanyDeduction"] = [
                    "total" => $providentFundCompanyDeductionTotal,
                    "data" => $providentFundCompanyDeductionData
               ];

               // Employee Provident Fund of Company

               // Employees State Insurance of Employee
               $employeesStateInsuranceCompanyDeductionDataRaw = Employee::employeesStateInsuranceCompanyDeduction($employeeDetails->id, $grossSalary, $extraData);
               $employeesStateInsuranceCompanyDeductionTotal = $employeesStateInsuranceCompanyDeductionDataRaw["total"];
               $employeesStateInsuranceCompanyDeductionData = $employeesStateInsuranceCompanyDeductionDataRaw["data"];

               $paySlipData["employeesStateInsuranceCompanyDeduction"] = [
                    "total" => $employeesStateInsuranceCompanyDeductionTotal,
                    "data" => $employeesStateInsuranceCompanyDeductionData
               ];

               // Employees State Insurance of Employee
               // Deductions

               // $finalPayableSalary = numberFormat(round((($payableSalary + $totalEarnings + $statutoryBonusTotal) - ($totalDeductions))));
               $finalPayableSalary = numberFormat(round((($payableSalary + $totalEarnings) - ($totalDeductions))));

               $paySlipData["employeeNewPayableSalary"] = $finalPayableSalary;

               $payslipDetail = json_decode(json_encode($paySlipData));

               // Salary Structure End
          }
          catch (Exception | Throwable $ex)
          {
               // $payslipDetail = [];
          }

          return $payslipDetail;
     }
}

if (!function_exists('getEmployeeDetails'))
{
     function getEmployeeDetails($employeeId)
     {
          return Employee::where("id", $employeeId)->with("branch", "designation")->first();
     }
}

if (!function_exists('leaveBalanceRestrictionCompanyIds'))
{
     function leaveBalanceRestrictionCompanyIds()
     {
          return [
               // Add company creator IDs here to restrict employee leave by available balance.
               // 308
          ];
     }
}

if (!function_exists('isLeaveBalanceRestrictionEnabled'))
{
     function isLeaveBalanceRestrictionEnabled($creatorId = null)
     {
          return true;

          // $creatorId = $creatorId ?? Auth::user()->creatorId();

          // return in_array((int) $creatorId, leaveBalanceRestrictionCompanyIds(), true);
     }
}

if (!function_exists('normalizeLeaveDaysValue'))
{
     function normalizeLeaveDaysValue($value)
     {
          $value = trim((string) $value);
          $value = str_replace(',', '.', $value);
          if (preg_match('/^\d+\s+\d+$/', $value))
          {
               $value = preg_replace('/\s+/', '.', $value);
          }
          else
          {
               $value = preg_replace('/\s+/', '', $value);
          }

          return round((float) $value, 2);
     }
}

if (!function_exists('getAppliedLeaveDays'))
{
     function getAppliedLeaveDays($leaveTypeDay, $startDate, $endDate)
     {
          if (in_array((string) $leaveTypeDay, ['0', '0.5'], true))
          {
               return 0.5;
          }

          $startDate = new DateTime($startDate);
          $endDate = new DateTime($endDate);
          $endDate->add(new DateInterval('P1D'));

          return normalizeLeaveDaysValue($startDate->diff($endDate)->days);
     }
}

if (!function_exists('validateEmployeeLeaveBalance'))
{
     function validateEmployeeLeaveBalance($employeeId, $leaveTypeId, $appliedLeaveDays, $startDate)
     {
          $employee = Employee::where("id", $employeeId)->first();

          if ($employee == null)
          {
               return ['status' => 0, 'message' => __('Employee not found.')];
          }

          $year = date("Y", strtotime($startDate));

          $leaveBalance = UserLeaveBalanceYear::where("leave_type_id", $leaveTypeId)
               ->where("leaves_year", $year)
               ->where("user_id", $employeeId)
               ->pluck("pending_leaves")
               ->sum(function ($pendingLeaves)
               {
                    return normalizeLeaveDaysValue($pendingLeaves);
               });

          $leaveBalance = normalizeLeaveDaysValue($leaveBalance);
          $appliedLeaveDays = normalizeLeaveDaysValue($appliedLeaveDays);

          if (!isLeaveBalanceRestrictionEnabled($employee->created_by))
          {
               return [
                    'status' => 1,
                    'message' => '',
                    'leave_balance' => $leaveBalance,
                    'applied_leave_days' => $appliedLeaveDays,
               ];
          }

          // No balance left: skip restriction and allow leave application.
          if ($leaveBalance <= 0)
          {
               return [
                    'status' => 1,
                    'message' => '',
                    'leave_balance' => $leaveBalance,
                    'applied_leave_days' => $appliedLeaveDays,
               ];
          }

          if ($appliedLeaveDays > $leaveBalance)
          {
               return [
                    'status' => 0,
                    'message' => __('You cannot apply leave more than your leave balance.'),
                    'leave_balance' => $leaveBalance,
                    'applied_leave_days' => $appliedLeaveDays,
               ];
          }

          return [
               'status' => 1,
               'message' => '',
               'leave_balance' => $leaveBalance,
               'applied_leave_days' => $appliedLeaveDays,
          ];
     }
}

if (!function_exists('employeeLeaveBalanceOperations'))
{
     function employeeLeaveBalanceOperations($leaveId, $currentLeaveStatus, $oldLeaveStatus, $created_by = 0)
     {
          $leave = Leave::where("id", $leaveId)->first();

          if ($leave == null)
          {
               return;
          }

          // leave balance operations
          // dd($currentLeaveStatus, $oldLeaveStatus);
          if ($currentLeaveStatus == "Approved")
          {
               // deduct leave balance
               $formate_month_year = date("Y-m", strtotime($leave->start_date));
               $leaveDataRaw = Employee::paySlipLeaveDeduction($leave->employee_id, $formate_month_year, true, $leave->id, $created_by);

               $extraLeaveDays = $leaveDataRaw["extraLeaveDays"];
               $extraLeaveDaysTotalAmount = $leaveDataRaw["total"];

               $leave->deducted_leave_balance = ($leaveDataRaw['deducted_leave_balance_total'] ?? 0);
               $leave->extra_leave_days = ($leaveDataRaw['extra_leave_days_total'] ?? 0);
               $leave->save();

               // return [$leave, $leaveDataRaw];

               // deduct leave balance
          }
          else if ($oldLeaveStatus == "Approved" && $currentLeaveStatus == "Reject")
          {
               // add leave balance
               if ($leave->deducted_leave_balance > 0) // add this leave balance to
               {
                    $incrementLeaveBalance = number_format($leave->deducted_leave_balance, 2);

                    UserLeaveBalance::where("leave_type_id", $leave->leave_type_id)
                         ->where("user_id", $leave->employee_id)
                         ->increment('pending_leaves', $incrementLeaveBalance);

                    UserLeaveBalanceYear::where("leave_type_id", $leave->leave_type_id)
                         ->where("user_id", $leave->employee_id)
                         ->where("leaves_year", date("Y", strtotime($leave->start_date)))
                         ->increment('pending_leaves', $incrementLeaveBalance);
               }

               $leave->deducted_leave_balance = 0;
               $leave->extra_leave_days = 0;
               $leave->save();
               // add leave balance

               // return [
               //      "deducted_leave_balance" => $leave->deducted_leave_balance,
               //      "incrementLeaveBalance" => $incrementLeaveBalance,
               // ];
          }



          // leave balance operations
     }
}

function getEmployeesBirthdaysList($excludeEmployees = [], int $withInDays = 15)
{
     $data = [];
     try
     {
          $dateAfter15Days = Carbon::now()->addDays($withInDays);

          $startDay = Carbon::now()->format('d');
          $endDay = $dateAfter15Days->format('d');

          $startMonth = Carbon::now()->format('m');
          $endMonth = $dateAfter15Days->format('m');


          $data = Employee::select("id", "user_id", "dob", "name", "employee_profile", "employee_profile as employee_profile_image", DB::raw("DATE_FORMAT(dob, '%m %d') dobd"))
               ->where(function ($query) use ($startDay, $startMonth, $endDay, $endMonth)
               {
                    if ($startMonth == $endMonth)
                    {
                         // If the date range is within the same month
                         $query->whereRaw('MONTH(dob) = ?', [$startMonth])
                              ->whereRaw('DAY(dob) BETWEEN ? AND ?', [$startDay, $endDay]);
                    }
                    else
                    {
                         // If the date range spans across two months
                         $query->where(function ($query) use ($startDay, $startMonth)
                         {
                              $query->whereRaw('MONTH(dob) = ?', [$startMonth])
                                   ->whereRaw('DAY(dob) >= ?', [$startDay]);
                         })
                              ->orWhere(function ($query) use ($endDay, $endMonth)
                              {
                                   $query->whereRaw('MONTH(dob) = ?', [$endMonth])
                                        ->whereRaw('DAY(dob) <= ?', [$endDay]);
                              });
                    }
               })
               ->where("created_by", Auth::user()->creatorId())
               ->where("employee_exit", 0)
               ->when(!empty($excludeEmployees), function ($query) use ($excludeEmployees)
               {
                    $query->whereNotIn("id", $excludeEmployees);
               })
               ->orderBy('dobd', 'ASC')
               ->get();

          // $data = Employee::select("id", "user_id", "dob", "name", "employee_profile", "employee_profile as employee_profile_image", DB::raw("DATE_FORMAT(dob, '%m %d') dobd"))
          //      ->whereRaw('DAY(dob) BETWEEN ? AND ?', [$startDay, $endDay])
          //      ->whereRaw('MONTH(dob) BETWEEN ? AND ?', [$startMonth, $endMonth])

          //      ->where("created_by", Auth::user()->creatorId())
          //      ->when(!empty($excludeEmployees), function ($query) use ($excludeEmployees)
          //      {
          //           $query->whereNotIn("id", $excludeEmployees);
          //      })
          //      ->orderBy('dobd', 'ASC')
          //      ->get();
     }
     catch (\Throwable $th)
     {
          //throw $th;
     }
     return $data;
}

function getWorkAnniversaryList($excludeEmployees = [])
{
     $data = [];
     try
     {
          $data = Employee::select("id", "name", "employee_profile", "employee_profile as employee_profile_image")
               ->whereDay("company_doj", date("d"))
               ->whereYear("company_doj", '!=', date("Y"))
               ->whereMonth("company_doj", date("m"))
               ->where("created_by", Auth::user()->creatorId())
               ->where("employee_exit", 0)
               ->when(!empty($excludeEmployees), function ($query) use ($excludeEmployees)
               {
                    $query->whereNotIn("id", $excludeEmployees);
               })
               ->orderBy("name", "ASC")
               ->get();
     }
     catch (Exception | Throwable $e)
     {
          // dd($e);
     }
     return $data;
}


function getMarriageAnniversaryList($excludeEmployees = [])
{
     $data = [];
     try
     {
          $data = Employee::select("id", "name", "employee_profile", "employee_profile as employee_profile_image")
               ->whereDay("marriage_anniversary_date", date("d"))
               ->whereMonth("marriage_anniversary_date", date("m"))
               ->where("created_by", Auth::user()->creatorId())
               ->where("employee_exit", 0)
               ->when(!empty($excludeEmployees), function ($query) use ($excludeEmployees)
               {
                    $query->whereNotIn("id", $excludeEmployees);
               })
               ->orderBy("name", "ASC")
               ->get();
     }
     catch (Exception | Throwable $e)
     {
          // dd($e);
     }
     return $data;
}
function getCountryNameById($country_id = [])
{
     $data = [];
     try
     {
          $data = Country::where("id", $country_id)->first()->name;
     }
     catch (Exception | Throwable $e)
     {
          // dd($e);
     }
     return $data;
}

function getStateNameById($state_id = [])
{
     $data = [];
     try
     {
          $data = State::where("state_id", $state_id)->first()->state_title;
     }
     catch (Exception | Throwable $e)
     {
          // dd($e);
     }
     return $data;
}

function getDistrictNameById($district_id = [])
{
     $data = [];
     try
     {
          $data = District::where("districtid", $district_id)->first()->district_title;
     }
     catch (Exception | Throwable $e)
     {
          // dd($e);
     }
     return $data;
}

function getTimeDataForExcelImport($excelTimeValue)
{
     $returnValue = "";
     try
     {
          // Convert the Excel time value to seconds
          $timeInSeconds = round($excelTimeValue * 86400); // 86400 seconds in a day

          // Calculate hours, minutes, and seconds
          $hours = floor($timeInSeconds / 3600);
          $minutes = floor(($timeInSeconds / 60) % 60);
          $seconds = $timeInSeconds % 60;

          // Create a Carbon instance from the extracted time
          $carbonTime = Carbon::createFromTime($hours, $minutes, $seconds);

          // Format the time as 'g:i A' (AM/PM format) and 'H:i:s' (24-hour format)
          $formattedTimeAMPM = $carbonTime->format('g:i A');
          $formattedTime24Hour = $carbonTime->format('H:i:s');

          $returnValue = $formattedTime24Hour;
     }
     catch (Exception | Throwable $ex)
     {
          $returnValue = "";
     }

     return $returnValue;
}


function isValidTime($time, $format = 'H:i:s')
{
     try
     {
          $dateTime = DateTime::createFromFormat($format, $time);
          return $dateTime && $dateTime->format($format) === $time;
     }
     catch (Exception | Throwable $ex)
     {
     }
     return false;
}

/**
 * reCalculateLeaveBalanceData | // if this leave is rejected or pending then deducted leave balance of this leave will be added back in leave balance from above function , so check if there is any other leave are approved then calculate its leave balance which should deduct and add extra days it there is extra days.
 *
 * @param  mixed $employeeId
 * @param  mixed $leaveNotInclude
 * @return void
 */
function reCalculateLeaveBalanceData($employeeId, $leaveNotInclude = [], $date = null)
{
     try
     {
          if ($date == null || empty($date))
          {
               $date = date("Y-m-d");
          }

          $years  = date("Y", strtotime($date));
          $month  = date("m", strtotime($date));

          $leaveChangeList = Leave::where('employee_id', '=', $employeeId)
               ->where('created_by', '=', Auth::user()->creatorId())
               ->when(!empty($leaveNotInclude), function ($query) use ($leaveNotInclude)
               {
                    $query->whereNotIn("id", $leaveNotInclude);
               })
               ->whereYear('start_date', $years)
               ->whereMonth('start_date', $month)
               ->where("status", "Approved")
               ->orderBy('start_date', 'ASC')
               ->orderBy('id', 'ASC')
               ->get();

          foreach ($leaveChangeList as $leaveChangeRow)
          {
               if ($leaveChangeRow->deducted_leave_balance > 0) // add this leave balance to
               {
                    $incrementLeaveBalance = number_format($leaveChangeRow->deducted_leave_balance, 2);

                    UserLeaveBalance::where("leave_type_id", $leaveChangeRow->leave_type_id)
                         ->where("user_id", $leaveChangeRow->employee_id)
                         ->increment('pending_leaves', $incrementLeaveBalance);

                    UserLeaveBalanceYear::where("leave_type_id", $leaveChangeRow->leave_type_id)
                         ->where("user_id", $leaveChangeRow->employee_id)
                         ->where("leaves_year", date("Y", strtotime($leaveChangeRow->start_date)))
                         ->increment('pending_leaves', $incrementLeaveBalance);
               }

               Leave::where("id", $leaveChangeRow->id)
                    ->where('employee_id', '=', $leaveChangeRow->employee_id)
                    ->update([
                         "deducted_leave_balance" => 0,
                         "extra_leave_days" => 0,
                    ]);

               $oldLeaveStatusForOperations = "";
               employeeLeaveBalanceOperations($leaveChangeRow->id, $leaveChangeRow->status, $oldLeaveStatusForOperations);
          }
     }
     catch (Exception | Throwable $ex)
     {
          // dd($ex);
     }
}

function getLeavesDataforPaySlip($employeeId, $salaryCalculationStartDate, $salaryCalculationEndDate)
{
     $responseData = [];
     try
     {
          $employee = Employee::where('id', $employeeId)->first();
          $basic_salary = $employee->salary;

          $companyWorkingDays = getCompanyWorkingDays();

          $oneDaysalary = 0;
          if ($companyWorkingDays > 0)
          {
               $oneDaysalary = $basic_salary / $companyWorkingDays;
          }

          DB::enableQueryLog();


          $leaveChangeList = Leave::select(
               "*",
               // DB::raw('sum(total_leave_days) as total_leave_days_count')
          )
               ->where('employee_id', '=', $employeeId)
               ->where('created_by', '=', Auth::user()->creatorId())

               ->whereDate('start_date', '>=', $salaryCalculationStartDate)
               ->whereDate('end_date', '<=', $salaryCalculationEndDate)

               ->where("status", "Approved")
               ->where("extra_leave_days", ">", 0)
               ->orderBy('start_date', 'ASC')
               ->orderBy('id', 'ASC')
               ->get();

          // dd($leaveChangeList, DB::getQueryLog());

          $total_leave_days_count = $leaveChangeList->sum("total_leave_days");

          $leaveDeductionData = [];
          $leaveDeductionAmountTotal = 0;
          $totalExtraDays = 0;


          foreach ($leaveChangeList as $leaveChangeRow)
          {
               $leaveDeductionAmount = ($oneDaysalary * abs($leaveChangeRow->extra_leave_days));
               $leaveTypeData = LeaveType::where("id", $leaveChangeRow->leave_type_id)->first();

               if ($leaveTypeData != null)
               {
                    $leaveDeductionData[] = [
                         'leave_id'    => $leaveChangeRow->id,
                         'employee_id'    => $employeeId,
                         // 'number_of_days' => $leaveChangeRow->total_leave_days_count,
                         'number_of_days' => $total_leave_days_count,
                         'leave_title'    => $leaveTypeData->title,
                         'leave_type_id'    => $leaveTypeData->id,
                         'extradays'      => abs($leaveChangeRow->extra_leave_days),
                         'amountdeduct'   => $leaveDeductionAmount,
                         'name' => $leaveTypeData->title,
                         'shortName' => $leaveTypeData->title,
                         'type' => "fixed",
                         'pencentage' => 0,
                         'amount' => $leaveDeductionAmount,
                         'dbObject' => $leaveChangeRow->toArray()
                    ];

                    $leaveDeductionAmountTotal += $leaveDeductionAmount;

                    $totalExtraDays += $leaveChangeRow->extra_leave_days;
               }
          }

          $responseData = [
               "total" => $leaveDeductionAmountTotal,
               'data' => $leaveDeductionData,
               'extraLeaveDays' => $totalExtraDays,
          ];
     }
     catch (Exception | Throwable $ex)
     {
     }
     return $responseData;
}

function getLeavesDataforPaySlip_old_before_new_changes_for_custom_salary_slab($employeeId, $date = null)
{
     $responseData = [];
     try
     {
          if ($date == null || empty($date))
          {
               $date = date("Y-m-d");
          }

          $years  = date("Y", strtotime($date));
          $month  = date("m", strtotime($date));


          $employee = Employee::where('id', $employeeId)->first();
          $basic_salary = $employee->salary;

          $companyWorkingDays = getCompanyWorkingDays();

          $oneDaysalary = 0;
          if ($companyWorkingDays > 0)
          {
               $oneDaysalary = $basic_salary / $companyWorkingDays;
          }

          DB::enableQueryLog();


          $leaveChangeList = Leave::select(
               "*",
               // DB::raw('sum(total_leave_days) as total_leave_days_count')
          )
               ->where('employee_id', '=', $employeeId)
               ->where('created_by', '=', Auth::user()->creatorId())
               ->whereYear('start_date', $years)
               ->whereMonth('start_date', $month)
               ->where("status", "Approved")
               ->where("extra_leave_days", ">", 0)
               ->orderBy('start_date', 'ASC')
               ->orderBy('id', 'ASC')
               ->get();

          // dd($leaveChangeList, DB::getQueryLog());

          $total_leave_days_count = $leaveChangeList->sum("total_leave_days");

          $leaveDeductionData = [];
          $leaveDeductionAmountTotal = 0;
          $totalExtraDays = 0;


          foreach ($leaveChangeList as $leaveChangeRow)
          {
               $leaveDeductionAmount = ($oneDaysalary * abs($leaveChangeRow->extra_leave_days));
               $leaveTypeData = LeaveType::where("id", $leaveChangeRow->leave_type_id)->first();

               if ($leaveTypeData != null)
               {
                    $leaveDeductionData[] = [
                         'leave_id'    => $leaveChangeRow->id,
                         'employee_id'    => $employeeId,
                         // 'number_of_days' => $leaveChangeRow->total_leave_days_count,
                         'number_of_days' => $total_leave_days_count,
                         'leave_title'    => $leaveTypeData->title,
                         'leave_type_id'    => $leaveTypeData->id,
                         'extradays'      => abs($leaveChangeRow->extra_leave_days),
                         'amountdeduct'   => $leaveDeductionAmount,
                         'name' => $leaveTypeData->title,
                         'shortName' => $leaveTypeData->title,
                         'type' => "fixed",
                         'pencentage' => 0,
                         'amount' => $leaveDeductionAmount,
                         'dbObject' => $leaveChangeRow->toArray()
                    ];

                    $leaveDeductionAmountTotal += $leaveDeductionAmount;

                    $totalExtraDays += $leaveChangeRow->extra_leave_days;
               }
          }

          $responseData = [
               "total" => $leaveDeductionAmountTotal,
               'data' => $leaveDeductionData,
               'extraLeaveDays' => $totalExtraDays,
          ];
     }
     catch (Exception | Throwable $ex)
     {
     }
     return $responseData;
}

function checkRequestType($requestType = "web")
{
     $isRequestType = false;
     try
     {
          $isRequestType = in_array($requestType, Route::current()->computedMiddleware);
     }
     catch (\Throwable $th)
     {
          $isRequestType = false;
     }
     return $isRequestType;
}
