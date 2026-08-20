<?php

namespace App\Http\Controllers\API;

use Storage;
use App\Models\User;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\Resignation;
use Illuminate\Http\Request;
use App\Models\CompanyMobileMenu;
use App\Models\NotificationModel;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MobileMenu;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Throwable;

class MobileMenuController extends Controller
{
     public function get_mobile_menu(request $request)
     {
          try
          {
               $menuData = MobileMenu::select("*", "icon as icon_url")
                    ->whereHas('CompanyMobileMenu', function ($query)
                    {
                         $query->where('company_id', Auth::user()->creatorId());
                    })
                    ->where("parent_id", 0)
                    ->orderBy("sequence_number", "ASC")
                    ->with("SubMenuCompany")
                    ->get();

               return $menuData;
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(showExceptionMessage($e));
          }
     }

     public function GetMobileMenuForGeofancing(request $request)
     {
          $response = [];
          try
          {
               $showAddEmployeeAttendanceScreen = false;


               $customer_data =  User::find(Auth::id());
               // $emp = Employee::select("*", "employee_profile as employee_profile_image")->where('user_id', '=', $customer_data->id)->first();

               if (Auth::user()->type != 'employee' && $customer_data->hasPermissionTo('Add Multiple Employee Attendance', 'web'))
               {
                    $showAddEmployeeAttendanceScreen = true;
               }

               $hideScreensArray = ["AddEmployeeAttendanceScreen"];

               $menuData = MobileMenu::select("*", "icon as icon_url")
                    ->whereHas('CompanyMobileMenu', function ($query)
                    {
                         $query->where('company_id', Auth::user()->creatorId());
                    })
                    ->when(!$showAddEmployeeAttendanceScreen, function ($query) use ($hideScreensArray)
                    {
                         $query->whereNotIn("title", $hideScreensArray);
                    })
                    ->where("parent_id", 0)
                    ->orderBy("sequence_number", "ASC")
                    ->with("SubMenuCompany")
                    ->get();

               $response = [
                    'status' => 1,
                    'message' => __("Mobile Menu."),
                    "data" => $menuData
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = [
                    'status' => 0,
                    'message' => showExceptionMessage($e),
                    "data" => []
               ];
          }

          return ApiResponse($response);
     }

     // public function get_mobile_menu(request $request)
     // {
     //      return $this->getMobileMenu($request);
     //      try
     //      {

     //           $data = CompanyMobileMenu::join('mobile_menu', 'company_mobile_menu.menu_id', 'mobile_menu.id')
     //                ->where('company_id', Auth::user()->creatorId())
     //                ->select('company_mobile_menu.menu_id', 'mobile_menu.sequence_number', 'mobile_menu.*')->orderBy('mobile_menu.sequence_number', 'asc')->get();
     //           if (!empty($data))
     //           {
     //                foreach ($data as $row)
     //                {
     //                     $row->icon_URL = Storage::url('/public/mobileIcon/' . $row->icon);
     //                }
     //           }
     //           $response = [
     //                'status' => 1,
     //                'message' => 'success',
     //                'data'  => $data
     //           ];

     //           // dd($data->count());
     //           return ApiResponse($response);
     //      }
     //      catch (Exception | Throwable $e)
     //      {
     //           return ApiResponse(showExceptionMessage($e));
     //      }
     // }



     public function create_resignation(Request $request)
     {
          try
          {
               DB::beginTransaction();

               $resignation = new Resignation();
               $user        = Auth::user();
               $employee    = Employee::where('user_id', $user->id)->first();
               $superior_user = null;
               if ($employee)
               {
                    $superior_user = $employee->users_types_id;
               }
               $resignation->employee_id = $employee->id;
               // $resignation->superior_user  = $superior_user;
               $resignation->notice_date       = $request->notice_date ?? null;
               $resignation->resignation_date  = $request->resignation_date ?? null;
               $resignation->description       = $request->description ?? "";
               $resignation->status            = 0;
               $resignation->created_by       = Auth::user()->creatorId();
               $resignation->save();

               //notification Code
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $resignation_email_notification = Utility::getValByName('resignation_email_notification') ?? '';
               if(!empty($resignation_email_notification)) {
                    $resignation_email = explode(',', $resignation_email_notification);
                    
                    foreach($resignation_email as $email) {
                         $uArr = [
                              'resigned_user' => $employee->name,
                              'resignation_date'  => date('d-m-Y'),
                              'notice_date' => '',
                         ];
                         $resp = Utility::sendEmailTemplate('employee_resignation', [$email], $uArr);
                    }
               }
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               foreach ($upUser as $row)
               {
                    foreach ((json_decode($row['assign_employees']) ?? []) as $key => $user)
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
               $response = [
                    'status' => 1,
                    'message' => __("Resignation Created Successfully."),
               ];
               DB::commit();
               return ApiResponse($response);
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               $error_msg =  showExceptionMessage($e);
               return ApiResponse($error_msg);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               $error_msg =  showExceptionMessage($e);
               return ApiResponse($error_msg);
          }
     }

     public function get_resignation(Request $request)
     {
          try
          {
               $emp = Employee::where('user_id', '=', Auth::user()->id)->first();
               $data = Resignation::where('created_by', '=', Auth::user()->creatorId())->where('employee_id', '=', $emp->id);
               $result = paginate($data, ($request->page ?? null));
               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function delete_resignation(Request $request)
     {
          try
          {
               DB::beginTransaction();

               $data = Resignation::find($request->id);
               $data->delete();

               DB::commit();

               $response = [
                    'status' => 1,
                    'message' => __("Resignation Deleted Successfully."),
               ];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function update_resignation(Request $request)
     {
          try
          {
               DB::beginTransaction();

               $resignation = Resignation::find($request->id);
               // $resignation->notice_date      = $request->notice_date ?? null;
               $resignation->resignation_date = $request->resignation_date ?? null;
               $resignation->description      = $request->description ?? "";
               $resignation->save();

               DB::commit();

               $response = [
                    'status' => 1,
                    'message' => __("Resignation Updated Successfully."),
               ];

               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }
}
