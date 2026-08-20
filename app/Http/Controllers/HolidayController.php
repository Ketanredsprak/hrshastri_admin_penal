<?php

namespace App\Http\Controllers;

use App\Exports\HolidayExport;
use App\Imports\HolidayImport;
use App\Models\Holiday as LocalHoliday;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Throwable;

class HolidayController extends Controller
{

     public function index(Request $request)
     {
          // update comma seperated data to json encode
          // $data = LocalHoliday::get();
          // foreach ($data as $row)
          // {
          //      $row->branch_id = json_encode(explode(',', $row->branch_id));
          //      $row->save();
          // }
          // update comma seperated data to json encode
          try
          {
               if (Auth::user()->can('Manage Holiday'))
               {
                    $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
                    $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

                    $holidays = LocalHoliday::where('created_by', '=', Auth::user()->creatorId())
                         ->where('start_date', '>=', $startDate)
                         ->where('end_date', '<=', $endDate)
                         ->get();

                    $data = [];

                    if (Auth::user()->type == 'employee')
                    {
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
                    }
                    else
                    {
                         $data = $holidays;
                    }

                    $holidays = $data;

                    return view('holiday.index', compact('holidays', 'startDate', 'endDate'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Exception $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }


     public function create()
     {
          if (\Auth::user()->can('Create Holiday'))
          {
               $branches = Branch::where('created_by', '=', \Auth::user()->creatorId())->get();
               return view('holiday.create', compact('branches'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function store(Request $request)
     {

          if (\Auth::user()->can('Create Holiday'))
          {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         'branch_id' => 'required',
                         'department_id' => 'required',
                         'occasion' => 'required',
                         'start_date' => 'required',
                         'end_date' => 'required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $branch_id = $request->branch_id ?? [];
               $department_id = $request->department_id ?? [];

               if (in_array(0, $branch_id))
               {
                    $branch_id = [0];
               }

               if (in_array(0, $department_id))
               {
                    $department_id = [0];
               }

               $holiday = new LocalHoliday();
               $holiday->occasion          = $request->occasion;
               // $holiday->branch_id          = $request->branch_id;

               $holiday->branch_id     = json_encode($branch_id);
               $holiday->department_id = json_encode($department_id);

               $holiday->start_date        = $request->start_date;
               $holiday->end_date          = $request->end_date;
               $holiday->status = 1;
               $holiday->created_by = \Auth::user()->creatorId();
               $holiday->save();

               // slack
               $setting = Utility::settings(\Auth::user()->creatorId());
               if (isset($setting['Holiday_notification']) && $setting['Holiday_notification'] == 1)
               {
                    $msg = $request->occasion . ' ' . __("on") . ' ' . $request->date . '.';
                    Utility::send_slack_msg($msg);
               }

               // telegram
               $setting = Utility::settings(\Auth::user()->creatorId());
               if (isset($setting['telegram_Holiday_notification']) && $setting['telegram_Holiday_notification'] == 1)
               {
                    $msg = $request->occasion . ' ' . __("on") . ' ' . $request->date . '.';
                    Utility::send_telegram_msg($msg);
               }

               // google calendar
               if ($request->get('synchronize_type')  == 'google_calender')
               {

                    $type = 'holiday';
                    $request1 = new GoogleEvent();
                    $request1->title = $request->occasion;
                    $request1->start_date = $request->start_date;
                    $request1->end_date = $request->end_date;
                    // dd();
                    Utility::addCalendarData($request1, $type);
               }

               return redirect()->route('holiday.index')->with(
                    'success',
                    'Holiday successfully created.'
               );
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function show($id)
     {
          $holidays = LocalHoliday::where('id', $id)->first();
          return view('holiday.show', compact('holidays'));
     }


     public function edit(LocalHoliday $holiday)
     {
          if (\Auth::user()->can('Edit Holiday'))
          {
               $branches = Branch::where('created_by', '=', \Auth::user()->creatorId())->get();

               $selectedBranchIds = json_decode($holiday->branch_id ?? "");
               $selectedDepartmentIds = json_decode($holiday->department_id ?? "");

               $departments = [];
               if (in_array(0, $selectedBranchIds))
               {
                    $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();
               }
               else
               {
                    $departments = Department::whereIn("branch_id", $selectedBranchIds)->where('created_by', '=', Auth::user()->creatorId())->get();
               }

               return view('holiday.edit', compact('holiday', 'branches', 'departments'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function update(Request $request, LocalHoliday $holiday)
     {
          if (\Auth::user()->can('Edit Holiday'))
          {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         'occasion' => 'required',
                         'start_date' => 'required',
                         'end_date' => 'required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $branch_id = $request->branch_id ?? [];
               $department_id = $request->department_id ?? [];

               if (in_array(0, $branch_id))
               {
                    $branch_id = [0];
               }

               if (in_array(0, $department_id))
               {
                    $department_id = [0];
               }

               $holiday->occasion          = $request->occasion;

               $holiday->branch_id     = json_encode($branch_id);
               $holiday->department_id = json_encode($department_id);

               // $holiday->branch_id          = $request->branch_id;

               $holiday->start_date        = $request->start_date;
               $holiday->end_date          = $request->end_date;
               $holiday->save();

               return redirect()->route('holiday.index')->with(
                    'success',
                    'Holiday successfully updated.'
               );
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function destroy(LocalHoliday $holiday)
     {
          if (\Auth::user()->can('Delete Holiday'))
          {
               $holiday->delete();

               return redirect()->route('holiday.index')->with(
                    'success',
                    'Holiday successfully deleted.'
               );
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function calender(Request $request)
     {
          $branches = Branch::where('created_by', '=', \Auth::user()->creatorId())->get();
          // return 'sdf';
          if (\Auth::user()->can('Manage Holiday'))
          {
               $holidays = LocalHoliday::where('status', 1)->where('created_by', '=', \Auth::user()->creatorId());

               $today_date = date('m');
               // $current_month_event = Holiday::select( 'occasion','start_date','end_date', 'created_at')->whereRaw('MONTH(start_date)=' . $today_date,'MONTH(end_date)=' . $today_date)->get();
               $current_month_event = LocalHoliday::select('occasion', 'start_date', 'end_date', 'created_at', 'status')->where('status', 1)->whereNotNull(['start_date', 'end_date'])->whereMonth('start_date', $today_date)->whereMonth('end_date', $today_date)->where('created_by', '=', \Auth::user()->creatorId());


               //chang code by viral
               $employees = Employee::where('user_id', Auth::id())->where('created_by', '=', \Auth::user()->creatorId())->first();
               if (!empty($employees))
               {
                    $current_month_event = $current_month_event->where('branch_id', $employees->branch_id);
               }
               if (!empty($request->branch))
               {
                    $current_month_event = $current_month_event->where('branch_id', $request->branch);
               }
               $current_month_event = $current_month_event->get();

               if (!empty($request->start_date))
               {
                    $holidays->where('start_date', '>=', $request->start_date)->where('status', 1);
               }
               if (!empty($request->end_date))
               {
                    $holidays->where('end_date', '<=', $request->end_date)->where('status', 1);
               }

               if (!empty($request->branch))
               {

                    $holidays->where('branch_id', $request->branch)->where('status', 1);
               }
               $holidays = $holidays->get();

               $arrHolidays = [];

               foreach ($holidays as $holiday)
               {

                    $arr['id']        = $holiday['id'];
                    $arr['title']     = $holiday['occasion'];
                    $arr['start']     = $holiday['start_date'];
                    $arr['end']       = $holiday['end_date'];
                    $arr['className'] = 'event-primary';
                    $arr['url']       = route('holiday.edit', $holiday['id']);
                    $arrHolidays[]    = $arr;
               }
               // $arrHolidays = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrHolidays)));
               // return $current_month_event;
               $arrHolidays =  json_encode($arrHolidays);


               return view('holiday.calender', compact('arrHolidays', 'current_month_event', 'employees', 'branches'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function export(Request $request)
     {
          // dd($request->all());

          $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
          $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

          $name = 'holidays_' . date('Y-m-d i:h:s');

          try
          {
               $data = Excel::download(new HolidayExport($startDate, $endDate), $name . '.xlsx');
          }
          catch (Exception | Throwable $ex)
          {
               return redirect()->back()->with('error', $ex->getMessage());
          }


          return $data;
     }
     public function importFile(Request $request)
     {
          return view('holiday.import');
     }
     public function import(Request $request)
     {
          $rules = [
               'file' => 'required|mimes:csv,txt',
          ];
          $validator = \Validator::make($request->all(), $rules);

          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();

               return redirect()->back()->with('error', $messages->first());
          }
          $holidays = (new HolidayImport())->toArray(request()->file('file'))[0];

          $totalholiday = count($holidays);

          $errorArray    = [];
          foreach ($holidays as $holiday)
          {

               $holiydayData = LocalHoliday::whereDate('start_date', $holiday['start_date'])->whereDate('end_date', $holiday['end_date'])->where('occasion', $holiday['occasion'])->first();

               if (!empty($holiydayData))
               {
                    $errorArray[] = $holiydayData;
               }
               else
               {
                    $holidays_data = new LocalHoliday();
                    $holidays_data->start_date = $holiday['start_date'];
                    $holidays_data->end_date = $holiday['end_date'];
                    $holidays_data->occasion = $holiday['occasion'];
                    $holidays_data->created_by = Auth::user()->creatorId();
                    $holidays_data->save();
               }
          }

          if (empty($errorArray))
          {
               $data['status'] = 'success';
               $data['msg']    = __('Record successfully imported');
          }
          else
          {

               $data['status'] = 'error';
               $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalholiday . ' ' . 'record');


               foreach ($errorArray as $errorData)
               {
                    $errorRecord[] = implode(',', $errorData->toArray());
               }

               \Session::put('errorArray', $errorRecord);
          }

          return redirect()->back()->with($data['status'], $data['msg']);
     }

     public function get_holiday_data(Request $request)
     {
          // dd($request->all());
          $arrayJson = [];
          if ($request->get('calender_type') == 'google_calender')
          {
               $type = 'holiday';
               $arrayJson =  Utility::getCalendarData($type);
          }
          else
          {
               $data = LocalHoliday::where('status', 1)->where('created_by', '=', \Auth::user()->creatorId());
               $employees = Employee::where('user_id', Auth::id())->where('created_by', '=', \Auth::user()->creatorId())->first();

               if (!empty($employees))
               {
                    $data = $data->where('branch_id', $employees->branch_id);
               }
               if (!empty($request->branch_id))
               {
                    $data = $data->where('branch_id', $request->branch_id);
               }
               $data = $data->get();

               // $data = $data->where('branch_id',$employees->branch_id)->get();

               foreach ($data as $val)
               {
                    if (Auth::user()->type == 'employee')
                    {
                         $url = route('holiday.show', $val['id']);
                    }
                    else
                    {
                         $url = route('holiday.edit', $val['id']);
                    }
                    $end_date = date_create($val->end_date);
                    date_add($end_date, date_interval_create_from_date_string("1 days"));
                    $arrayJson[] = [
                         "id" => $val->id,
                         "title" => $val->occasion,
                         "start" => $val->start_date,
                         "end" => date_format($end_date, "Y-m-d H:i:s"),
                         "className" => $val->color,
                         "textColor" => '#FFF',
                         "allDay" => true,
                         "url" => $url,
                    ];
               }
          }

          return $arrayJson;
     }
     //  by disma
     public function active_holiday($id)
     {
          LocalHoliday::where('id', $id)->update(array('status' => 1));
          return redirect()->back()->with('success', 'Holiday Actived successfully.');
     }

     public function inactive_holiday($id)
     {
          LocalHoliday::where('id', $id)->update(array('status' => 0));
          return redirect()->back()->with('success', 'Holiday Inactived successfully.');
     }
}
