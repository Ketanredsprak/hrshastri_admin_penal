<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Utility;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnnouncementController extends Controller
{
     public function index()
     {

          // update comma seperated data to json encode
          // $data = Announcement::get();
          // foreach ($data as $row)
          // {
          //      $columnsToConvert = ['branch_id', 'department_id', 'employee_id'];

          //      $row->branch_id = json_encode(explode(',', $row->branch_id));
          //      $row->department_id = json_encode(explode(',', $row->department_id));
          //      $row->employee_id = json_encode(explode(',', $row->employee_id));

          //      $row->save();
          // }
          // update comma seperated data to json encode

          if (\Auth::user()->can('Manage Announcement'))
          {

               if (Auth::user()->type == 'employee')
               {
                    $current_employee = Employee::where('user_id', Auth::id())->first();
                    // $announcements    = Announcement::orderBy('announcements.id', 'desc')->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $current_employee->id)->orWhere(
                    //     function ($q){
                    //         $q->where('announcements.department_id', '["0"]')->where('announcements.employee_id', '["0"]');
                    //     }
                    // )->get();
                    // $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')
                    // ->where('announcement_employees.employee_id', '=', $current_employee->id)
                    // ->orWhere(
                    //     function ($q){
                    //         $q->where('announcements.department_id', 0)
                    //         ->where('announcements.employee_id', 0);
                    //     }
                    // )->where('announcement_employees.created_by',\Auth::user()->creatorId())->get();
                    $announcements    = Announcement::where('created_by', '=', \Auth::user()->creatorId())->with("branch", "department")->get();
               }
               else
               {
                    $current_employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                    $announcements    = Announcement::where('created_by', '=', \Auth::user()->creatorId())->with("branch", "department")->get();
               }

               return view('announcement.index', compact('announcements', 'current_employee'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Announcement'))
          {

               $employees = Employee::where('created_by', \Auth::user()->creatorId())
                    ->where("employee_exit", 0)
                    ->get()

                    ->pluck('name', 'id');
               $employees->prepend('All', 0);

               // $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               // $branch->prepend('All', 0);

               // $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               // $departments->prepend('All', 0);

               // $employees   = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();
               $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();

               return view('announcement.create', compact('employees', 'branch', 'departments'));
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
               if (Auth::user()->can('Create Announcement'))
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'title' => 'required',
                              'start_date' => 'required',
                              'end_date' => 'required|after_or_equal:start_date',
                              'branch_id' => 'required',
                              'department_id' => 'required',
                              'employee_id' => 'required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }

                    DB::beginTransaction();
                    try
                    {

                         $branch_id = $request->branch_id ?? [];
                         $department_id = $request->department_id ?? [];
                         $employee_id = $request->employee_id ?? [];



                         if (in_array(0, $branch_id))
                         {
                              $branch_id = [0];
                         }

                         if (in_array(0, $department_id))
                         {
                              $department_id = [0];
                         }

                         if (in_array(0, $employee_id))
                         {
                              $employee_id = [0];
                         }

                         $announcement                = new Announcement();
                         $announcement->title         = $request->title;
                         $announcement->start_date    = $request->start_date;
                         $announcement->end_date      = $request->end_date;

                         $announcement->branch_id     = json_encode($branch_id);
                         $announcement->department_id = json_encode($department_id);
                         $announcement->employee_id   = json_encode($employee_id);

                         $announcement->description   = $request->description;
                         $announcement->created_by    = Auth::user()->creatorId();
                         $announcement->save();


                         if (in_array('0', $request->employee_id ?? []))
                         {
                              $departmentEmployee = Employee::whereIn('department_id', $request->department_id)->get()->pluck('id');
                              $departmentEmployee = $departmentEmployee;
                         }
                         else
                         {
                              $departmentEmployee = $request->employee_id ?? [];
                         }

                         foreach ($departmentEmployee as $employee)
                         {
                              $announcementEmployee                  = new AnnouncementEmployee();
                              $announcementEmployee->announcement_id = $announcement->id;
                              $announcementEmployee->employee_id     = $employee;
                              $announcementEmployee->created_by      = Auth::user()->creatorId();

                              $announcementEmployee->save();
                         }

                         DB::commit();
                    }
                    catch (Exception | Throwable $ex)
                    {
                         DB::rollBack();
                         return redirect()->back()->with('error', showExceptionMessage($ex));
                    }


                    // slack
                    $setting = Utility::settings(Auth::user()->creatorId());
                    $branch = Branch::find($request->branch_id);
                    if (isset($setting['Announcement_notification']) && $setting['Announcement_notification'] == 1)
                    {
                         $msg = $request->title . ' ' . __("announcement created for branch") . ' ' . $branch->name . ' ' . __("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';
                         Utility::send_slack_msg($msg);
                    }

                    // telegram
                    $setting = Utility::settings(Auth::user()->creatorId());
                    $branch = Branch::find($request->branch_id);
                    if (isset($setting['telegram_Announcement_notification']) && $setting['telegram_Announcement_notification'] == 1)
                    {
                         $msg = $request->title . ' ' . __("announcement created for branch") . ' ' . $branch->name . ' ' . __("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';
                         Utility::send_telegram_msg($msg);
                    }

                    // twilio
                    $setting = Utility::settings(Auth::user()->creatorId());
                    $branch = Branch::find($request->branch_id);

                    if (isset($setting['twilio_announcement_notification']) && $setting['twilio_announcement_notification'] == 1)
                    {
                         $employeess = Employee::whereIn('branch_id', $request->employee_id)->get();
                         foreach ($employeess as $key => $employee)
                         {
                              $msg = $request->title . ' ' . __("announcement created for branch") . ' ' . $branch->name . ' ' . __("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';
                              Utility::send_twilio_msg($employee->phone, $msg);
                         }
                    }

                    return redirect()->route('announcement.index')->with('success', __('Announcement  successfully created.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Exception | Throwable $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function show(Announcement $announcement)
     {
          return redirect()->route('announcement.index');
     }

     public function edit($announcement)
     {
          try
          {
               if (Auth::user()->can('Edit Announcement'))
               {
                    $announcement = Announcement::find($announcement);
                    if ($announcement->created_by == Auth::user()->creatorId())
                    {
                         $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();

                         $selectedBranchIds = json_decode($announcement->branch_id ?? "");
                         $selectedDepartmentIds = json_decode($announcement->department_id ?? "");

                         $departments = [];
                         if (in_array(0, $selectedBranchIds))
                         {
                              $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();
                         }
                         else
                         {
                              $departments = Department::whereIn("branch_id", $selectedBranchIds)->where('created_by', '=', Auth::user()->creatorId())->get();
                         }

                         // -----------------------------------

                         $branch_ids = json_decode($announcement->branch_id);
                         $department_ids = json_decode($announcement->department_id);
                         $employee_ids = json_decode($announcement->employee_id);

                         $employees = [];
                         // if all branchs, all departments and all employees | get all employees data
                         if (in_array(0, $branch_ids) && in_array(0, $department_ids))
                         {
                              $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->where("employee_exit", 0)->get();
                         }
                         // if not all branches, but for all departments | get employees data based on branch only
                         else if (!in_array(0, $branch_ids) && in_array(0, $department_ids))
                         {

                              $employees = Employee::where('created_by', '=', Auth::user()->creatorId())
                                   ->whereIn('branch_id', $selectedBranchIds)
                                   ->where("employee_exit", 0)
                                   ->get();

                              // dd("in 2", $employees);
                         }

                         // if not all branched and not all departments  | get employees data based on branch and department
                         else if (!in_array(0, $branch_ids) && !in_array(0, $department_ids))
                         {
                              // dd("in 3");
                              $employees = Employee::where('created_by', '=', Auth::user()->creatorId())
                                   ->whereIn('branch_id', $selectedBranchIds)
                                   ->whereIn('department_id', $selectedDepartmentIds)
                                   ->where("employee_exit", 0)
                                   ->get();
                         }

                         // -----------------------------------

                         return view('announcement.edit', compact('announcement', 'branch', 'departments', 'employees'));
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
          catch (Exception | Throwable $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function update(Request $request, Announcement $announcement)
     {
          try
          {
               if (\Auth::user()->can('Edit Announcement'))
               {
                    if ($announcement->created_by == \Auth::user()->creatorId())
                    {

                         $validator = \Validator::make(
                              $request->all(),
                              [
                                   'title' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   'branch_id' => 'required',
                                   'department_id' => 'required',
                                   'employee_id' => 'required',
                              ]
                         );
                         if ($validator->fails())
                         {
                              $messages = $validator->getMessageBag();

                              return redirect()->back()->with('error', $messages->first());
                         }

                         $branch_id = $request->branch_id ?? [];
                         $department_id = $request->department_id ?? [];
                         $employee_id = $request->employee_id ?? [];

                         if (in_array(0, $branch_id))
                         {
                              $branch_id = [0];
                         }

                         if (in_array(0, $department_id))
                         {
                              $department_id = [0];
                         }

                         if (in_array(0, $employee_id))
                         {
                              $employee_id = [0];
                         }

                         DB::beginTransaction();
                         try
                         {

                              $announcement->title         = $request->title;
                              $announcement->start_date    = $request->start_date;
                              $announcement->end_date      = $request->end_date;

                              // $announcement->branch_id     = $request->branch_id;
                              // $announcement->department_id = implode(",", $request->department_id);

                              $announcement->branch_id     = json_encode($branch_id);
                              $announcement->department_id = json_encode($department_id);
                              $announcement->employee_id   = json_encode($employee_id);

                              $announcement->description   = $request->description;
                              $announcement->save();


                              if (in_array('0', $request->employee_id ?? []))
                              {
                                   $departmentEmployee = Employee::whereIn('department_id', $request->department_id)->get()->pluck('id');
                                   $departmentEmployee = $departmentEmployee;
                              }
                              else
                              {
                                   $departmentEmployee = $request->employee_id ?? [];
                              }

                              AnnouncementEmployee::where("announcement_id", $announcement->id)->delete();
                              foreach ($departmentEmployee as $employee)
                              {
                                   $announcementEmployee                  = new AnnouncementEmployee();
                                   $announcementEmployee->announcement_id = $announcement->id;
                                   $announcementEmployee->employee_id     = $employee;
                                   $announcementEmployee->created_by      = Auth::user()->creatorId();

                                   $announcementEmployee->save();
                              }

                              DB::commit();
                         }
                         catch (Exception | Throwable $ex)
                         {
                              DB::rollBack();
                              return redirect()->back()->with('error', showExceptionMessage($ex));
                         }


                         return redirect()->route('announcement.index')->with('success', __('Announcement successfully updated.'));
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
          catch (Exception | Throwable $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function destroy(Announcement $announcement)
     {
          if (\Auth::user()->can('Delete Announcement'))
          {
               if ($announcement->created_by == \Auth::user()->creatorId())
               {
                    $announcement->delete();

                    return redirect()->route('announcement.index')->with('success', __('Announcement successfully deleted.'));
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

     public function getdepartment(Request $request)
     {
          $branchIds = $request->branch_id ?? [];
          if (in_array(0, $branchIds))
          {
               $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
          }
          else
          {
               $departments = Department::where('created_by', '=', Auth::user()->creatorId())->whereIn('branch_id', $branchIds)->get()->pluck('name', 'id')->toArray();
          }

          return response()->json($departments);
     }

     public function getemployee(Request $request)
     {
          $department_id = $request->department_id ?? [];
          if (in_array(0, $department_id))
          {
               $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id')->toArray();
          }
          else
          {
               $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->where("employee_exit", 0)->whereIn('department_id', $department_id)->get()->pluck('name', 'id')->toArray();
          }
          return response()->json($employees);
     }
}
