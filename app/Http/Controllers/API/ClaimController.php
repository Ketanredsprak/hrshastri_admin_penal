<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CityTier;
use App\Models\Employee;
use App\Models\GradeType;
use App\Models\VisitType;
use App\Models\Department;
use App\Models\CommuteType;
use App\Models\MyClaimModel;
use App\Models\MyVisitModel;
use Illuminate\Http\Request;
use App\Models\GradeTierData;
use App\Models\CategoriesType;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;
use Throwable;

class ClaimController extends Controller
{
     public function create_claim(request $request)
     {
          // return [
          //      "Parameters List" => $request->all()
          // ];

          $employee = Employee::where('user_id', Auth::id())->first();
          $myvisit = MyVisitModel::find($request->my_visit_id);
          $grade_id = GradeType::where('assign_grade_id', $employee->grade_type_id)->first();
          $datatier = [];
          if (!empty($myvisit->tier_id))
          {
               $datatier = GradeTierData::with('expenseData')->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id)->get();
          }

          // dd($request->expens_amount, json_encode($request->expens_amount), json_encode(array_values($request->expens_amount)));

          $arr = [];
          $is_actual = [];

          $expense_ids = [];

          if (count($datatier) > 0)
          {
               foreach ($request->expens_name as $key => $expense_id)
               {
                    $grade_tier_data = GradeTierData::with('expenseData')->find($expense_id);
                    if ($grade_tier_data != null)
                    {
                         if ($grade_tier_data->is_actual == 0 && $request->expens_amount[$key] > $grade_tier_data->amount)
                         {
                              $response = [
                                   'status' => 0,
                                   // 'message' => ['expens_amount' => ['You have exceeded the amount given by company']],
                                   'message' => 'You have exceeded the amount given by company',
                              ];
                              return ApiResponse($response);
                         }
                         $is_actual[] = $grade_tier_data->is_actual;
                         array_push($arr, $grade_tier_data->expenseData->name);

                         $expense_ids[] = $expense_id;
                    }
               }

               // dd($arr);

               $validator = Validator::make(
                    $request->all(),
                    [
                         'my_visit_id' => 'required',
                         'description' => 'required',
                         'expens_name.*' => 'required',
                         'expens_amount.*' => 'required_with:expens_name.*',
                    ],
                    [
                         'commuting_type.required' => 'Vehicle type field is required',
                         'description.required' => 'Description field is required',
                         'expens_name.*.required' => 'Expense name field is required',
                         'expens_amount.*.required_with' => 'Expense amount field is required',
                    ]
               );
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'my_visit_id' => 'required',
                         'commuting_type' => 'required',
                         'kilomiter' => 'required',
                         'description' => 'required',
                    ],
                    [
                         'commuting_type.required' => 'Vehicle type field is required',
                         'description.required' => 'Description field is required',
                         'kilomiter.required' => 'Kilomiter field is required',
                         'misc.required' => 'Misc field is required',
                    ]
               );
          }
          if ($validator->fails())
          {
               $messages = $validator->messages();
               $response = [
                    'status' => 0,
                    'message' => $messages,
               ];
               return ApiResponse($response);
          }
          $en_uploaded = '';
          try
          {
               DB::beginTransaction();
               if ($request->hasFile('uploadfile'))
               {
                    $image = $request->file('uploadfile');
                    $en_uploaded = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/fileupload');
                    $image->move($destinationPath, $en_uploaded);
               }
               $data = new MyClaimModel();
               $data->my_visit_id = $request->my_visit_id;
               $data->commuting_type = $request->commuting_type;
               $data->kilomiter = $request->kilomiter;
               $data->description = $request->description;
               // $data->fix_amount = $request->fix_amount;
               $data->fix_amount = $request->total_amount;
               $data->total_amount = $request->total_amount;
               $data->misc = $request->misc;


               $expencesNames = [];
               $expencesAmounts = [];
               $expencesIds = [];

               if (!empty($request->expens_name))
               {
                    for ($i = 0; $i <= count($request->expens_name) - 1; $i++)
                    {
                         $grade_tier_data = GradeTierData::with('expenseData')->find(($request->expens_name[$i] ?? 0));
                         if ($grade_tier_data != null)
                         {
                              // $expencesData[] = [$request->expens_name[$i] => $request->expens_amount[$i]];
                              // $expencesData[] = [$grade_tier_data->expenseData->name => $request->expens_amount[$i]];

                              $expencesNames[] = ($grade_tier_data->expenseData->name ?? '');
                              $expencesAmounts[] = ($request->expens_amount[$i] ?? 0);
                              $expencesIds[] = ($request->expens_name[$i] ?? 0);
                         }
                    }
               }

               // dd($expencesNames, $expencesIds);
               $data->expens_name = json_encode(array_values($expencesNames));
               $data->expens_amount = json_encode(array_values($expencesAmounts));
               $data->expense_ids = json_encode($expencesIds);

               $data->hidden_expense_is_actual = json_encode($is_actual);

               $data->fileupload = $en_uploaded;
               $data->status = 'Pending';
               $data->userid = $employee->id;
               $data->created_by = Auth::user()->creatorId();
               $data->visit_type = $request->visit_type ?? 0;
               $data->name = $request->claim_name ?? "";
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
                                   $notification = new NotificationModel();
                                   $notification->user_id = $row->id;
                                   $notification->employee_id = $employee->id;
                                   $notification->title = $employee->name . " has requested for claim";
                                   $notification->details = $employee->name . " has requested for claim";
                                   $notification->status = 0;
                                   $notification->date = Carbon::now();
                                   $notification->created_by = $employee->created_by;
                                   $notification->redirect = 'my_claim';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               $response = [
                    'status' => 1,
                    'message' => __('Claim Created Successfully.')
               ];

               // dd("before commit");
               DB::commit();
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function claim_edit(request $request)
     {

          // Log::info("Edit CLaim", $request->all());

          $data = MyClaimModel::with('myvisitData')->find($request->my_claim_id);

          if ($data == null)
          {
               return response()->json(['status' => 0, 'message' => __("No data found.")]);
          }

          $visit_type = $data->visit_type ?? 0; // 0 = normal visit, 1 = other visit
          $arr = [];
          $is_actual = [];
          $expense_ids = [];

          if ($visit_type == 0) // normal visit
          {
               if (($data->myvisitData->visit_type ?? 0) == 2)
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'my_visit_id' => 'required',
                              'description' => 'required',
                              'expens_name.*' => 'required',
                              'expens_amount.*' => 'required_with:expens_name.*',
                         ],
                         [
                              'description.required' => 'Description field is required',
                              'expens_name.*.required' => 'Expense name field is required',
                              'expens_amount.*.required_with' => 'Expense amount field is required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->messages();
                         return response()->json(['status' => 0, 'message' => $messages]);
                    }
               }
               if (($data->myvisitData->visit_type ?? 0) == 1)
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'my_visit_id' => 'required',
                              'commuting_type' => 'required',
                              'kilomiter' => 'required',
                              'description' => 'required',
                         ],
                         [
                              'commuting_type.required' => 'Vehicle type field is required',
                              'description.required' => 'Description field is required',
                              'kilomiter.required' => 'Kilomiter field is required',
                              'misc.required' => 'Misc field is required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->messages();
                         return response()->json(['status' => 0, 'message' => $messages]);
                    }
               }

               if (($data->myvisitData->visit_type ?? 0) == 2)
               {
                    foreach ($request->expens_name as $key => $expense_id)
                    {
                         $grade_tier_data = GradeTierData::with('expenseData')->find($expense_id);

                         if ($grade_tier_data != null)
                         {
                              if ($grade_tier_data->is_actual == 0 && $request->expens_amount[$key] > $grade_tier_data->amount)
                              {

                                   // dd("in", [
                                   //      'expense name' => $grade_tier_data->expenseData->name,
                                   //      '$request->expens_amount[$key]' => $request->expens_amount[$key],
                                   //      '$grade_tier_data->amount' => $grade_tier_data->amount
                                   // ]);

                                   return response()->json(['status' => 0, 'message' => 'You have exceeded the expense amount given by company.']);
                                   // return response()->json(['status' => 0, 'message' => ['expens_amount.' . $key => ['You have exceeded the amount given by company']]]);
                              }

                              $is_actual[] = $grade_tier_data->is_actual ?? "";
                              array_push($arr, $grade_tier_data->expenseData->name);
                              $expense_ids[] = $expense_id;
                         }
                    }
               }
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'claim_name'    => 'required',
                         'description'       => 'required',
                    ],
                    [
                         'claim_name.required'  => 'Claim Name field is required',
                         'description.required'  => 'Description field is required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->messages();
                    return response()->json(['status' => 0, 'message' => $messages]);
               }
          }

          try
          {
               DB::beginTransaction();

               $en_uploaded = $data->fileupload ?? "";

               if ($request->hasFile('uploadfile'))
               {
                    $image = $request->file('uploadfile');
                    $en_uploaded = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/fileupload');
                    $image->move($destinationPath, $en_uploaded);
               }

               $data->commuting_type = $request->commuting_type;
               $data->kilomiter = $request->kilomiter;
               $data->description = $request->description;
               // $data->fix_amount = $request->fix_amount;
               $data->fix_amount = $request->total_amount;
               $data->total_amount = $request->total_amount;
               $data->misc = $request->misc;


               $expencesNames = [];
               $expencesAmounts = [];
               $expencesIds = [];

               if (!empty($request->expens_name))
               {
                    for ($i = 0; $i <= count($request->expens_name) - 1; $i++)
                    {
                         $grade_tier_data = GradeTierData::with('expenseData')->find(($request->expens_name[$i] ?? 0));
                         if ($grade_tier_data != null)
                         {
                              // $expencesData[] = [$request->expens_name[$i] => $request->expens_amount[$i]];
                              // $expencesData[] = [$grade_tier_data->expenseData->name => $request->expens_amount[$i]];

                              $expencesNames[] = ($grade_tier_data->expenseData->name ?? '');
                              $expencesAmounts[] = ($request->expens_amount[$i] ?? 0);
                              $expencesIds[] = ($request->expens_name[$i] ?? 0);
                         }
                    }
               }

               $data->expens_name = json_encode(array_values($expencesNames));
               $data->expens_amount = json_encode(array_values($expencesAmounts));
               $data->expense_ids = json_encode($expencesIds);

               $data->hidden_expense_is_actual = json_encode($is_actual);

               $data->fileupload = $en_uploaded;
               $data->status = 'Pending';

               // $data->visit_type = $request->visit_type ?? 0;
               $data->name = $request->claim_name ?? "";

               $data->update();

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
                                   $notification = new NotificationModel();
                                   $notification->user_id = $row->id;
                                   $notification->employee_id = $employee->id;
                                   $notification->title = $employee->name . " has updated the request for claim";
                                   $notification->details = $employee->name . " has updated the request for claim";
                                   $notification->status = 0;
                                   $notification->date = Carbon::now();
                                   $notification->created_by = $employee->created_by;
                                   $notification->redirect = 'my_claim';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               // return response()->json(['status' => 1, 'message' => __('Claim successfully updated.')]);
               $response = [
                    'status' => 1,
                    'message' => __('Claim updated successfully.'),
               ];
               DB::commit();
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_claim(request $request)
     {
          try
          {
               // visit_type
               $keyword = $request->input('search_key');
               $employee = Employee::where('user_id', Auth::id())->first();

               $data = MyClaimModel::select("*", "fileupload as image_link", 'created_at as created_at_formatted')->where('userid', $employee->id)->where('created_by', '=', Auth::user()->creatorId())
                    ->where(function ($query) use ($keyword)
                    {
                         $query->where('commuting_type', 'like', '%' . $keyword . '%')
                              ->orWhere('status', 'like', '%' . $keyword . '%')
                              ->orWhere('description', 'like', '%' . $keyword . '%');
                    })->orderBy('id', 'desc');

               $response = paginate($data, ($request->page ?? null));

               $Gradedata = GradeType::where('assign_grade_id', $employee->grade_type_id)->first();

               $in_city_two_wheeler = 0;
               $in_city_four_wheeler = 0;

               if ($Gradedata != null)
               {
                    $in_city_two_wheeler = $Gradedata->in_city_two_wheeler;
                    $in_city_four_wheeler = $Gradedata->in_city_four_wheeler;
               }

               $statisticsData = MyClaimModel::select("*", "fileupload as image_link", 'created_at as created_at_formatted')->where('userid', $employee->id)->where('created_by', '=', Auth::user()->creatorId())
                    ->where(function ($query) use ($keyword)
                    {
                         $query->where('commuting_type', 'like', '%' . $keyword . '%')
                              ->orWhere('status', 'like', '%' . $keyword . '%')
                              ->orWhere('description', 'like', '%' . $keyword . '%');
                    })->orderBy('id', 'desc')
                    ->get();

               $response["statistics"] = [
                    "pending" => $statisticsData->where('status', "Pending")->count() ?? 0,
                    "approved" => $statisticsData->where('status', "Approved")->count() ?? 0,
                    "rejected" => $statisticsData->where('status', "Rejected")->count() ?? 0,
               ];

               if (!empty($response['data']))
               {
                    foreach ($response['data'] as $row)
                    {
                         $row->commuting_type_name = ($row->commuting_type == 1) ? 'Two-wheeler' : 'Four-wheeler';
                         $row->claim_type = $row->visit_type;

                         $data = MyVisitModel::find($row->my_visit_id);

                         if ($row->visit_type == 0)
                         {
                              if (!empty($data))
                              {

                                   $row->visit_type_name = ($data->visit_type == 1) ? 'Local visit' : 'Out of City visit';
                                   $row->claim_visit_type = (int)$data->visit_type; // 1 = Local Visit, 2 = Out Of City Visit, 3 = other visit | if in city visit dont show expense , if out city visit show expense
                                   $row->visit_date = Auth::user()->dateFormat($data->form_date);
                                   $row->visit_name = $data->visit_name;
                                   $row->name = $row->visit_name;

                                   $tierdata = CityTier::find($data->tier_id);
                                   if (!empty($tierdata))
                                   {
                                        $row->tierdata_name = $tierdata->name;
                                   }
                                   else
                                   {
                                        $row->tierdata_name = null;
                                   }
                                   $citydata = DB::table('city')->where('id', $data->city_id)->first();
                                   if (!empty($citydata))
                                   {
                                        $row->city_name = $citydata->name;
                                   }
                                   else
                                   {
                                        $row->city_name = null;
                                   }
                              }
                         }
                         else
                         {
                              $row->visit_date = Auth::user()->dateFormat($row->created_at);
                              $row->visit_name = $row->name;
                              $row->claim_visit_type = 3;
                              $row->visit_type_name = "Other Claim";
                         }

                         if (!empty($row->status_changed_by))
                         {
                              $staff = Employee::where('user_id', $row->status_changed_by)->first();
                              $row->status_changed_by_name = ($staff->name ?? "");
                         }
                         else
                         {
                              $row->status_changed_by_name = "";
                         }

                         // dd($row->expens_name, $row->expens_amount);
                         $row->expens_name = (json_decode($row->expens_name ?? "") ?? []);


                         $expense_amount_data = [];
                         if (!empty($row->expens_amount) && $row->expens_amount != null)
                         {
                              $expense_amount_data = json_decode($row->expens_amount ?? "") ?? [];

                              if (!is_array($expense_amount_data))
                              {
                                   // dd("Not Array", $expense_amount_data);
                                   $expense_amount_data = array_values(json_decode(json_encode($expense_amount_data), true));
                              }
                         }

                         $row->expens_amount = $expense_amount_data;
                         if ($row->id == 253)
                         {
                              // dd($expense_amount_data, is_array($expense_amount_data), (array)$expense_amount_data);
                         }
                         // $row->expens_amount = (json_decode($row->expens_amount ?? "") ?? []);
                         // $row->expens_amount = array_values(json_decode($row->expens_amount ?? "", true) ?? []);

                         $row->hidden_expense_is_actual = (json_decode($row->hidden_expense_is_actual ?? "") ?? []);

                         $row->in_city_two_wheeler = $in_city_two_wheeler;
                         $row->in_city_four_wheeler = $in_city_four_wheeler;

                         try
                         {
                              if (is_numeric($row->total_amount))
                              {
                                   $row->display_amount = Auth::user()->priceFormat($row->total_amount);
                              }
                              else
                              {
                                   $row->display_amount = Auth::user()->priceFormat(0);
                              }
                         }
                         catch (Exception | Throwable $e)
                         {
                         }
                    }
               }
               else
               {
                    $response = ["status" => 0, "message" => "no data found"];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function delete_claim(request $request)
     {
          try
          {
               DB::beginTransaction();
               $data = MyClaimModel::find($request->cliam_id);
               $data->delete();
               $response = [
                    'status' => 1,
                    'message' => __('Claim Deleted Successfully.')
               ];
               DB::commit();
          }
          catch (Exception $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_expense(request $request)
     {
          try
          {
               $employeeData = Employee::where('user_id', Auth::id())->first();

               $myvisit = MyVisitModel::find($request->visit_id);
               if ($myvisit != null)
               {
                    $grade_id = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->first();

                    $data = [];

                    if (!empty($myvisit->tier_id) && !is_null($myvisit->tier_id) && $myvisit->tier_id != 'null' && $myvisit->tier_id != "undefined" && $myvisit->tier_id != 0)
                    {
                         if ($grade_id != null)
                         {
                              $data = GradeTierData::with('expenseData')->where('tier_id', $myvisit->tier_id)->where('grade_id', $grade_id->id);
                         }
                    }
                    else
                    {
                         $data = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->first();

                         $data1 = [];
                         if ($data != null)
                         {
                              $data1[] = [
                                   'id' => 1,
                                   'name' => 'Two Wheeler',
                                   'in_city_two_wheeler' => $data->in_city_two_wheeler
                              ];
                              $data1[] = [
                                   'id' => 2,
                                   'name' => 'Four Wheeler',
                                   'in_city_two_wheeler' => $data->in_city_four_wheeler
                              ];
                         }

                         $response = [
                              'status' => 1,
                              'message' => 'success',
                              'data' => $data1
                         ];
                         return ApiResponse($response);
                    }
                    $response = paginate($data, ($request->page ?? null));
               }
               else
               {
                    $response = ["status" => 0, "message" => __("No data found.")];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }
}
