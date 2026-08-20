<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GradeType;
use App\Models\CommuteType;
use App\Models\User;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use App\Models\CategoriesType;
use App\Models\CityTier;
use App\Models\VisitType;
use App\Models\GradeTierData;


class GradeTypeController extends Controller
{
     public function __construct()
     {
          $this->middleware(
               [
                    'auth',
                    'XSS',
               ]
          );
     }
     public function index()
     {
          $gradeTypes = GradeType::with('role', 'visitTypeData')->where('created_by', '=', \Auth::user()->creatorId())->get();
          return view('gradeType.index')->with('gradeTypes', $gradeTypes);
     }

     public function create()
     {
          $roles = Role::where('created_by', '=', \Auth::user()->creatorId())->get();
          $commuteType = CommuteType::where('created_by', '=', \Auth::user()->creatorId())->get();
          $gradetypes = VisitType::where('created_by', '=', \Auth::user()->creatorId())->get();
          $expenses   = CategoriesType::where('created_by', '=', \Auth::user()->creatorId())->get();
          $cityTierData = CityTier::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'ASC')->get();
          $citytierCount = CityTier::where('created_by', '=', \Auth::user()->creatorId())->count();
          return view('gradeType.create', compact('citytierCount', 'gradetypes', 'roles', 'commuteType', 'cityTierData', 'expenses'));
     }

     public function store(Request $request)
     {


          $validator = \Validator::make(
               $request->all(),
               [
                    'role_id' => 'required',
                    'assign_grade_id'   => 'required',
                    'in_city_two_wheeler'   => 'required',
                    'in_city_four_wheeler'   => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               return response()->json(['status' => 0, 'message' => $messages]);
          }
          $data = new GradeType();
          $data->role_id  = $request->role_id;
          $data->status   = 1;
          $data->in_city_two_wheeler        = $request->in_city_two_wheeler;
          $data->in_city_four_wheeler        = $request->in_city_four_wheeler;
          $data->assign_grade_id   = $request->assign_grade_id;
          $data->created_by     = \Auth::user()->creatorId();
          $data->save();

          if (!empty($request->tierData))
          {
               foreach ($request->tierData as $key => $rowData)
               {

                    foreach ($rowData as $key => $row)
                    {
                         if (!empty($row['select_expensive']))
                         {
                              $datatier  = new GradeTierData();
                              $datatier->grade_id   = $data->id;
                              $datatier->tier_id    = $key;
                              $datatier->expense_id = $row['select_expensive'];
                              $datatier->amount     = ($row['amount_exp'] != null) ? $row['amount_exp'] : 0;
                              $datatier->is_actual  = (!empty($row['is_actual'])) ? $row['is_actual'] : 0;
                              $datatier->created_by = \Auth::user()->creatorId();
                              $datatier->save();
                         }
                    }
               }
          }
          return response()->json(['status' => 1, 'message' => __('Grade Type successfully created!')]);
     }

     public function show($id)
     {
     }

     public function edit($id)
     {

          $roles = Role::with('roleData')->where('created_by', '=', \Auth::user()->creatorId())->get();
          $gradetypes = VisitType::where('created_by', '=', \Auth::user()->creatorId())->get();
          $data = GradeType::find($id);

          $cityTierData = CityTier::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'ASC')->get();
          $citytierCount = CityTier::where('created_by', '=', \Auth::user()->creatorId())->count();

          $expenses   = CategoriesType::where('created_by', '=', \Auth::user()->creatorId())->get();
          $datatier = GradeTierData::where('grade_id', $id)->get();

          $datatierCount = GradeTierData::where('grade_id', $id)->count();


          return view('gradeType.edit', compact('datatierCount', 'data', 'roles', 'gradetypes', 'datatier', 'cityTierData', 'citytierCount', 'expenses'));
     }

     // public function update(Request $request, $id)
     // {
     //     $validator = \Validator::make(
     //         $request->all(), [
     //                 'role_id' => 'required',
     //                 'assign_grade_id'   => 'required',
     //                 'in_city_two_wheeler'   => 'required',
     //                 'in_city_four_wheeler'   => 'required',
     //             ]
     //         );
     //         if($validator->fails()){
     //             $messages = $validator->messages();
     //             return response()->json(['status' => 0,'message' => $messages]);
     //         }

     //         $data = GradeType::find($id);
     //         $data->role_id           = $request->role_id;
     //         $data->in_city_two_wheeler       = $request->in_city_two_wheeler;
     //         $data->in_city_four_wheeler      = $request->in_city_four_wheeler;
     //         $data->assign_grade_id   = $request->assign_grade_id;
     //         $data->update();

     //         foreach($request->tierData as $key=>$row){
     //             $datatier  = GradeTierData::where('grade_id',$id)->where('tier_id',$key)->delete();

     //             foreach($row['select_expensive'] as $p=>$now){
     //                 $datatier  = new GradeTierData();
     //                 $datatier->grade_id   = $id;
     //                 $datatier->tier_id    = $key;
     //                 $datatier->expense_id = $row['select_expensive'][$p];
     //                 $datatier->amount     = $row['amount_exp'][$p];
     //                 $datatier->is_actual  = (empty($row['is_actual'][$p])) ? 0 : 1;
     //                 $datatier->created_by = \Auth::user()->creatorId();
     //                 $datatier->save();
     //             }
     //         }
     //         return response()->json(['status' => 1,'message' => __('Grade Type successfully updated!')]);
     //     }
     public function update(Request $request, $id)
     {
          $validator = \Validator::make(
               $request->all(),
               [
                    'role_id' => 'required',
                    'assign_grade_id'   => 'required',
                    'in_city_two_wheeler'   => 'required',
                    'in_city_four_wheeler'   => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               return response()->json(['status' => 0, 'message' => $messages]);
          }

          $data = GradeType::find($id);
          $data->role_id           = $request->role_id;
          $data->in_city_two_wheeler       = $request->in_city_two_wheeler;
          $data->in_city_four_wheeler      = $request->in_city_four_wheeler;
          $data->assign_grade_id   = $request->assign_grade_id;
          $data->update();

          $i = 0;

          foreach (($request->tierData ?? []) as $key => $row)
          {
               foreach ($row as $p => $now)
               {
                    GradeTierData::updateOrCreate([
                         'grade_id' => $id,
                         'tier_id' => $p,
                         'expense_id' => $now['select_expensive'],
                         'created_by' => \Auth::user()->creatorId()
                    ], [
                         'grade_id' => $id,
                         'tier_id' => $p,
                         'expense_id' => $now['select_expensive'],
                         'amount' => ($now['amount_exp'] != null) ? $now['amount_exp'] : 0,
                         'is_actual' => $now['is_actual'] ?? 0,
                         'created_by' => \Auth::user()->creatorId(),
                    ]);
               }
               $i++;
          }
          return response()->json(['status' => 1, 'message' => __('Grade Type successfully updated!')]);
     }
     public function destroy($id)
     {
          $data = GradeType::find($id);
          $data->delete();
          return redirect()->route('grade_type.index')->with('success', __('Grade Type successfully deleted!'));
     }

     public function delete_tierData(request $request)
     {
          $data = GradeTierData::find($request->id);
          $data->delete();
          return response()->json(['status' => 1, 'message' => __('Data successfully delete!')]);
     }
}
