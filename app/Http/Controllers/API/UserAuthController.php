<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Auth\PasswordResetLinkController;
use URL;
use Exception;
use Throwable;
use App\Models\User;
use App\Models\Utility;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EXPTable;
use App\Models\IpRestrict;
use Illuminate\Http\Request;
use App\Models\DependentData;
use App\Models\EdcutionTable;
use App\Models\AttendanceEmployee;
use App\Models\LeaveType;
use App\Models\Leave as LocalLeave;
use App\Models\EarlyLateCommingLeave;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\EmployeeDocument;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\NotificationModel;
use App\Models\WorkingShiftEmployees;
use App\Models\WorkingShifts;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Services\EmployeeDataChangeLogService;
use App\Services\EmployeeDataSnapshotService;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;



use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
     public function get_attendance_list(request $request)
     {
          try
          {

               $emp = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;

               $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp);

               if ($request->type == 'monthly')
               {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));


                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));

                    $attendanceEmployee->whereBetween(
                         'date',
                         [
                              $start_date,
                              $end_date,
                         ]
                    );
               }
               elseif ($request->type == 'daily')
               {
                    $attendanceEmployee->where('date', $request->date);
               }
               else
               {
                    $month      = date('m');
                    $year       = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));
                    $attendanceEmployee->whereBetween(
                         'date',
                         [
                              $start_date,
                              $end_date,
                         ]
                    );
               }

               $user_device_configurations = json_decode(Auth::user()->user_device_configurations ?? "");

               $attendanceEmployee = $attendanceEmployee->orderBy('id', 'desc')->get();
               if (!empty($attendanceEmployee))
               {
                    foreach ($attendanceEmployee as $row)
                    {
                         $row->clock_in = ($row->clock_in != '00:00:00') ? Auth::user()->timeFormat($row->clock_in) : '00:00:00';
                         $row->clock_out = ($row->clock_out != '00:00:00') ? Auth::user()->timeFormat($row->clock_out) : '00:00:00';
                    }
                    $response = [
                         'status' => 1,
                         'message' => 'success',
                         'data' => $attendanceEmployee,
                         'user_device_configurations' => $user_device_configurations,
                    ];
                    return $response;
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data not found',
                         'user_device_configurations' => $user_device_configurations,
                    ];
                    return $response;
               }
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }


     public function get_attendance(request $request)
     {
          try
          {
               $employeeId      = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;
               $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->first();


               // get employee working hours

               $settings = Utility::settings();
               $officeTime['startTime'] = timeFormat($settings['company_start_time'] ?? '');
               $officeTime['endTime']   = timeFormat($settings['company_end_time'] ?? '');
               $officeTime['flexiHours'] = $settings['company_flexi_hours'] ?? '';
               $officeTime['flexiDays'] = $settings['company_flexi_days'] ?? '';

               // dd(Utility::getValByName('company_start_time'));

               // check if employee is assigned to any shift calculate according that shift
               $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employeeId)->first();
               $employeeShift = null;
               if ($workingShiftEmployees != null)
               {
                    $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
                    if ($employeeShift != null)
                    {
                         $officeTime['startTime'] = timeFormat($employeeShift->start_time);
                         $officeTime['endTime'] = timeFormat($employeeShift->end_time);

                         $officeTime['flexiHours'] = $employeeShift->flexi_hours;
                         $officeTime['flexiDays'] = $employeeShift->flexi_days;
                    }
               }
               // get employee working hours



               if (empty($todayAttendance))
               {
                    $data = [
                         // 'is_clockin' => true,
                         // 'is_clockout' => true,
                         'is_clockin' => false,  //button click thase simple
                         'is_clockout' => true,
                         'officeTime' => $officeTime
                    ];
                    $response = [
                         'status' => 1,
                         'message' => 'success',
                         'data' => $data,
                    ];
                    return $response;
               }
               else
               {
                    if ($todayAttendance->clock_in && $todayAttendance->clock_out != "00:00:00" && $todayAttendance->clock_out != "" && !is_null($todayAttendance->clock_out) && $todayAttendance->clock_out != "null")
                    {
                         $data = [
                              'is_clockin' => false,
                              'is_clockout' => true,
                              'officeTime' => $officeTime
                         ];
                         $response = [
                              'status' => 1,
                              'message' => 'success',
                              'data' => $data,
                         ];
                         return $response;
                    }
                    else
                    {

                         $data = [
                              'is_clockin' => true,
                              'is_clockout' => false,
                              'officeTime' => $officeTime
                         ];
                         $response = [
                              'status' => 1,
                              'message' => 'success',
                              'data' => $data,
                         ];
                         return $response;
                    }
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
     }

     public function login(Request $request)
     {
          try
          {
               $data = $request->validate([
                    'email' => 'email|required',
                    'password' => 'required'
               ]);
               $data = array(
                    'email'  => $request->get('email'),
                    'password' => $request->get('password')
               );

               if (auth()->attempt($data))
               {
                    $customer_data =  User::find(Auth::id());

                    if ($request->has("device_token") && !empty($request->device_token))
                    {
                         $customer_data->device_token = $request->device_token;
                    }

                    // 'device_type', Platform.OS === 'android' ? '2' : ‘1.   //{0=web,1=ios,2=android}
                    $customer_data->device_type = $request->device_type ?? 0;
                    $customer_data->user_device_configurations = json_encode($request->user_device_configurations ?? "");
                    $customer_data->save();

                    $user_token = $customer_data->createToken('api token')->accessToken;
                    $customer_data->token = $user_token;

                    $emp = Employee::select("*", "employee_profile as employee_profile_image")->where('user_id', '=', $customer_data->id)->first();
                    if ($emp == null)
                    {
                         auth()->logout();
                         $response = [
                              'status' => 0,
                              'message' => __('Please Login with Employee credentials.'),
                         ];
                         return $response;
                    }
                    else if ($emp->hasExited())
                    {
                         auth()->logout();
                         $response = [
                              'status' => 0,
                              'message' => __("You're not allowed to login."),
                         ];
                         return $response;
                    }
                    else if (!$emp->isAppLoginAllowed())
                    {
                         auth()->logout();
                         $response = [
                              'status' => 0,
                              'message' => __('App login is not enabled for your account. Please contact the administrator.'),
                         ];
                         return $response;
                    }


                    // get employee working hours


                    $officeTime['startTime'] = timeFormat(Utility::getValByName('company_start_time'));
                    $officeTime['endTime']   = timeFormat(Utility::getValByName('company_end_time'));

                    $officeTime['flexiHours'] = Utility::getValByName('company_flexi_hours');
                    $officeTime['flexiDays'] = Utility::getValByName('company_flexi_days');

                    // dd(Utility::getValByName('company_start_time'));

                    // check if employee is assigned to any shift calculate according that shift
                    $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0)->first();
                    $employeeShift = null;
                    if ($workingShiftEmployees != null)
                    {
                         $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
                         if ($employeeShift != null)
                         {
                              $officeTime['startTime'] = timeFormat($employeeShift->start_time);
                              $officeTime['endTime'] = timeFormat($employeeShift->end_time);

                              $officeTime['flexiHours'] = $employeeShift->flexi_hours;
                              $officeTime['flexiDays'] = $employeeShift->flexi_days;
                         }
                    }
                    // get employee working hours


                    $customer_data->workingData = $officeTime;

                    $customer_data->company_doj = $emp->getRawOriginal("company_doj");
                    $customer_data->isTodayWorkAnniversary = (date('Y', strtotime($emp->getRawOriginal("company_doj"))) != date("Y") && date('m-d', strtotime($emp->getRawOriginal("company_doj"))) == date("m-d") ? true : false);

                    $isTodayMarriageAnniversary = false;
                    if ($emp->marital_status == "Married" || $emp->marital_status == "Widowed")
                    {
                         if ($emp->marriage_anniversary_date == date("Y-m-d"))
                         {
                              $isTodayMarriageAnniversary = true;
                         }
                    }
                    $customer_data->isTodayMarriageAnniversary = $isTodayMarriageAnniversary;

                    $customer_data->geofencing_status = $emp->geofencing_status;
                    $customer_data->branch_id = $emp->branch_id;

                    $customer_data->geofencing_status = $emp->geofencing_status;
                    $customer_data->multi_clock_in_out_status = $emp->geofencing_status;
                    $customer_data->geofencing_radius = ($emp->branch->geofencing_radius ?? 0);
                    $customer_data->employee_id = $emp->employee_id;
                    $customer_data->employee_profile_image = $emp->employee_profile_image;
                    $customer_data->personal_email = $emp->personal_email;
                    $customer_data->add_multiple_employee_attendance = $customer_data->hasPermissionTo('Add Multiple Employee Attendance') ? 1 : 0;

                    $response = [
                         'status' => 1,
                         'message' => 'success',
                         'data' => $customer_data,
                    ];

                    return $response;
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => __('These credentials do not match our records'),
                    ];
                    return $response;
               }
          }
          catch (Exception | Throwable $e)
          {
               return error_msg(showExceptionMessage($e));
          }
     }



     public function clockInOutBranchList(Request $request)
     {
          try
          {
               $user =  User::find(Auth::id());
               $data = Branch::where('created_by', '=', Auth::user()->creatorId())
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->pluck('name', 'id')
                    ->prepend('All', 0)
                    ->map(function ($name, $id)
                    {
                         return [
                              'id' => $id,
                              'name' => $name,
                         ];
                    })
                    ->values(); // Optional: to reset the keys to numeric indices

               return response()->json([
                    'status' => 1,
                    'message' =>  __("Branch List."),
                    'data' => $data,
               ]);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }
     public function clockInOutEmployeesList(Request $request)
     {
          try
          {
               // Get the page_no parameter, default to 1 if not provided
               $pageNo = $request->page  ?? 1;
               // Get the search query parameter, default to an empty string if not provided
               $search = $request->search ?? '';
               // Define the number of items per page
               $perPage = 25;

               // Query employees with optional search and pagination

               // add last clock in, out status

               // Auth::user()->hasPermissionTo('Add Multiple Employee Attendance')

               $user =  User::find(Auth::id());
               $userEmployee = Employee::select("id", "name", "user_id", "branch_id")->where('user_id', '=', $user->id)->first();
               if ($userEmployee == null)
               {
                    $response = [
                         'status' => 0,
                         'message' => __('Please Login with Employee credentials.'),
                    ];
                    return $response;
               }

               $branchIds = $request->branch_ids ?? [];
               if (empty($branchIds))
               {
                    $branchIds = [$userEmployee->branch_id];
               }
               else
               {
                    if (in_array(0, $branchIds))
                    {
                         $branchIds = Branch::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get()->pluck("id")->toArray();
                    }
               }

               // dd(Auth::user()->creatorId());

               $employees = Employee::select(
                    "id",
                    "employee_id",
                    "name",
                    "phone",
                    "email",
                    "employee_profile",
                    "employee_profile as employee_profile_image",
                    "geofencing_status",
                    "branch_id",
                    "created_by",
                    "id as last_clock_in_out_data"
               )
                    ->when(!empty(trim($search)), function ($query)  use ($search)
                    {
                         $query
                              ->where("created_by", Auth::user()->creatorId())
                              ->where('name', 'like', '%' . $search . '%')
                              ->orWhere('email', 'like', '%' . $search . '%')
                              // ->orWhere('phone', 'like', '%' . $search . '%')
                         ;
                    })

                    ->whereIn("branch_id", $branchIds)
                    ->where("created_by", Auth::user()->creatorId())
                    ->orderBy('name', 'ASC')
                    ->paginate($perPage, ['*'], 'page', $pageNo);

               return response()->json([
                    'status' => 1,
                    'message' =>  __("Employees List."),
                    'data' => $employees->items(),
                    'current_page' => $employees->currentPage(),
                    'total_page' => $employees->lastPage(),
                    'total_items' => $employees->total(),
               ]);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function clock_in(Request $request)
     {
          Log::channel('clock_in_out')->info("_____________________ CLOCK IN API START _____________________");

          try
          {
               Log::channel('clock_in_out')->info("syncClockInClockOut API START - UserID: " . (Auth::user()->id ?? 'N/A'), $request->all());

               if ($request->hasFile('image'))
               {
                    $image = $request->file('image');
                    Log::channel('clock_in_out')->info("Image Upload Info", [
                         'original_name' => $image->getClientOriginalName(),
                         'extension' => $image->getClientOriginalExtension(),
                         'mime_type' => $image->getMimeType(),
                         'size' => $image->getSize(),
                         'real_path' => $image->getRealPath(),
                         'error' => $image->getError(),
                    ]);
               }
          }
          catch (\Throwable $th)
          {
               Log::channel('clock_in_out')->error("Image Log Error: " . $th->getMessage());
          }

          try
          {
               $latitude = $request->latitude ?? "";
               $longitude = $request->longitude ?? "";

               $metaData = json_encode($request->meta_data ?? "");

               $employeeId = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;

               if ($request->has("employee_id"))
               {
                    if ($request->employee_id > 0)
                    {
                         $employeeId = $request->employee_id;
                    }
               }

               $employeeDetails = Employee::where("id", $employeeId)->first();

               $date = date("Y-m-d");
               $time = date("H:i:s");

               $currentDateTime = date("Y-m-d H:i:s");

               $checkLastAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)
                    // ->orderBy("date", "DESC")
                    ->orderByRaw("date DESC, id DESC")
                    ->first();



               $isCreateClockIn = false;

               if ($checkLastAttendance == null || ($checkLastAttendance->clock_out ?? "") != "00:00:00") // if null then create new clock in entry
               {
                    $late = "00:00:00";
                    $is_flexi_hour_applied = 0;

                    //29-08 told to rmeove geofencing condition --Monali
                    //if ($employeeDetails->geofencing_status == 0) // calculate attendance data only if geofancing is no enabled for this employee
                    //{
                    // Attandence Calculation Late Comming, & Flexi hours
                    $clockInDatetime = date("Y-m-d H:i:s");

                    $calculateAttendanceData = (object)calculateAttendanceData($employeeId, $date, $clockInDatetime, null);
                    if ($calculateAttendanceData->status == 1)
                    {
                         if (!empty($calculateAttendanceData->data))
                         {
                              $calculateAttendanceDataRow = (object)$calculateAttendanceData->data ?? [];

                              $late = $calculateAttendanceDataRow->late_comming ?? $late;
                              $is_flexi_hour_applied = ($calculateAttendanceDataRow->is_flexi_hour_applied == true ? 1 : 0);
                         }
                    }
                    // Attandence Calculation Late Comming, & Flexi hours
                    //}

                    $imageName = null;
                    if ($request->hasFile('image'))
                    {
                         $rawFile = $request->file('image');
                         $originalExtension = $rawFile->getClientOriginalExtension();
                         if (!$originalExtension || $originalExtension === 'tmp')
                         {
                              $guessedExtension = $rawFile->guessExtension(); // Based on MIME
                              $originalExtension = $guessedExtension ?: 'jpg'; // Fallback to jpg
                         }

                         $image_name = time() . rand(100, 10000) . '.' . $originalExtension;

                         $uploadImageData = uploadFile($request->file('image'), $image_name, "images/clock_in_out/$employeeId/", true, true);
                         if ($uploadImageData['status'] == 1)
                         {
                              $imageName = $image_name;
                         }
                    }

                    DB::beginTransaction();

                    $employeeAttendance                = new AttendanceEmployee();
                    $employeeAttendance->employee_id   = $employeeId;
                    $employeeAttendance->date          = $date;
                    $employeeAttendance->status        = 'Present';

                    $employeeAttendance->clock_in_date_time = $currentDateTime;
                    $employeeAttendance->clock_out_date_time = null;

                    $employeeAttendance->clock_in = $time;
                    $employeeAttendance->clock_out     = '00:00:00';

                    $employeeAttendance->clock_in_latitude    = (string)$latitude;
                    $employeeAttendance->clock_in_longitude   = (string)$longitude;

                    $employeeAttendance->late          = $late;
                    $employeeAttendance->early_leaving = '00:00:00';
                    $employeeAttendance->overtime      = '00:00:00';
                    $employeeAttendance->total_rest    = '00:00:00';
                    $employeeAttendance->is_flexi_hours_used = $is_flexi_hour_applied;

                    $employeeAttendance->image = $imageName;
                    $employeeAttendance->clock_in_meta_data = $metaData;

                    $employeeAttendance->created_by = Auth::user()->id;
                    $employeeAttendance->geofencing_status = $employeeDetails->geofencing_status;

                    if ($request->has("webClockInOut"))
                    {
                         $employeeAttendance->clock_in_ip_address = ($request->ip() ?? null);
                    }

                    $employeeAttendance->save();

                    DB::commit();

                    // Last Clock In Data

                    $checkLastAttendance = AttendanceEmployee::select("id", "employee_id", "clock_in_date_time", "clock_out_date_time", "clock_in_latitude", "clock_in_longitude", "clock_out_latitude", "clock_out_longitude", "clock_in", "clock_out",  "image", "image as clocked_in_image", "clock_out_image", "clock_out_image as clocked_out_image", "date")
                         ->where('employee_id', '=', $employeeDetails->id)
                         // ->orderBy("date", "DESC")
                         ->orderByRaw("date DESC, id DESC")
                         ->first();

                    // Last Clock In Data

                    $response = [
                         'status' => 1,
                         'message' => __("You have successfully clocked in."),
                         "data" => $checkLastAttendance,
                    ];
               }
               else // return with validation message of already clocked in
               {
                    $checkLastAttendance = AttendanceEmployee::select("id", "employee_id", "clock_in_date_time", "clock_out_date_time", "clock_in_latitude", "clock_in_longitude", "clock_out_latitude", "clock_out_longitude", "clock_in", "clock_out",  "image", "image as clocked_in_image", "clock_out_image", "clock_out_image as clocked_out_image", "date")
                         ->where('employee_id', '=', $employeeDetails->id)
                         // ->orderBy("date", "DESC")
                         ->orderByRaw("date DESC, id DESC")
                         ->first();

                    $response = [
                         'status' => 0,
                         'message' => __("You have already clocked in, first clock out to clock in again."),
                         "data" => $checkLastAttendance,
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }

          Log::channel('clock_in_out')->info("_____________________ CLOCK IN API END _____________________");

          return $response;
     }

     public function clock_out(Request $request)
     {
          Log::channel('clock_in_out')->info("_____________________ CLOCK OUT API START _____________________");

          if ($request->has("is_debug"))
          {


               // Send Push Notification to App
               $usersData = User::where('id', Auth::id())->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();


               try
               {

                    $title_notification = __("Attendance Notification");
                    $body_notification = __("You have clocked out successfully.");

                    if (!$usersData->isEmpty())
                    {
                         $device_type = implode(',', $usersData->pluck('device_type')->toArray());
                         if ($request->is_from_kill_mode == 1)
                         {
                              if ($device_type == 2)
                              {

                                   $title = $title_notification;
                                   $body = $body_notification;

                                   $click_action = "dashboard_user_clock_out";
                                   $extraData = ['id' => "0"];

                                   sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                              }
                         }
                         else
                         {
                              $title = $title_notification;
                              $body = $body_notification;

                              $click_action = "dashboard_user_clock_out";
                              $extraData = ['id' => "0"];



                              $sendpPushNotification = sendpPushNotification($title, $body, $usersData, $click_action, $extraData, true);
                              //dd($sendpPushNotification);
                         }
                    }
               }
               catch (\Throwable $th)
               {
                    dd($th);
               }
               // Send Push Notification to App
               dd("In Debug");
          }

          try
          {
               Log::channel('clock_in_out')->info("syncClockInClockOut API START - UserID: " . (Auth::user()->id ?? 'N/A'), $request->all());

               if ($request->hasFile('image'))
               {
                    $image = $request->file('image');
                    Log::channel('clock_in_out')->info("Image Upload Info", [
                         'original_name' => $image->getClientOriginalName(),
                         'extension' => $image->getClientOriginalExtension(),
                         'mime_type' => $image->getMimeType(),
                         'size' => $image->getSize(),
                         'real_path' => $image->getRealPath(),
                         'error' => $image->getError(),
                    ]);
               }
          }
          catch (\Throwable $th)
          {
               Log::channel('clock_in_out')->error("Image Log Error: " . $th->getMessage());
          }

          try
          {
               $latitude = $request->latitude ?? "";
               $longitude = $request->longitude ?? "";

               $metaData = json_encode($request->meta_data ?? "");

               $employeeId = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;

               if ($request->has("employee_id"))
               {
                    if ($request->employee_id > 0)
                    {
                         $employeeId = $request->employee_id;
                    }
               }

               $employeeDetails = Employee::where("id", $employeeId)->first();

               $date = date("Y-m-d");
               $time = date("H:i:s");

               $currentDateTime = date("Y-m-d H:i:s");

               $checkLastAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)
                    // ->orderBy("date", "DESC")
                    ->orderByRaw("date DESC, id DESC")
                    ->first();

               $clockout_early = false;
               if ($checkLastAttendance != null && ($checkLastAttendance->clock_out ?? "") == "00:00:00") // if null then create new clock in entry
               {
                    $is_flexi_hour_applied = 0;
                    $late = "00:00:00";
                    $earlyLeaving = "00:00:00";
                    $overtime = '00:00:00';

                    // Attandence Calculation Late Comming, Early Leaving, Overtime,
                    $clockInTime = date("H:i", strtotime($checkLastAttendance->clock_in));

                    $clockInDatetime = date("Y-m-d H:i:s", strtotime($checkLastAttendance->clock_in));
                    $clockOutDatetime = date("Y-m-d H:i:s");

                    $calculateAttendanceData = (object)calculateAttendanceData($employeeId, $date, $clockInDatetime, $clockOutDatetime);

                    if ($calculateAttendanceData->status == 1)
                    {
                         if (!empty($calculateAttendanceData->data))
                         {
                              $calculateAttendanceDataRow = (object)$calculateAttendanceData->data ?? [];

                              $is_flexi_hour_applied = ($calculateAttendanceDataRow->is_flexi_hour_applied == true ? 1 : 0);
                              $late = $calculateAttendanceDataRow->late_comming ?? $late;
                              $earlyLeaving = $calculateAttendanceDataRow->early_leaving ?? $earlyLeaving;
                              $overtime = $calculateAttendanceDataRow->overtime ?? $overtime;

                              $startTime_new = Carbon::parse($calculateAttendanceDataRow->startTime);
                              $endTime_new = Carbon::parse($calculateAttendanceDataRow->endTime);
                              if ($endTime_new->lt($startTime_new))
                              {
                                   $endTime_new->addDay();
                              }
                              $companyWorking = $endTime_new->diffInSeconds($startTime_new);

                              $inParseTime = Carbon::parse($calculateAttendanceDataRow->clockInTime);
                              $outParseTime = Carbon::parse($calculateAttendanceDataRow->clockOutTime);
                              if ($outParseTime->lt($inParseTime))
                              {
                                   $outParseTime->addDay();
                              }
                              $totalWorked = $inParseTime->diffInSeconds($outParseTime);
                              if ($employeeId == 817)
                              {
                                   Log::channel('clock_in_out')->info("error", ["totalWorked" => $totalWorked, "companyWorking" => $companyWorking]);
                              }
                              if ($totalWorked < $companyWorking)
                              {
                                   $clockout_early = true;
                              }
                         }
                    }
                    // Attandence Calculation Late Comming, Early Leaving, Overtime,

                    $imageName = null;
                    if ($request->hasFile('image'))
                    {
                         // $image_name = time() . rand(100, 10000) . '.' . $request->file('image')->getClientOriginalExtension();

                         $rawFile = $request->file('image');
                         $originalExtension = $rawFile->getClientOriginalExtension();
                         if (!$originalExtension || $originalExtension === 'tmp')
                         {
                              $guessedExtension = $rawFile->guessExtension(); // Based on MIME
                              $originalExtension = $guessedExtension ?: 'jpg'; // Fallback to jpg
                         }

                         $image_name = time() . rand(100, 10000) . '.' . $originalExtension;

                         $uploadImageData = uploadFile($request->file('image'), $image_name, "images/clock_in_out/$employeeId/", true, true);
                         if ($uploadImageData['status'] == 1)
                         {
                              $imageName = $image_name;
                         }
                    }

                    AttendanceEmployee::where("id", $checkLastAttendance->id)
                         ->update([
                              "clock_out_date_time" => $currentDateTime,
                              "clock_out" => $time,
                              "clock_out_latitude" => (string)$latitude,
                              "clock_out_longitude" => (string)$longitude,

                              "early_leaving" => $earlyLeaving,
                              "overtime" => $overtime,

                              "clock_out_ip_address" => ($request->has("webClockInOut") ? $request->ip() : null),
                              "clock_out_image" => $imageName,
                              "clock_out_meta_data" => $metaData,

                         ]);

                    // Last Clock In Data

                    $checkLastAttendance = AttendanceEmployee::select("id", "employee_id", "clock_in_date_time", "clock_out_date_time", "clock_in_latitude", "clock_in_longitude", "clock_out_latitude", "clock_out_longitude", "clock_in", "clock_out",  "image", "image as clocked_in_image", "clock_out_image", "clock_out_image as clocked_out_image", "date")
                         ->where('employee_id', '=', $employeeDetails->id)
                         // ->orderBy("date", "DESC")
                         ->orderByRaw("date DESC, id DESC")
                         ->first();

                    if ($request->is_from_kill_mode == 1)
                    {
                         AttendanceEmployee::where('employee_id', '=', $employeeDetails->id)->update(['is_clock_by_killapp' => 1]);
                    }
                    // Last Clock In Data
                    if ($clockout_early)
                    {
                         $title_notification = __("Attendance Alert");
                         $body_notification = __("You have clocked out successfully. You have not completed your required working hours.");
                    }
                    else
                    {
                         $title_notification = __("Attendance Notification");
                         $body_notification = __("You have clocked out successfully.");
                    }

                    // notification Code
                    $notification               = new NotificationModel();
                    $notification->user_id      = Auth::user()->id;
                    $notification->employee_id  = $employeeId;
                    $notification->title        = $title_notification;
                    $notification->details      = $body_notification;
                    $notification->status       = 0;
                    $notification->date         = Carbon::now();
                    $notification->created_by   = Auth::user()->created_by;
                    $notification->redirect     = 'dashboard_user_clock_out';
                    $notification->save();
                    //end notification

                    // Send Push Notification to App
                    $usersData = User::where('id', Auth::id())->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();
                    if (!$usersData->isEmpty())
                    {
                         $device_type = implode(',', $usersData->pluck('device_type')->toArray());
                         if ($request->is_from_kill_mode == 1)
                         {
                              if ($device_type == 2)
                              {
                                   $title = $title_notification;
                                   $body = $body_notification;

                                   $click_action = "dashboard_user_clock_out";
                                   $extraData = ['id' => (string)$notification->id];

                                   sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                              }
                         }
                         else
                         {
                              $title = $title_notification;
                              $body = $body_notification;

                              $click_action = "dashboard_user_clock_out";
                              $extraData = ['id' => (string)$notification->id];

                              sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                         }
                    }
                    // Send Push Notification to App
                    $response = [
                         'status' => 1,
                         'message' => __("You have clocked out successfully."),
                         "data" => $checkLastAttendance,
                    ];
               }
               else // return with validation message of already clocked in
               {
                    $checkLastAttendance = AttendanceEmployee::select("id", "employee_id", "clock_in_date_time", "clock_out_date_time", "clock_in_latitude", "clock_in_longitude", "clock_out_latitude", "clock_out_longitude", "clock_in", "clock_out",  "image", "image as clocked_in_image", "clock_out_image", "clock_out_image as clocked_out_image", "date")
                         ->where('employee_id', '=', $employeeDetails->id)
                         // ->orderBy("date", "DESC")
                         ->orderByRaw("date DESC, id DESC")
                         ->first();

                    $response = [
                         'status' => 0,
                         'message' => __("You have already clocked out, first clock in to clock out again."),
                         "data" => $checkLastAttendance,
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }

          Log::channel('clock_in_out')->info("_____________________ CLOCK OUT API END _____________________");

          return $response;
     }

     public function syncClockInClockOut(Request $request)
     {
          Log::channel('clock_in_out')->info("_____________________ SYNC CLOCK IN CLOCK OUT START _____________________");

          try
          {
               Log::channel('clock_in_out')->info("syncClockInClockOut API START - UserID: " . (Auth::user()->id ?? 'N/A'), $request->all());

               if ($request->hasFile('image'))
               {
                    $image = $request->file('image');
                    Log::channel('clock_in_out')->info("Image Upload Info", [
                         'original_name' => $image->getClientOriginalName(),
                         'extension' => $image->getClientOriginalExtension(),
                         'mime_type' => $image->getMimeType(),
                         'size' => $image->getSize(),
                         'real_path' => $image->getRealPath(),
                         'error' => $image->getError(),
                    ]);
               }
          }
          catch (\Throwable $th)
          {
               Log::channel('clock_in_out')->error("Image Log Error: " . $th->getMessage());
          }

          $body_notification = __("You have clocked out successfully.");

          try
          {
               Log::channel('clock_in_out')->info("Processing clock in/out logic");

               $latitude = $request->latitude ?? "";
               $longitude = $request->longitude ?? "";

               $metaData = json_encode($request->meta_data ?? "");

               $employeeId = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;

               if ($request->has("employee_id"))
               {
                    if ($request->employee_id > 0)
                    {
                         $employeeId = $request->employee_id;
                    }
               }

               Log::channel('clock_in_out')->info("Employee ID resolved: $employeeId");

               $employeeDetails = Employee::where("id", $employeeId)->first();

               $date = date("Y-m-d");
               $time = date("H:i:s");

               $currentDateTime = date("Y-m-d H:i:s");

               $checkLastAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)
                    ->orderByRaw("date DESC, id DESC")
                    ->first();

               Log::channel('clock_in_out')->info("Last attendance fetched", [
                    'attendance_id' => $checkLastAttendance->id ?? null,
                    'clock_in' => $checkLastAttendance->clock_in ?? null,
                    'clock_out' => $checkLastAttendance->clock_out ?? null,
                    'date' => $checkLastAttendance->date ?? null,
               ]);

               DB::beginTransaction();
               Log::channel('clock_in_out')->info("DB Transaction started");

               $isCreateClockIn = false;
               $clockout_early = false;

               if ($checkLastAttendance == null)
               {
                    Log::channel('clock_in_out')->info("No previous attendance found, will create clock in");
                    $isCreateClockIn = true;
               }
               else
               {
                    if ($checkLastAttendance->clock_out == "00:00:00")
                    {
                         Log::channel('clock_in_out')->info("Previous clock in found, will update clock out", [
                              'attendance_id' => $checkLastAttendance->id
                         ]);
                         $is_flexi_hour_applied = 0;
                         $late = "00:00:00";
                         $earlyLeaving = "00:00:00";
                         $overtime = '00:00:00';

                         $clockInTime = date("H:i", strtotime($checkLastAttendance->clock_in));
                         $clockInDatetime = date("Y-m-d H:i:s", strtotime($checkLastAttendance->clock_in));
                         $clockOutDatetime = date("Y-m-d H:i:s");

                         $calculateAttendanceData = (object)calculateAttendanceData($employeeId, $date, $clockInDatetime, $clockOutDatetime);
                         Log::channel('clock_in_out')->info("Attendance calculation for clock out", [
                              'result' => $calculateAttendanceData
                         ]);
                         if ($calculateAttendanceData->status == 1)
                         {
                              if (!empty($calculateAttendanceData->data))
                              {
                                   $calculateAttendanceDataRow = (object)$calculateAttendanceData->data ?? [];

                                   $is_flexi_hour_applied = ($calculateAttendanceDataRow->is_flexi_hour_applied == true ? 1 : 0);
                                   $late = $calculateAttendanceDataRow->late_comming ?? $late;
                                   $earlyLeaving = $calculateAttendanceDataRow->early_leaving ?? $earlyLeaving;
                                   $overtime = $calculateAttendanceDataRow->overtime ?? $overtime;

                                   $startTime_new = Carbon::parse($calculateAttendanceDataRow->startTime);
                                   $endTime_new = Carbon::parse($calculateAttendanceDataRow->endTime);
                                   if ($endTime_new->lt($startTime_new))
                                   {
                                        $endTime_new->addDay();
                                   }

                                   $companyWorking = $endTime_new->diffInSeconds($startTime_new);
                                   $inParseTime = Carbon::parse($calculateAttendanceDataRow->clockInTime);
                                   $outParseTime = Carbon::parse($calculateAttendanceDataRow->clockOutTime);
                                   if ($outParseTime->lt($inParseTime))
                                   {
                                        $outParseTime->addDay();
                                   }
                                   $totalWorked = $inParseTime->diffInSeconds($outParseTime);

                                   if ($totalWorked < $companyWorking)
                                   {
                                        $clockout_early = true;
                                        Log::channel('clock_in_out')->info("Clock out early detected", [
                                             'totalWorked' => $totalWorked,
                                             'companyWorking' => $companyWorking
                                        ]);
                                   }
                              }
                         }

                         $imageName = null;
                         if ($request->hasFile('image'))
                         {
                              // $image_name = time() . rand(100, 10000) . '.' . $request->file('image')->getClientOriginalExtension();

                              $rawFile = $request->file('image');
                              $originalExtension = $rawFile->getClientOriginalExtension();
                              if (!$originalExtension || $originalExtension === 'tmp')
                              {
                                   $guessedExtension = $rawFile->guessExtension(); // Based on MIME
                                   $originalExtension = $guessedExtension ?: 'jpg'; // Fallback to jpg
                              }

                              $image_name = time() . rand(100, 10000) . '.' . $originalExtension;

                              $uploadImageData = uploadFile($request->file('image'), $image_name, "images/clock_in_out/$employeeId/", false, false);
                              if ($uploadImageData['status'] == 1)
                              {
                                   $imageName = $image_name;
                                   Log::channel('clock_in_out')->info("Clock out image uploaded", ['image' => $imageName]);
                              }
                              else
                              {
                                   Log::channel('clock_in_out')->warning("Clock out image upload failed", ['upload_result' => $uploadImageData]);
                              }
                         }

                         AttendanceEmployee::where("id", $checkLastAttendance->id)
                              ->update([
                                   "clock_out_date_time" => $currentDateTime,
                                   "clock_out" => $time,
                                   "clock_out_latitude" => (string)$latitude,
                                   "clock_out_longitude" => (string)$longitude,
                                   "early_leaving" => $earlyLeaving,
                                   "overtime" => $overtime,
                                   "clock_out_ip_address" => ($request->has("webClockInOut") ? $request->ip() : null),
                                   "clock_out_image" => $imageName,
                                   "clock_out_meta_data" => $metaData,
                              ]);
                         Log::channel('clock_in_out')->info("Attendance updated for clock out", [
                              'attendance_id' => $checkLastAttendance->id
                         ]);
                         $isCreateClockIn = false;
                    }
                    else
                    {
                         Log::channel('clock_in_out')->info("Previous attendance already clocked out, will create new clock in");
                         $isCreateClockIn = true;
                    }
               }

               if ($isCreateClockIn)
               {
                    Log::channel('clock_in_out')->info("Creating new clock in entry");
                    $late = "00:00:00";
                    $is_flexi_hour_applied = 0;

                    $clockInDatetime = date("Y-m-d H:i:s");

                    $calculateAttendanceData = (object)calculateAttendanceData($employeeId, $date, $clockInDatetime, null);
                    Log::channel('clock_in_out')->info("Attendance calculation for clock in", [
                         'result' => $calculateAttendanceData
                    ]);
                    if ($calculateAttendanceData->status == 1)
                    {
                         if (!empty($calculateAttendanceData->data))
                         {
                              $calculateAttendanceDataRow = (object)$calculateAttendanceData->data ?? [];

                              $late = $calculateAttendanceDataRow->late_comming ?? $late;
                              $is_flexi_hour_applied = ($calculateAttendanceDataRow->is_flexi_hour_applied == true ? 1 : 0);
                         }
                    }

                    $imageName = null;
                    if ($request->hasFile('image'))
                    {
                         // $image_name = time() . rand(100, 10000) . '.' . $request->file('image')->getClientOriginalExtension();
                         $rawFile = $request->file('image');
                         $originalExtension = $rawFile->getClientOriginalExtension();
                         if (!$originalExtension || $originalExtension === 'tmp')
                         {
                              $guessedExtension = $rawFile->guessExtension(); // Based on MIME
                              $originalExtension = $guessedExtension ?: 'jpg'; // Fallback to jpg
                         }

                         $image_name = time() . rand(100, 10000) . '.' . $originalExtension;

                         $uploadImageData = uploadFile($request->file('image'), $image_name, "images/clock_in_out/$employeeId/", true, true);
                         if ($uploadImageData['status'] == 1)
                         {
                              $imageName = $image_name;
                              Log::channel('clock_in_out')->info("Clock in image uploaded", ['image' => $imageName]);
                         }
                         else
                         {
                              Log::channel('clock_in_out')->warning("Clock in image upload failed", ['upload_result' => $uploadImageData]);
                         }
                    }

                    $employeeAttendance                = new AttendanceEmployee();
                    $employeeAttendance->employee_id   = $employeeId;
                    $employeeAttendance->date          = $date;
                    $employeeAttendance->status        = 'Present';
                    $employeeAttendance->clock_in_date_time = $currentDateTime;
                    $employeeAttendance->clock_out_date_time = null;
                    $employeeAttendance->clock_in = $time;
                    $employeeAttendance->clock_out     = '00:00:00';
                    $employeeAttendance->clock_in_latitude    = (string)$latitude;
                    $employeeAttendance->clock_in_longitude   = (string)$longitude;
                    $employeeAttendance->late          = $late;
                    $employeeAttendance->early_leaving = '00:00:00';
                    $employeeAttendance->overtime      = '00:00:00';
                    $employeeAttendance->total_rest    = '00:00:00';
                    $employeeAttendance->is_flexi_hours_used = $is_flexi_hour_applied;
                    $employeeAttendance->image = $imageName;
                    $employeeAttendance->clock_in_meta_data = $metaData;
                    $employeeAttendance->created_by = Auth::user()->id;
                    $employeeAttendance->geofencing_status = $employeeDetails->geofencing_status;

                    if ($request->has("webClockInOut"))
                    {
                         $employeeAttendance->clock_in_ip_address = ($request->ip() ?? null);
                    }

                    $employeeAttendance->save();
                    Log::channel('clock_in_out')->info("Attendance created for clock in", [
                         'attendance_id' => $employeeAttendance->id
                    ]);
               }

               $body_notification = __("You have clocked out successfully.");
               if (!$isCreateClockIn)
               {
                    if ($request->is_from_kill_mode == 1)
                    {
                         AttendanceEmployee::where('employee_id', '=', $employeeId)->update(['is_clock_by_killapp' => 1]);
                         Log::channel('clock_in_out')->info("Marked attendance as clocked by kill app");
                    }
                    if ($clockout_early)
                    {
                         $title_notification = __("Attendance Alert");
                         $body_notification = __("You have clocked out successfully. You have not completed your required working hours.");
                    }
                    else
                    {
                         $title_notification = __("Attendance Notification");
                         $body_notification = __("You have clocked out successfully.");
                    }

                    // notification Code
                    $notification               = new NotificationModel();
                    $notification->user_id      = Auth::user()->id;
                    $notification->employee_id  = $employeeId;
                    $notification->title        = $title_notification;
                    $notification->details      = $body_notification;
                    $notification->status       = 0;
                    $notification->date         = Carbon::now();
                    $notification->created_by   = Auth::user()->created_by;
                    $notification->redirect     = 'dashboard_user_clock_out';
                    $notification->save();
                    Log::channel('clock_in_out')->info("Notification created", [
                         'notification_id' => $notification->id
                    ]);

                    // Send Push Notification to App
                    $usersData = User::where('id', Auth::id())->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $device_type = implode(',', $usersData->pluck('device_type')->toArray());
                         if ($request->is_from_kill_mode == 1)
                         {
                              if ($device_type == 2)
                              {
                                   $title = $title_notification;
                                   $body = $body_notification;

                                   $click_action = "dashboard_user_clock_out";
                                   $extraData = ['id' => (string)$notification->id];

                                   sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                                   Log::channel('clock_in_out')->info("Push notification sent (kill mode)", [
                                        'title' => $title,
                                        'body' => $body
                                   ]);
                              }
                         }
                         else
                         {
                              $title = $title_notification;
                              $body = $body_notification;

                              $click_action = "dashboard_user_clock_out";
                              $extraData = ['id' => (string)$notification->id];

                              sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                              Log::channel('clock_in_out')->info("Push notification sent", [
                                   'title' => $title,
                                   'body' => $body
                              ]);
                         }
                    }
               }
               DB::commit();
               Log::channel('clock_in_out')->info("DB Transaction committed");

               $checkLastAttendance = AttendanceEmployee::select("id", "employee_id", "clock_in_date_time", "clock_out_date_time", "clock_in_latitude", "clock_in_longitude", "clock_out_latitude", "clock_out_longitude", "clock_in", "clock_out",  "image", "image as clocked_in_image", "clock_out_image", "clock_out_image as clocked_out_image", "date")->where('employee_id', '=', $employeeDetails->id)
                    ->orderByRaw("date DESC, id DESC")
                    ->first();

               Log::channel('clock_in_out')->info("Returning response", [
                    'isClockIn' => $isCreateClockIn,
                    'attendance_id' => $checkLastAttendance->id ?? null
               ]);

               $response = [
                    'status' => 1,
                    'message' => ($isCreateClockIn == true ? __("You have successfully clocked in.") : $body_notification),
                    "isClockIn" => $isCreateClockIn,
                    "data" => $checkLastAttendance,
               ];
          }
          catch (Exception | Throwable $e)
          {
               Log::channel('clock_in_out')->error("Exception: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
               ]);
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }

          $message = "";
          if ($request->has("webClockInOut"))
          {
               $message = ($isCreateClockIn == true ? __("You have successfully clocked in.") : $body_notification);
               Log::channel('clock_in_out')->info("Web clock in/out redirect", ['message' => $message]);
               return redirect()->route('home')->with('success', $message);
          }

          Log::channel('clock_in_out')->info("syncClockInClockOut API END", [
               'response_status' => $response['status'] ?? null,
               'isClockIn' => $response['isClockIn'] ?? null
          ]);

          Log::channel('clock_in_out')->info("_____________________ SYNC CLOCK IN CLOCK OUT END _____________________");

          return $response;
     }

     public function syncClockInClockOutStatus(Request $request)
     {
          $response = [];
          $isClockIn = false;
          $isCLockOut = false;

          try
          {
               $employeeId = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;

               $employeeDetails = Employee::where("id", $employeeId)->first();
               if ($employeeDetails != null)
               {
                    $checkLastAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)
                         // ->where('geofencing_status', '=', 1)
                         // ->orderBy("date", "DESC")
                         ->orderByRaw("date DESC, id DESC")
                         ->first();

                    if ($checkLastAttendance != null)
                    {
                         // check if clocked in or not
                         if ($checkLastAttendance->clock_in != '00:00:00')
                         {
                              $isClockIn = true;
                         }
                         else
                         {
                              $isClockIn = false;
                         }

                         // check if clocked out or not
                         if ($checkLastAttendance->clock_out != '00:00:00')
                         {
                              $isCLockOut = true;
                         }
                         else
                         {
                              $isCLockOut = false;
                         }
                    }
               }

               $response = [
                    'status' => 1,
                    "is_clock_in" => $isClockIn,
                    "is_clock_out" => $isCLockOut,
               ];
          }
          catch (Exception | Throwable $ex)
          {
               $response = [
                    'status' => 0,
                    "is_clock_in" => $isClockIn,
                    "is_clock_out" => $isCLockOut,
               ];
          }

          return $response;
     }

     public function get_profile(request $request)
     {
          try
          {
               $employee = Employee::select("*", "employee_profile as employee_profile_image")
                    ->with(
                         'employeeDocuments',
                         'experience',
                         'education',
                         'dependent'
                    )
                    ->with('branch', 'department', 'designation')->with("playslab")->where('user_id', Auth::id())->first();

               $employee->employee_code = Auth::user()->employeeIdFormat($employee->employee_id);
               $employee->office_staff_name = ($employee->office_staff == 1 ? "Office Staff" : "Field Staff");
               $employee->salary = Auth::user()->priceFormat($employee->salary);

               $employee->login_status = Auth::user()->login_status ?? 0;

               $employee->maritalStatusList = [
                    "Single",
                    "Married",
                    "Widowed",
                    "Separated",
                    "Divorced"
               ];

               $employee->relationTypesList = [
                    "Father",
                    "Mother",
                    "Brother",
                    "Sister",
                    "Husband",
                    "Wife",
                    "Child",
               ];

               $response = [
                    'status' => 1,
                    'message' => __("Profile Data"),
                    'data' => $employee
               ];

               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_experience(request $request)
     {
          try
          {
               $emp =  User::find(Auth::id());
               $employee     = Employee::where('user_id', $emp->id)->first();
               $EXPTableData = EXPTable::where('employee_id', $employee->id)->get();

               $response = [
                    'status' => 1,
                    'message' => __("Experience Data"),
                    'data' => $EXPTableData,
               ];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_education(request $request)
     {
          try
          {
               $emp        =  User::find(Auth::id());
               $employee   = Employee::where('user_id', $emp->id)->first();
               $data       = EdcutionTable::where('employee_id', $employee->id)->get();
               $response = [
                    'status'  => 1,
                    'message' => _("Education Data"),
                    'data'    => $data,
               ];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_dependent(request $request)
     {
          try
          {
               $emp        =  User::find(Auth::id());
               $employee   = Employee::where('user_id', $emp->id)->first();
               $data       = DependentData::where('employee_id', $employee->id)->get();
               $response = [
                    'status'  => 1,
                    'message' => __("Dependent Data"),
                    'data'    => $data,
               ];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_documents()
     {
          try
          {
               $data = Document::where('created_by', Auth::user()->creatorId())
                    ->with(["EmployeeDocument" => function ($query)
                    {
                         $query->select("*", "document_value as document_link")
                              ->where("employee_id", Auth::user()->employee->id);
                    }])
                    // ->whereHas(["EmployeeDocument" => function ($query)
                    // {
                    //      $query->where("employee_id", Auth::user()->employee->id);
                    // }])
                    ->get();
               $response = [
                    'status'  => 1,
                    'message' => __("Documents"),
                    'data'    => $data,
               ];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function testNotification()
     {
          try
          {
               $login_user = User::find(337);
               // notification Code
               $notification               = new NotificationModel();
               $notification->user_id      = $login_user->id;
               $notification->employee_id  = $login_user->id;
               $notification->title        = "You clockout successfully";
               $notification->details      = "You clockout successfully";
               $notification->status       = 0;
               $notification->date         = Carbon::now();
               $notification->created_by   = $login_user->created_by;
               $notification->redirect     = 'dashboard_user_clock_out';
               $notification->save();
               //end notification

               // Send Push Notification to App
               $usersData = User::where('id', 337)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

               if (!$usersData->isEmpty())
               {
                    $title = __("You successfully clockout");
                    $body = __("You successfully clockout");

                    $click_action = 'dashboard_user_clock_out';
                    $extraData = ['id' => (string)$notification->id];

                    sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
               }
               // Send Push Notification to App
          }
          catch (Exception | Throwable $e)
          {
               dd($e);
          }
     }
     public function set_logout(request $request)
     {
          try
          {
               $login_user = User::find(Auth::id());
               $latitude = $request->latitude ?? "";
               $longitude = $request->longitude ?? "";
               if (!empty($login_user))
               {
                    if ($request->latitude != '' && $request->longitude != '')
                    {
                         $employeeId = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;
                         $date = date("Y-m-d");
                         $checkLastAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->orderBy("id", "DESC")->first();
                         if ($checkLastAttendance != null && ($checkLastAttendance->clock_out ?? "") == "00:00:00") // if null then create new clock in entry
                         {
                              $late = "00:00:00";
                              $earlyLeaving = "00:00:00";
                              $overtime = '00:00:00';

                              // Attandence Calculation Late Comming, Early Leaving, Overtime,
                              $clockInTime = date("H:i", strtotime($checkLastAttendance->clock_in));

                              $clockInDatetime = date("Y-m-d H:i:s", strtotime($checkLastAttendance->clock_in));
                              $clockOutDatetime = date("Y-m-d H:i:s");

                              $calculateAttendanceData = (object)calculateAttendanceData($employeeId, $date, $clockInDatetime, $clockOutDatetime);

                              if ($calculateAttendanceData->status == 1)
                              {
                                   if (!empty($calculateAttendanceData->data))
                                   {
                                        $calculateAttendanceDataRow = (object)$calculateAttendanceData->data ?? [];

                                        $late = $calculateAttendanceDataRow->late_comming ?? $late;
                                        $earlyLeaving = $calculateAttendanceDataRow->early_leaving ?? $earlyLeaving;
                                        $overtime = $calculateAttendanceDataRow->overtime ?? $overtime;
                                   }
                              }
                              $currentDateTime = date("Y-m-d H:i:s");
                              $time = date("H:i:s");
                              AttendanceEmployee::where("id", $checkLastAttendance->id)
                                   ->update([
                                        "clock_out_date_time" => $currentDateTime,
                                        "clock_out" => $time,
                                        "clock_out_latitude" => (string)$latitude,
                                        "clock_out_longitude" => (string)$longitude,
                                        "early_leaving" => $earlyLeaving,
                                        "overtime" => $overtime,
                                        "clock_out_ip_address" => null,
                                        "clock_out_image" => '',
                                        "clock_out_meta_data" => '',
                                        "is_by_logout" => 1,
                                   ]);
                         }
                    }
                    $request->user()->token()->revoke();
                    $response = [
                         'status' => 1,
                         'message' => __("Logged out successfully."),
                    ];
                    return $response;
               }
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function updatePassword(request $request)
     {
          try
          {
               if (Auth::check())
               {
                    $objUser          = Auth::user();
                    $request_data     = $request->all();
                    $current_password = $objUser->password;
                    if (Hash::check($request_data['current_password'], $current_password))
                    {
                         $user_id            = Auth::user()->id;
                         $obj_user           = User::find($user_id);
                         $obj_user->password = Hash::make($request_data['new_password']);
                         $obj_user->save();

                         DB::commit();
                         $response = ['status' => 1, 'message' => __('Password successfully updated.')];
                    }
                    else
                    {
                         $response = ['status' => 0, 'message' => __('Please enter correct current password.')];
                    }
               }
               else
               {
                    $response = ['status' => 0, 'message' => __('Something is wrong.')];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function forgotPassword(Request $request)
     {
          $request->validate([
               'email' => 'required|email',
          ]);

          $response = [];
          try
          {
               $userData = User::where("email", $request->email)->first();
               if ($userData != null)
               {
                    $status = Password::sendResetLink(
                         $request->only('email')
                    );

                    if ($status == Password::RESET_LINK_SENT)
                    {
                         $response = [
                              "status" => 1,
                              "message" => __("We have e-mailed your password reset link!"),
                              "data" => [],
                         ];
                    }
                    else
                    {
                         $response = [
                              "status" => 0,
                              "message" => __("Something went wrong, can not send email currently."),
                              "data" => [],
                         ];
                    }
               }
               else
               {
                    $response = [
                         "status" => 0,
                         "message" => __("There is no user exists with this email, please check your email and try again."),
                         "data" => [],
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function dashboard(request $request)
     {
          $response = [];

          try
          {
               $emp = Auth::user();
               $employee = Employee::select("*", "employee_profile as employee_profile_image")
                    ->with('branch')
                    ->where('user_id', ($emp->id ?? 0))
                    ->first();

               if ($employee != null)
               {
                    $employeeId = $employee->id;

                    $settings = Utility::settings();

                    $site_currency_symbol_position = $settings['site_currency_symbol_position'] ?? "";
                    $site_currency_symbol = $settings['site_currency_symbol'] ?? "";

                    $payslipDetail = getEmployeeSalaryStructure($employee->id, date("Y") . '-' . date("m"));


                    $isTodayWorkAnniversary = false;
                    if (date('Y', strtotime($employee->getRawOriginal("company_doj"))) != date("Y") && date('m-d', strtotime($employee->getRawOriginal("company_doj"))) == date("m-d"))
                    {
                         $isTodayWorkAnniversary = true;
                    }

                    $isTodayMarriageAnniversary = false;
                    if ($employee->marital_status == "Married" || $employee->marital_status == "Widowed")
                    {
                         if ($employee->marriage_anniversary_date == date("Y-m-d"))
                         {
                              $isTodayMarriageAnniversary = true;
                         }
                    }

                    $officeTime['startTime'] = timeFormat($settings['company_start_time'] ?? '');
                    $officeTime['endTime']   = timeFormat($settings['company_end_time'] ?? '');
                    $officeTime['flexiHours'] = $settings['company_flexi_hours'] ?? '';
                    $officeTime['flexiDays'] = $settings['company_flexi_days'] ?? '';

                    $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employeeId)->first();
                    $employeeShift = null;
                    if ($workingShiftEmployees != null)
                    {
                         $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
                         if ($employeeShift != null)
                         {
                              $officeTime['startTime'] = timeFormat($employeeShift->start_time);
                              $officeTime['endTime'] = timeFormat($employeeShift->end_time);

                              $officeTime['flexiHours'] = $employeeShift->flexi_hours;
                              $officeTime['flexiDays'] = $employeeShift->flexi_days;
                         }
                    }

                    $workingData = $officeTime;

                    $documents = Document::where('created_by', Auth::user()->creatorId())
                         ->with(["EmployeeDocument" => function ($query) use ($employeeId)
                         {
                              $query->select("id", "document_id", "document_value", "document_value as document_link")->where("employee_id", $employeeId);
                         }])
                         ->get();

                    $workAnniversaryList = Employee::select("id", "name", "employee_profile", "employee_profile as employee_profile_image")
                         ->whereDay("company_doj", date("d"))
                         ->whereYear("company_doj", '!=', date("Y"))
                         ->whereMonth("company_doj", date("m"))
                         ->where("created_by", Auth::user()->creatorId())
                         ->where("id", "!=", $employeeId)
                         ->where("employee_exit", 0)
                         ->orderBy("name", "ASC")
                         ->get();

                    $marriageAnniversaryList = Employee::select("id", "name", "employee_profile", "employee_profile as employee_profile_image")
                         ->whereDay("marriage_anniversary_date", date("d"))
                         ->whereMonth("marriage_anniversary_date", date("m"))
                         ->where("created_by", Auth::user()->creatorId())
                         ->where("id", "!=", $employeeId)
                         ->where("employee_exit", 0)
                         ->orderBy("name", "ASC")
                         ->get();

                    $is_custom_weekoffs = $employee->is_custom_weekoffs;
                    $employee_weekoffs_data = $employee->weekoffs_data;

                    $company_weekoffs_data = [];
                    if ($settings['company_office_week_off_monday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Monday";
                    }
                    if ($settings['company_office_week_off_tuesday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Tuesday";
                    }
                    if ($settings['company_office_week_off_wednesday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Wednesday";
                    }
                    if ($settings['company_office_week_off_thusday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Thursday";
                    }
                    if ($settings['company_office_week_off_friday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Friday";
                    }
                    if ($settings['company_office_week_off_saturday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Saturday";
                    }
                    if ($settings['company_office_week_off_sunday'] == 'on')
                    {
                         $company_weekoffs_data[] = "Sunday";
                    }

                    $holidays = Holiday::where('status', 1)
                         ->where('created_by', Auth::user()->creatorId())
                         ->where('branch_id', $employee->branch_id)
                         ->get();

                    $checkLastAttendance = AttendanceEmployee::select("id", "employee_id", "clock_in_date_time", "clock_out_date_time", "clock_in_latitude", "clock_in_longitude", "clock_out_latitude", "clock_out_longitude", "clock_in", "clock_out", "image", "image as clocked_in_image", "clock_out_image", "clock_out_image as clocked_out_image", "date")->where('employee_id', '=', $employee->id)
                         ->orderByRaw("date DESC, id DESC")
                         ->first();

                    $lastClockInImageUrl = "";
                    if ($checkLastAttendance != null && !empty($checkLastAttendance->image))
                    {
                         $lastClockInImageUrl = getFilePathPrefix() . 'images/clock_in_out/' . $checkLastAttendance->employee_id . '/' . $checkLastAttendance->image;
                    }

                    $response = [
                         'status' => 1,
                         'message' => 'Dashboard Data',
                         'data' => [
                              "site_currency_symbol_position" => $site_currency_symbol_position,
                              "site_currency_symbol" => $site_currency_symbol,
                              "salaryStructure" => $payslipDetail,
                              "geofencing_status" => $employee->geofencing_status,
                              "remote_geofencing_status" => $employee->remote_geofencing_status,
                              "latitude" => $employee->latitude,
                              "longitude" => $employee->longitude,
                              "multi_clock_in_out_status" => $employee->geofencing_status,
                              "geofencing_radius" => ($employee->branch->geofencing_radius ?? 0),
                              "lastClockInImageUrl" => $lastClockInImageUrl,

                              "company_doj" => $employee->getRawOriginal("company_doj"),
                              "isTodayWorkAnniversary" => $isTodayWorkAnniversary,
                              "isTodayMarriageAnniversary" => $isTodayMarriageAnniversary,
                              "employee_id" => $employee->employee_id,
                              "workingData" => $workingData,

                              "employee_documents" => $documents,
                              "employee_profile_image" => $employee->employee_profile_image,
                              "maritalStatusList" => [
                                   "Single",
                                   "Married",
                                   "Widowed",
                                   "Separated",
                                   "Divorced"
                              ],
                              "relationTypesList" => [
                                   "Father",
                                   "Mother",
                                   "Brother",
                                   "Sister",
                                   "Husband",
                                   "Wife",
                                   "Child",
                              ],

                              "workAnniversaryList" => $workAnniversaryList,
                              "marriageAnniversaryList" => $marriageAnniversaryList,

                              "is_custom_weekoffs" => $is_custom_weekoffs,
                              "employee_weekoffs_data" => json_decode($employee_weekoffs_data),
                              "company_weekoffs_data" => $company_weekoffs_data,
                              "holidays" => $holidays,
                              "default_image" => asset(Storage::url('uploads/avatar')) . '/avatar.png',
                              "login_status" => $emp->login_status ?? 0,
                              "last_clock_in_out_data" => $checkLastAttendance,
                         ]
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Employee not found.',
                         'data' => [],
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function getNotificationList(Request $request)
     {
          try
          {
               $data = NotificationModel::where('user_id', Auth::id())
                    // ->where('status', 0)
                    ->where('created_by', \Auth::user()->creatorId())
                    ->orderBy('id', 'DESC')
                    ->get();

               $response = [
                    'status' => 1,
                    'message' => "Notification List",
                    'data' => $data,
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function clearNotificationList(Request $request)
     {
          try
          {
               DB::beginTransaction();

               NotificationModel::where('user_id', Auth::id())
                    ->where('created_by', \Auth::user()->creatorId())
                    ->delete();

               DB::commit();

               $response = [
                    'status' => 1,
                    'message' => "All Notifications are cleared.",
                    'data' => [],
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function updateEmployeeProfile(Request $request)
     {

          $response = [];
          try
          {
               $emp = User::find(Auth::id());
               $employee = Employee::where('user_id', ($emp->id ?? 0))->first();


               // 0 personal details
               // 1 experience
               // 2 education
               // 3 dependent
               // 4 documents
               $profile_data_type = $request->profile_data_type ?? 0;


               if ($employee != null)
               {
                    // $validator = \Validator::make(
                    //      $request->all(),
                    //      [
                    //           'name' => 'required',
                    //           'phone' => 'required|numeric|digits:10',
                    //           'email' => 'required|unique:users,email,' . $emp->id,
                    //           'dob'   => 'required',
                    //           'marital_status'   => 'required',
                    //           'address' => 'required',
                    //           'employee_experience' => 'required',
                    //           'gender' => 'required',

                    //           // "account_holder_name" => "required",
                    //           // "account_number" => "required",
                    //           // "bank_name" => "required",
                    //           // "bank_identifier_code" => "required",
                    //           // "branch_location" => "required",
                    //           // "tax_payer_id" => "required",
                    //      ],
                    //      [
                    //           'name' => __("Name is required."),
                    //           'phone' => __("Phone is required."),
                    //           'email.required' => __("Email is required."),
                    //           'email.unique' => __("Email is already exists."),
                    //           'dob' => __("Date of birth is required."),
                    //           'marital_status' => __("Marital_status is required."),
                    //           'address' => __("Address is required."),
                    //           'employee_experience' => __("Employee Experience is required."),
                    //           'gender' => __("Gender Experience is required."),

                    //           // "account_holder_name" => __("Account Holder Name is required."),
                    //           // "account_number" => __("Account Number is required."),
                    //           // "bank_name" => __("Bank Name is required."),
                    //           // "bank_identifier_code" => __("Bank Identifier Code is required."),
                    //           // "branch_location" => __("Branch location is required."),
                    //           // "tax_payer_id" => __("Tax payer id is required."),
                    //      ]
                    // );

                    // if ($validator->fails())
                    // {
                    //      $response = [
                    //           'status' => 0,
                    //           'message' => "Employee Not Found",
                    //           'validationErrors' => $validator->messages()->all(),
                    //      ];
                    // }

                    DB::beginTransaction();

                    // 0 personal details
                    // 1 experience
                    // 2 education
                    // 3 dependent
                    // 4 documents

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

                    if ($profile_data_type == 0) // 0  = save personal details
                    {
                         $validator = \Validator::make(
                              $request->all(),
                              [
                                   'name' => 'required',
                                   'phone' => 'required|numeric|digits:10',
                                   'email' => 'required|unique:users,email,' . $emp->id,
                                   'dob'   => 'required',
                                   'marital_status'   => 'required',
                                   'address' => 'required',
                                   'employee_experience' => 'required',
                                   'gender' => 'required',
                              ],
                              [
                                   'name' => __("Name is required."),
                                   'phone' => __("Phone is required."),
                                   'phone.numeric' => __("Phone number should be numeric."),
                                   'phone.digits' => __("Phone number should be 10 digit."),
                                   'email.required' => __("Email is required."),
                                   'email.unique' => __("Email is already exists."),
                                   'dob' => __("Date of birth is required."),
                                   'marital_status' => __("Marital_status is required."),
                                   'address' => __("Address is required."),
                                   'employee_experience' => __("Employee Experience is required."),
                                   'gender' => __("Gender Experience is required."),

                                   // "account_holder_name" => __("Account Holder Name is required."),
                                   // "account_number" => __("Account Number is required."),
                                   // "bank_name" => __("Bank Name is required."),
                                   // "bank_identifier_code" => __("Bank Identifier Code is required."),
                                   // "branch_location" => __("Branch location is required."),
                                   // "tax_payer_id" => __("Tax payer id is required."),
                              ]
                         );

                         if ($validator->fails())
                         {
                              $response = [
                                   'status' => 0,
                                   'message' => $validator->messages()->first(),
                                   // 'validationErrors' => $validator->messages()->all(),
                              ];

                              return ApiResponse($response);
                         }

                         $employee->name = $request->name ?? "";
                         $employee->phone = $request->phone ?? "";
                         $employee->email = $request->email ?? "";
                         $employee->dob = $request->dob ?? "";
                         $employee->marital_status = $request->marital_status ?? "";
                         $employee->address = $request->address ?? "";
                         $employee->employee_experience = $request->employee_experience ?? "";
                         $employee->gender = $request->gender ?? "";
                         $employee->personal_email = $request->personal_email ?? "";

                         // Bank Account Details
                         // $employee->account_holder_name = $request->account_holder_name ?? "";
                         // $employee->account_number = $request->account_number ?? "";
                         // $employee->bank_name = $request->bank_name ?? "";
                         // $employee->bank_identifier_code = $request->bank_identifier_code ?? "";
                         // $employee->branch_location = $request->branch_location ?? "";
                         // $employee->tax_payer_id = $request->tax_payer_id ?? $employee->tax_payer_id;
                         // Bank Account Details

                         // Employee Profile Picture

                         if ($request->hasFile("employee_profile"))
                         {
                              $extension = $request->file('employee_profile')->getClientOriginalExtension();
                              $employee_profile_image_name = $employee->id . "_" . rand(1, 100000000) . time() . '.' . $extension;
                              $dir = 'uploads/employee_profile/';
                              $path = Utility::upload_file($request, 'employee_profile', $employee_profile_image_name, $dir, []);



                              if ($path['flag'] == 1)
                              {
                                   if ($employee->employee_profile != null || !empty(trim($employee->employee_profile)))
                                   {
                                        $image_path = storage_path() . "/" . $dir . $employee->employee_profile;
                                        Utility::delete_directory($image_path);
                                   }
                              }
                              else
                              {
                                   $employee_profile_image_name = "";
                                   return response()->json(['status' => 0, 'message' => __($path['msg'])]);
                              }

                              $employee->employee_profile = $employee_profile_image_name;
                              // User::where("id", $employee->user_id)->update(["avatar" => $employee_profile_image_name]);
                         }
                         // Employee Profile Picture

                         User::where("id", $employee->user_id)->update([
                              'name' => $employee->name,
                              'email' => $employee->email,
                              "avatar" => $employee->employee_profile
                         ]);
                    }
                    else if ($profile_data_type == 1) // 1  = save experience only
                    {
                         // Experience Data
                         EXPTable::where("employee_id", $employee->id)->delete();
                         if (!empty($request->previous_company_name[0]))
                         {
                              foreach ($request->previous_company_name as $key => $institute2)
                              {
                                   try
                                   {
                                        $eduData = new EXPTable();
                                        $eduData->employee_id                         = $employee->id;
                                        $eduData->previous_company_name               = $request->previous_company_name[$key] ?? "";
                                        $eduData->previous_job_title                  = $request->previous_job_title[$key] ?? "";
                                        $eduData->previous_from_date                  = $request->previous_from_date[$key] ?? "";
                                        $eduData->previous_to_date                    = $request->previous_to_date[$key] ?? "";
                                        $eduData->previous_company_designation_id     = $request->previous_company_designation[$key] ?? "";
                                        $eduData->experiance_Job_description          = $request->experiance_job_description[$key] ?? "";
                                        $eduData->save();
                                   }
                                   catch (Exception | Throwable $ex)
                                   {
                                   }
                              }
                         }
                         // Experience Data
                    }
                    else if ($profile_data_type == 2) // 2  = save education only
                    {
                         // Education Data
                         EdcutionTable::where("employee_id", $employee->id)->delete();
                         if (!empty($request->institute_name[0]))
                         {
                              foreach ($request->institute_name as $key => $institute2)
                              {
                                   try
                                   {
                                        $eduData = new EdcutionTable();

                                        $eduData->employee_id         = $employee->id;
                                        $eduData->institute_name      = $request->institute_name[$key];
                                        $eduData->education           = $request->education[$key];
                                        $eduData->specialization      = $request->specialization[$key];
                                        $eduData->date_of_completion  =  $request->date_of_completion[$key];
                                        $eduData->notes               = $request->notes[$key];
                                        $eduData->save();
                                   }
                                   catch (Exception | Throwable $ex)
                                   {
                                   }
                              }
                         }
                         // Education Data
                    }
                    else if ($profile_data_type == 3) // 3  = save dependent only
                    {
                         // Dependard Data
                         DependentData::where("employee_id", $employee->id)->delete();
                         if (!empty($request->dependent_name[0]))
                         {
                              foreach ($request->dependent_name as $key => $institute2)
                              {
                                   try
                                   {
                                        if (!empty($request->dependent_name[$key]))
                                        {
                                             $eduData = new DependentData();
                                             $eduData->employee_id         = $employee->id;
                                             $eduData->dependent_name      = $request->dependent_name[$key];
                                             $eduData->dependent_relation  = $request->dependent_relation[$key];
                                             $eduData->dependent_dob       = $request->dependent_dob[$key];
                                             $eduData->emergency_phone_no  = $request->emergency_phone_no[$key];
                                             $eduData->save();
                                        }
                                   }
                                   catch (Exception | Throwable $ex)
                                   {
                                   }
                              }
                         }
                         // Dependard Data
                    }
                    else if ($profile_data_type == 4) // 4  = save documents only
                    {
                         // Upload Employee Documents
                         if ($request->hasFile('document'))
                         {

                              foreach ($request->document as $key => $document)
                              {
                                   try
                                   {
                                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                                        $fileNameToStore = $employee->id . "_" . rand(1, 100000000) . time() . '.' . $extension;
                                        $dir             = 'uploads/document/';
                                        $image_path      = $dir . $fileNameToStore;

                                        $path = Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);
                                        if ($path['flag'] == 1)
                                        {
                                             $url = $path['url'];
                                        }
                                        else
                                        {
                                             return response()->json(['status' => 0, 'message' => __($path['msg'])]);
                                        }

                                        $employee_document = EmployeeDocument::where('employee_id', $employee->id)->where('document_id', $key)->first();
                                        if ($employee_document != null)
                                        {
                                             if (!empty($employee_document->document_value))
                                             {
                                                  // \File::delete(storage_path('uploads/document/' . $employee_document->document_value));
                                                  $image_path = storage_path() . "/" . $dir . $employee_document->document_value;
                                                  Utility::delete_directory($image_path);
                                             }
                                             $employee_document->document_value = $fileNameToStore;
                                             $employee_document->save();
                                        }
                                        else
                                        {
                                             $employee_document                 = new EmployeeDocument();
                                             $employee_document->employee_id    = $employee->id;
                                             $employee_document->document_id    = $key;
                                             $employee_document->document_value = $fileNameToStore;
                                             $employee_document->save();
                                        }
                                   }
                                   catch (Exception | Throwable $ex)
                                   {
                                   }
                              }
                         }
                         // Upload Employee Documents
                    }

                    $employee->save();

                    DB::commit();

                    if ($auditEnabled) {
                         try {
                              app(EmployeeDataChangeLogService::class)->recordFromEmployee(
                                   (int) $employee->id,
                                   $auditBefore,
                                   [
                                        'source' => 'mobile_api',
                                        'company_id' => (int) Auth::user()->creatorId(),
                                        'profile_data_type' => $profile_data_type,
                                   ]
                              );
                         } catch (Throwable $auditEx) {
                              // Never break API response
                         }
                    }


                    $profileData = Employee::select("*", "employee_profile as employee_profile_image")
                         ->with(
                              // 'employeeDocuments',
                              'experience',
                              'education',
                              'dependent'
                         )
                         ->with('branch', 'department', 'designation')->with("playslab")->where('id', $employee->id)->first();

                    $employeeId = $employee->id;
                    $documents = Document::where('created_by', Auth::user()->creatorId())
                         ->with(["EmployeeDocument" => function ($query) use ($employeeId)
                         {
                              $query->select("id", "document_id", "document_value", "document_value as document_link")->where("employee_id", $employeeId);
                         }])
                         ->get();

                    $profileData->employee_documents = $documents;

                    $profileData->employee_code = Auth::user()->employeeIdFormat($employee->employee_id);
                    $profileData->office_staff_name = ($employee->office_staff == 1 ? "Office Staff" : "Field Staff");
                    $profileData->salary = Auth::user()->priceFormat($employee->salary);


                    // 0 personal details
                    // 1 experience
                    // 2 education
                    // 3 dependent
                    // 4 documents
                    $messagesArray = [
                         0 => __("Personal details updated successfully."),
                         1 => __("Experience details updated successfully."),
                         2 => __("Education details updated successfully."),
                         3 => __("Dependent details updated successfully."),
                         4 => __("Documents updated successfully."),
                    ];

                    $response = [
                         'status' => 1,
                         'message' => $messagesArray[$profile_data_type],
                         'data' => $profileData,
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => "Employee Not Found",
                         'data' => [],
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function employeeCalendarData(Request $request)
     {
          $response = [];
          try
          {
               $employee = Auth::user()->employee ?: Employee::where('user_id', Auth::id())->first();

               if ($employee == null)
               {
                    $response = [
                         'status' => 0,
                         'message' => "Employee not found.",
                         'data' => [],
                    ];
               }
               else
               {
                    $setting = Utility::settings();
                    $creatorId = Auth::user()->creatorId();

                    $weefOffArray = [];
                    if ($setting['company_office_week_off_monday'] == 'on')
                    {
                         $weefOffArray[] = "Monday";
                    }
                    if ($setting['company_office_week_off_tuesday'] == 'on')
                    {
                         $weefOffArray[] = "Tuesday";
                    }
                    if ($setting['company_office_week_off_wednesday'] == 'on')
                    {
                         $weefOffArray[] = "Wednesday";
                    }
                    if ($setting['company_office_week_off_thusday'] == 'on')
                    {
                         $weefOffArray[] = "Thursday";
                    }
                    if ($setting['company_office_week_off_friday'] == 'on')
                    {
                         $weefOffArray[] = "Friday";
                    }
                    if ($setting['company_office_week_off_saturday'] == 'on')
                    {
                         $weefOffArray[] = "Saturday";
                    }
                    if ($setting['company_office_week_off_sunday'] == 'on')
                    {
                         $weefOffArray[] = "Sunday";
                    }

                    $calenderDate = $request->calender_date ?? date("Y-m-d");
                    $month = date('m', strtotime($calenderDate));
                    $year = date('Y', strtotime($calenderDate));
                    $monthStart = $year . '-' . $month . '-01';
                    $monthEnd = date('Y-m-t', strtotime($monthStart));

                    $num_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
                    $dates = [];
                    for ($i = 1; $i <= $num_of_days; $i++)
                    {
                         $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                    }

                    $is_custom_weekoffs = $employee->is_custom_weekoffs ?? 0;
                    $employee_weekoffs_data = json_decode($employee->weekoffs_data) ?? [];

                    $calenderData = [];

                    $leaveColorCode = "#3ec9d6";
                    $presentColorCode = "#6fd943";
                    $weekOffColorCode = "#000000";
                    $holidayColorCode = "#ffa21d";
                    $absentColorCode = "#ff3a6e";

                    $monthLeaves = Leave::where('employee_id', $employee->id)
                         ->where('status', 'Approved')
                         ->where('created_by', $creatorId)
                         ->where('start_date', '<=', $monthEnd)
                         ->where('end_date', '>=', $monthStart)
                         ->orderBy('id')
                         ->get();

                    // Preserve original per-day SQL:
                    // (status=1 AND created_by=X AND start_date=D) OR (end_date=D AND created_by=X)
                    $monthHolidays = Holiday::where('created_by', $creatorId)
                         ->where(function ($q) use ($monthStart, $monthEnd)
                         {
                              $q->where(function ($inner) use ($monthStart, $monthEnd)
                              {
                                   $inner->where('status', 1)
                                        ->whereBetween('start_date', [$monthStart, $monthEnd]);
                              })->orWhereBetween('end_date', [$monthStart, $monthEnd]);
                         })
                         ->orderBy('id')
                         ->get();

                    $attendanceByDate = AttendanceEmployee::where('employee_id', $employee->id)
                         ->whereBetween('date', [$monthStart, $monthEnd])
                         ->orderBy('id')
                         ->get()
                         ->groupBy(function ($row)
                         {
                              return $row->getRawOriginal('date') ?: $row->date;
                         })
                         ->map(function ($rows)
                         {
                              return $rows->first();
                         });

                    foreach ($dates as $date)
                    {
                         $dateFormat = $year . '-' . $month . '-' . $date;
                         $days = date('l', strtotime($dateFormat));

                         $localLeave = $monthLeaves->first(function ($leave) use ($dateFormat)
                         {
                              $start = $leave->getRawOriginal('start_date') ?: $leave->start_date;
                              $end = $leave->getRawOriginal('end_date') ?: $leave->end_date;
                              return $start <= $dateFormat && $end >= $dateFormat;
                         });

                         $holiday = $monthHolidays->first(function ($row) use ($dateFormat)
                         {
                              $start = $row->getRawOriginal('start_date') ?: $row->start_date;
                              $end = $row->getRawOriginal('end_date') ?: $row->end_date;
                              return ((int) $row->status === 1 && $start == $dateFormat) || ($end == $dateFormat);
                         });

                         if ($holiday != null)
                         {
                              $row = $holiday;
                              $branch_ids = json_decode($row->branch_id) ?? [];
                              $department_ids = json_decode($row->department_id) ?? [];

                              $holidayData = [];

                              if (in_array(0, $branch_ids) && in_array(0, $department_ids))
                              {
                                   $holidayData = $row;
                              }
                              else if (!in_array(0, $branch_ids) && in_array(0, $department_ids))
                              {
                                   if (in_array($employee->branch_id, $branch_ids))
                                   {
                                        $holidayData = $row;
                                   }
                              }
                              else if (!in_array(0, $branch_ids) && !in_array(0, $department_ids))
                              {
                                   if (in_array($employee->branch_id, $branch_ids) && in_array($employee->department_id, $department_ids))
                                   {
                                        $holidayData = $row;
                                   }
                              }
                              else if (in_array(0, $branch_ids) && !in_array(0, $department_ids))
                              {
                                   if (in_array($employee->department_id, $department_ids))
                                   {
                                        $holidayData = $row;
                                   }
                              }

                              $holiday = $holidayData;
                         }

                         $isWeekOff = false;
                         if ($is_custom_weekoffs == 1)
                         {
                              if (in_array($dateFormat, $employee_weekoffs_data))
                              {
                                   $isWeekOff = true;
                              }
                         }
                         else
                         {
                              if (in_array($days, $weefOffArray))
                              {
                                   $isWeekOff = true;
                              }
                         }

                         if (!empty($localLeave))
                         {
                              if ($localLeave->total_leave_days == "0.5")
                              {
                                   $calenderData[] = [
                                        "date" => $dateFormat,
                                        "statusText" => "HD",
                                        "status" => 0,
                                        "colorCode" => $leaveColorCode,
                                   ];
                              }
                              else
                              {
                                   $period = CarbonPeriod::create($localLeave->getRawOriginal("start_date"), $localLeave->getRawOriginal("end_date"));
                                   foreach ($period as $rangeDate)
                                   {
                                        $calenderData[] = [
                                             "date" => $rangeDate->format('Y-m-d'),
                                             "statusText" => "L",
                                             "status" => 0,
                                             "colorCode" => $leaveColorCode,
                                        ];
                                   }
                              }
                         }
                         else
                         {
                              $employeeAttendance = $attendanceByDate[$dateFormat] ?? null;

                              if ($employeeAttendance != null && $employeeAttendance->status == 'Present')
                              {
                                   $calenderData[] = [
                                        "date" => $dateFormat,
                                        "statusText" => "P",
                                        "status" => 1,
                                        "colorCode" => $presentColorCode,
                                   ];
                              }
                              else
                              {
                                   if ($isWeekOff)
                                   {
                                        $calenderData[] = [
                                             "date" => $dateFormat,
                                             "statusText" => "WO",
                                             "status" => 2,
                                             "colorCode" => $weekOffColorCode,
                                        ];
                                   }
                                   else
                                   {
                                        $holidays_branch_ids = [];
                                        try
                                        {
                                             $holidays_branch_ids = json_decode($holiday->branch_id ?? "") ?? [];
                                        }
                                        catch (Exception | Throwable $ex)
                                        {
                                        }

                                        if (array_intersect([0, $employee->branch_id], $holidays_branch_ids))
                                        {
                                             $period = CarbonPeriod::create($holiday->getRawOriginal("start_date"), $holiday->getRawOriginal("end_date"));
                                             foreach ($period as $rangeDate)
                                             {
                                                  $addHoliday = true;
                                                  if ($is_custom_weekoffs == 1)
                                                  {
                                                       if (in_array($rangeDate->format('Y-m-d'), $employee_weekoffs_data))
                                                       {
                                                            $addHoliday = false;
                                                       }
                                                  }
                                                  else
                                                  {
                                                       $perios_days = date('l', strtotime($rangeDate->format('Y-m-d')));
                                                       if (in_array($perios_days, $weefOffArray))
                                                       {
                                                            $addHoliday = false;
                                                       }
                                                  }

                                                  if ($addHoliday)
                                                  {
                                                       $calenderData[] = [
                                                            "date" => $rangeDate->format('Y-m-d'),
                                                            "statusText" => "H",
                                                            "status" => 3,
                                                            "colorCode" => $holidayColorCode,
                                                       ];
                                                  }
                                             }
                                        }
                                        else
                                        {
                                             if ($dateFormat <= date('Y-m-d'))
                                             {
                                                  $calenderData[] = [
                                                       "date" => $dateFormat,
                                                       "statusText" => "A",
                                                       "status" => 4,
                                                       "colorCode" => $absentColorCode,
                                                  ];
                                             }
                                        }
                                   }
                              }
                         }
                    }

                    $response = [
                         'status' => 1,
                         'message' => "Employee Calender Data",
                         'data' => $calenderData,
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }
}
