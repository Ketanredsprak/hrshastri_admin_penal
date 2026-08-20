<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Employee;
use App\Models\Transfer;
use App\Models\Promotion;
use App\Models\Termination;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\CompanyPolicy;
use App\Models\DucumentUpload;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Holiday;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EmployeeModulesController extends Controller
{
     public function get_transfer(request $request)
     {
          try
          {
               $emp        = Employee::where('user_id', '=', Auth::user()->id)->first();
               $data       = Transfer::join('branches', 'transfers.branch_id', 'branches.id')
                    ->join('departments', 'transfers.department_id', 'departments.id')
                    ->where('transfers.created_by', '=', Auth::user()->creatorId())
                    ->where('employee_id', '=', $emp->id)
                    ->select('transfers.*', 'branches.name as branche_name', 'departments.name as department_name')
                    ->orderBy('transfers.id', 'DESC');

               $result = paginate($data, ($request->page ?? null));

               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_promotion(request $request)
     {
          try
          {
               $emp        = Employee::where('user_id', '=', Auth::user()->id)->first();
               $data       = Promotion::join('designations', 'promotions.designation_id', 'designations.id')
                    ->where('promotions.created_by', '=', Auth::user()->creatorId())
                    ->select('promotions.*', 'designations.name as designation_name')
                    ->where('employee_id', '=', $emp->id)
                    ->orderBy('promotions.id', 'DESC');
               $result = paginate($data, ($request->page ?? null));
               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_announcement(request $request)
     {
          try
          {
               $emp = Employee::where('user_id', '=', Auth::user()->id)->first();

               $data = [];

               $branchIds = [0, $emp->branch_id];
               $announcements = Announcement::
                    // whereJsonContains('branch_id', $branchIds)
                    // whereIn("branch_id", $branchIds)
                    // ->where("employee_id", [0, $emp->id])
                    // ->where("end_date", "<=", date("Y-m-d"))
                    where("created_by", Auth::user()->creatorId())
                    ->whereDate("end_date", ">=", date("Y-m-d"))
                    ->orderBy('id', 'desc')
                    ->get();

               foreach ($announcements as $row)
               {
                    $branch_ids = json_decode($row->branch_id) ?? [];
                    $department_ids = json_decode($row->department_id) ?? [];
                    $employee_ids = json_decode($row->employee_id) ?? [];


                    // scenarios
                    // scenarios 1 : if all branched selected, all departments selected and all employees selected | then show announcement to employee
                    // scenarios 2 : if not all branches selected, but for all departments are selected | then show announcement to employee
                    // scenarios 2.1 : if all employees are selected or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch


                    // if all branchs, all departments and all employees
                    if (in_array(0, $branch_ids) && in_array(0, $department_ids) && (in_array(0, $employee_ids) || in_array($emp->id, $employee_ids)))
                    {
                         $data[] = $row;
                    }

                    // if not all branches, but for all departments
                    else if (!in_array(0, $branch_ids) && in_array(0, $department_ids))
                    {
                         // check if all employees are allowed or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch
                         if (
                              in_array(0, $employee_ids) ||
                              (
                                   in_array($emp->id, $employee_ids) && in_array($emp->branch_id, $branch_ids)
                              )
                         )
                         {
                              $data[] = $row;
                         }
                    }

                    // if not all branched and not all departments
                    else if (!in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         // check if employee branch and depasrtment is selected
                         if (in_array($emp->branch_id, $branch_ids) && in_array($emp->department_id, $department_ids))
                         {
                              // check if all employees is selected or this employee is selected
                              if (in_array(0, $employee_ids) || in_array($emp->id, $employee_ids))
                              {
                                   $data[] = $row;
                              }
                         }
                    }
                    else if (in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         if (in_array($emp->department_id, $department_ids))
                         {
                              if (in_array(0, $employee_ids) || in_array($emp->id, $employee_ids))
                              {
                                   $data[] = $row;
                              }
                         }
                    }
               }

               // $result = paginate($data, ($request->page ?? null));
               return ApiResponse(["status" => 1, "message" => __("Announcements List"), "data" => $data]);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_holiday(request $request)
     {
          try
          {
               // $data = Holiday::leftjoin('branches', 'holidays.branch_name', 'branches.id')
               //      ->where('holidays.status', 1)
               //      ->where('holidays.created_by', '=', Auth::user()->creatorId())
               //      ->select('holidays.*', 'branches.name as branch_name', 'holidays.branch_name as branch_id');
               // $employees = Employee::where('user_id', Auth::id())->where('created_by', '=', Auth::user()->creatorId())->first();
               // $data = $data
               //      ->whereIn('branch_name', [0, $employees->branch_id])
               //      ->orderBy('id', 'DESC');
               // $result = paginate($data, ($request->page ?? null));
               // return ApiResponse($result);

               $data = [];

               $holidays = Holiday::where('created_by', '=', Auth::user()->creatorId())->get();

               $emp = Employee::where('user_id', '=', Auth::user()->id)->first();

               foreach ($holidays as $row)
               {
                    $branch_ids = json_decode($row->branch_id) ?? [];
                    $department_ids = json_decode($row->department_id) ?? [];


                    // scenarios
                    // scenarios 1 : if all branched selected, all departments selected and all employees selected | then show announcement to employee
                    // scenarios 2 : if not all branches selected, but for all departments are selected | then show announcement to employee
                    // scenarios 2.1 : if all employees are selected or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch


                    // if all branchs, all departments
                    if (in_array(0, $branch_ids) && in_array(0, $department_ids))
                    {
                         $data[] = $row;
                    }

                    // if not all branches, but for all departments
                    else if (!in_array(0, $branch_ids) && in_array(0, $department_ids))
                    {
                         // check if all employees are allowed or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch
                         if (in_array($emp->branch_id, $branch_ids))
                         {
                              $data[] = $row;
                         }
                    }

                    // if not all branched and not all departments
                    else if (!in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         if (in_array($emp->branch_id, $branch_ids) && in_array($emp->department_id, $department_ids))
                         {
                              $data[] = $row;
                         }
                    }
                    // if all branched and not all departments
                    else if (in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         if (in_array($emp->department_id, $department_ids))
                         {
                              $data[] = $row;
                         }
                    }
               }

               return ApiResponse(["status" => 1, "message" => __("Holidays List"), "data" => $data]);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_document(request $request)
     {
          try
          {
               $userRole  = Auth::user()->roles->first();
               $data = DucumentUpload::with('document_typeData')->whereIn('role', [$userRole->id, 0,])->where('created_by', Auth::user()->creatorId())->get();
               foreach ($data as $row)
               {
                    $row->document_file = Storage::url('uploads/documentUpload/' . $row->document);
               }
               if (!empty($data))
               {
                    $response = [
                         'status' => 1,
                         'message' => 'success',
                         'data'    => $data
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
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_termination(request $request)
     {
          try
          {
               $emp = Employee::where('user_id', '=', Auth::user()->id)->first();
               $data = Termination::join('termination_types', 'terminations.termination_type', 'termination_types.id')
                    ->where('terminations.created_by', Auth::user()->creatorId())
                    ->select('terminations.*', 'termination_types.name as termination_type_name')
                    ->where('employee_id', '=', $emp->id)->get();
               if (count($data) > 0)
               {
                    $response = [
                         'status' => 1,
                         'message' => __("Termination list."),
                         'data'    => $data
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
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_company_policy(request $request)
     {
          try
          {
               $data = CompanyPolicy::where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
               foreach ($data as $row)
               {
                    $row->companyPolicyFile =  Storage::url('uploads/companyPolicy/' . $row->attachment);
               }
               if (count($data) > 0)
               {
                    $response = [
                         'status' => 1,
                         'message' => __("Company Policy list."),
                         'data' => $data,
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
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_events(request $request)
     {
          $response = [];

          try
          {
               $data = Event::orderBy('id', 'desc')
                    ->where("created_by", Auth::user()->creatorId())
                    ->where("start_date", ">=", date("Y-m-d"))
                    ->get();

               $events = [];


               foreach ($data as $row)
               {
                    // $branch_id = $row->branch_id;
                    // $department_ids = json_decode($row->department_id);
                    // $employee_ids = json_decode($row->employee_id);

                    // if (
                    //      ($branch_id == $employeesData->branch_id || $branch_id == 0) ||
                    //      (in_array(0, $department_ids) || in_array($employeesData->department_id, $department_ids))
                    //      || (in_array(0, $employee_ids) || in_array($employeesData->id, $employee_ids))
                    // )
                    // {
                    //      $events[] = $row;


                    $branch_ids = json_decode($row->branch_id) ?? [];
                    $department_ids = json_decode($row->department_id) ?? [];
                    $employee_ids = json_decode($row->employee_id) ?? [];

                    // --------------

                    // scenarios
                    // scenarios 1 : if all branched selected, all departments selected and all employees selected | then show announcement to employee
                    // scenarios 2 : if not all branches selected, but for all departments are selected | then show announcement to employee
                    // scenarios 2.1 : if all employees are selected or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch

                    $emp = Employee::where('user_id', '=', Auth::user()->id)->first();

                    // if all branchs, all departments and all employees
                    if (in_array(0, $branch_ids) && in_array(0, $department_ids) && (in_array(0, $employee_ids) || in_array($emp->id, $employee_ids)))
                    {
                         $events[] = $row;
                    }

                    // if not all branches, but for all departments
                    else if (!in_array(0, $branch_ids) && in_array(0, $department_ids))
                    {
                         // check if all employees are allowed or if specific employee then check logged in employee is added in list or not and its branch is equal to announcement branch
                         if (
                              in_array(0, $employee_ids) ||
                              (
                                   in_array($emp->id, $employee_ids) && in_array($emp->branch_id, $branch_ids)
                              )
                         )
                         {
                              $events[] = $row;
                         }
                    }

                    // if not all branched and not all departments
                    else if (!in_array(0, $branch_ids) && !in_array(0, $department_ids))
                    {
                         if (in_array(0, $employee_ids) || in_array($emp->id, $employee_ids))
                         {
                              $events[] = $row;
                         }
                    }

                    // --------------
               }

               if (!empty($events))
               {
                    $response = [
                         'status' => 1,
                         'message' => __("Events List"),
                         'data'    => [
                              'event' => $events,
                              'currunt_month_event' => $data
                         ]
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data Not Found',
                         'data'    => [
                              'event' => [],
                              'currunt_month_event' => []
                         ]
                    ];
               }
          }
          catch (Exception | Throwable $ex)
          {
               $response = [
                    'status' => 0,
                    'message' => showExceptionMessage($ex),
                    'data'    => [
                         'event' => [],
                         'currunt_month_event' => []
                    ]
               ];
          }
          return ApiResponse($response);
     }

     public function get_upcomming_birthday(request $request)
     {
          try
          {
               $emp = User::find(Auth::id());
               $employee = Employee::select("*", "employee_profile as employee_profile_image")->where('user_id', ($emp->id ?? 0))->first();
               $employeeId = $employee->id;

               $is_todays_birthdays = $request->is_todays_birthdays ?? 1; // 0 = no , 1 = yes get todays birthdays only

               $dateAfter15Days = Carbon::now()->addDays(15);

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
                    ->where("id", "!=", $employeeId)
                    ->orderBy('dobd', 'ASC')
                    ->where("employee_exit", 0)
                    ->get();

               foreach ($data as $row)
               {
                    $user = User::find($row->user_id);
                    if ($user != null)
                    {
                         $row->date_of_birth = date("d-M", strtotime($row->dob));
                         $row->emp_name = $user->email;
                         $row->avatar = $row->employee_profile_image ?? "";
                    }
               }
               if (!empty($data))
               {
                    $response = [
                         'status' => 1,
                         'message' => __("Upcomming Birthdays list."),
                         'data'    => $data
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data Not Found',
                         'data'    => []
                    ];
               }
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }
}
