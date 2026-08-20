<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CityModel;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\District;
use App\Models\Utility;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Exception;
use Form;
use Illuminate\Support\Facades\Crypt;

class BranchController extends Controller
{

     public function getcities($id)
     {
          $cities = CityModel::where('districtid', $id)->get();
          return $cities;
     }
     public function getdistict($id)
     {
          $getdistict = District::where('state_id', $id)->get();
          return $getdistict;
     }
     public function index(Request $request)
     {
          if (Auth::user()->can('Manage Branch'))
          {
               // $branches = Branch::with('states', 'districts')->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
               $branches = Branch::with('states', 'districts')->where('created_by', Auth::user()->creatorId())->orderBy('id', 'DESC');
               if ($request->ajax())
               {
                    return Datatables::of($branches)
                         ->addIndexColumn()
                         // if you need to add custom columns or edit what shows:
                         ->addColumn('country', function ($row)
                         {
                              // assuming country_name holds id or name
                              return is_numeric($row->country_name) ? getCountryNameById($row->country_name) : $row->country_name;
                         })
                         ->addColumn('state', function ($row)
                         {
                              return is_numeric($row->state_name) ? getStateNameById($row->state_name) : $row->state_name;
                         })
                         ->addColumn('district', function ($row)
                         {
                              return is_numeric($row->district_name) ? getDistrictNameById($row->district_name) : $row->district_name;
                         })
                         // you can add city, zip, address similarly
                         ->addColumn('action', function ($row)
                         {
                              $btn = '';
                              if (Auth::user()->can('Edit Branch'))
                              {
                                   $btn .= '<a href="#" class="btn btn-sm btn-info" data-url="' . route('branch.edit', $row->id) . '" data-ajax-popup="true" data-title="' . __('Edit Branch') . '"><i class="ti ti-pencil"></i></a> ';
                              }
                              if (Auth::user()->can('Delete Branch'))
                              {
                                   $btn .= '<form method="POST" action="' . route('branch.destroy', $row->id) . '" style="display:inline;">'
                                        . csrf_field()
                                        . method_field('DELETE')
                                        . '<button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>'
                                        . '</form>';
                              }
                              return $btn;
                         })
                         ->rawColumns(['action'])
                         ->make(true);
               }
               else
               {
                    $branches = $branches->get();
                    return view('branch.index', compact('branches'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Branch'))
          {
               $state = State::get();
               return view('branch.create', compact('state'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {
          if (Auth::user()->can('Create Branch'))
          {
               $geofencing_status = $request->has("geofencing_status") ? 1 : 0;
               $remote_geofencing_status = $request->has("remote_geofencing_status") ? 1 : 0;
               $geofencingValidations = ['name' => 'required'];
               if ($geofencing_status == 1)
               {
                    $geofencingValidations = [
                         'name' => 'required',
                         "geofencing_radius" => "required",
                         // 'geofencing_radius' => 'required|numeric|min:300',
                    ];
               }

               $validator = Validator::make(
                    $request->all(),
                    $geofencingValidations,
                    [
                         "name" => "Name  is required.",
                         "geofencing_radius" => "Geofencing Radius is required.",
                         // 'geofencing_radius.numeric' => 'Geofencing Radius must be a number.',
                         // 'geofencing_radius.min' => 'To ensure reliability and reduce false triggers, a radius of 300 meters or more is recommended by Google.',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               // $state = State::where('state_title', $request->state_name)->first();
               // $district_name = District::where('districtid', $request->district_name)->first();


               DB::beginTransaction();
               $branch = new Branch();
               $branch->name = $request->name;
               $branch->country_name = $request->country_name;
               $branch->state_name = $request->state_name;
               $branch->district_name = $request->district_name;
               $branch->city_name = $request->city_name;
               $branch->zip_code = $request->zip_code;
               $branch->address = $request->address;
               $branch->longitude = $request->longitude;
               $branch->latitude = $request->latitude;
               $branch->created_by = Auth::user()->creatorId();

               $branch->geofencing_status = $geofencing_status;
               $branch->remote_geofencing_status = $remote_geofencing_status;
               if ($geofencing_status == 1)
               {
                    $branch->geofencing_radius = $request->geofencing_radius ?? "";
               }

               $branch->save();

               if ($remote_geofencing_status == 0)
               {
                    $latitude = $request->latitude;
                    $longitude = $request->longitude;
               }
               else
               {
                    $latitude = "";
                    $longitude = "";
               }

               Employee::where("branch_id", $branch->id)->update(["geofencing_status" => $branch->geofencing_status, 'remote_geofencing_status' => $branch->remote_geofencing_status, "longitude" => $longitude, 'latitude' => $latitude]);

               DB::commit();

               return redirect()->route('branch.index')->with('success', __('Branch  successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Branch $branch)
     {
          return redirect()->route('branch.index');
     }

     public function edit(Branch $branch)
     {
          if (Auth::user()->can('Edit Branch'))
          {
               if ($branch->created_by == Auth::user()->creatorId())
               {
                    $country_id = State::where('state_title', $branch->state_name)?->first()?->country_id;
                    $state = State::get();
                    return view('branch.edit', compact('branch', 'state', 'country_id'));
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

     public function update(Request $request, Branch $branch)
     {
          if (Auth::user()->can('Edit Branch'))
          {
               if ($branch->created_by == Auth::user()->creatorId())
               {
                    $geofencing_status = $request->has("geofencing_status") ? 1 : 0;
                    $remote_geofencing_status = $request->has("remote_geofencing_status") ? 1 : 0;
                    $geofencingValidations = ['name' => 'required'];
                    if ($geofencing_status == 1)
                    {
                         $geofencingValidations = [
                              'name' => 'required',
                              "geofencing_radius" => "required",
                              // 'geofencing_radius' => 'required|numeric|min:300',
                         ];
                    }

                    $validator = Validator::make(
                         $request->all(),
                         $geofencingValidations,
                         [
                              "name" => "Name  is required.",
                              "geofencing_radius" => "Geofencing Radius is required.",
                              // 'geofencing_radius.numeric' => 'Geofencing Radius must be a number.',
                              // 'geofencing_radius.min' => 'To ensure reliability and reduce false triggers, a radius of 300 meters or more is recommended by Google.',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }

                    DB::beginTransaction();

                    // $state = State::where('state_id', $request->state_name)->first();
                    // $district_name = District::where('districtid', $request->district_name)->first();

                    $branch->name = $request->name;
                    $branch->country_name = $request->country_name;
                    $branch->state_name = $request->state_name;
                    $branch->district_name = $request->district_name;
                    $branch->city_name = $request->city_name;
                    $branch->zip_code = $request->zip_code;
                    $branch->address = $request->address;
                    $branch->longitude = $request->longitude;
                    $branch->latitude = $request->latitude;
                    $branch->geofencing_status = $geofencing_status;
                    $branch->remote_geofencing_status = $remote_geofencing_status;
                    if ($geofencing_status == 1)
                    {
                         $branch->geofencing_radius = $request->geofencing_radius ?? "";
                    }

                    $branch->save();

                    if ($remote_geofencing_status == 0)
                    {
                         $latitude = $request->latitude;
                         $longitude = $request->longitude;
                    }
                    else
                    {
                         $latitude = "";
                         $longitude = "";
                    }

                    Employee::where("branch_id", $branch->id)->update(["geofencing_status" => $branch->geofencing_status, 'remote_geofencing_status' => $branch->remote_geofencing_status, "longitude" => $longitude, 'latitude' => $latitude]);

                    DB::commit();

                    return redirect()->route('branch.index')->with('success', __('Branch successfully updated.'));
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

     public function destroy(Branch $branch)
     {
          if (Auth::user()->can('Delete Branch'))
          {
               if ($branch->created_by == Auth::user()->creatorId())
               {
                    $employee = Employee::where('branch_id', $branch->id)->get();
                    if (count($employee) == 0)
                    {
                         $department = Department::where('branch_id', $branch->id)->first();
                         if (!empty($department))
                         {
                              Designation::where('department_id', $department->branch_id)->delete();
                              $department->delete();
                         }
                         $branch->delete();
                    }
                    else
                    {
                         return redirect()->route('branch.index')->with('error', __('This branch has employees. Please remove the employee from this branch.'));
                    }

                    return redirect()->route('branch.index')->with('success', __('Branch successfully deleted.'));
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

          if ($request->branch_id == 0)
          {
               $departments = Department::get()->pluck('name', 'id')->toArray();
          }
          else
          {
               $departments = Department::where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
          }

          return response()->json($departments);
     }

     public function getemployee(Request $request)
     {
          if (in_array('0', $request->department_id))
          {
               $employees = Employee::get()->pluck('name', 'id')->toArray();
          }
          else
          {
               $employees = Employee::whereIn('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
          }

          return response()->json($employees);
     }


     public function getStateByCountryId($country_id)
     {
          $states = State::where("country_id", $country_id)->get();
          return $states;
     }

     public function getDistrictByStateName($stateName)
     {
          $stateId = State::where("state_title", $stateName)->first()->state_id ?? 0;
          $getdistict = District::where('state_id', $stateId)->get();
          return $getdistict;
     }

     public function getCityByDistrictName($districtName)
     {
          try
          {
               $districtId = District::where("district_title", $districtName)->orderBy("districtid", "DESC")->first()->districtid ?? 0;
               $cities = CityModel::where('districtid', $districtId)->get();
               return $cities;
          }
          catch (Exception $ex)
          {
          }
     }
}
