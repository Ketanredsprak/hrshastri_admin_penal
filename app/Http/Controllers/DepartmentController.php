<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Form;
use Illuminate\Support\Facades\Crypt;

class DepartmentController extends Controller
{
     /* public function index()
     {

        if(Auth::user()->can('Manage Department'))
        {
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();
            return view('department.index', compact('departments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    } */
     public function index(Request $request)
     {

          if (Auth::user()->can('Manage Department'))
          {
               $departments = Department::with('branch')->where('created_by', '=', Auth::user()->creatorId());
               if ($request->ajax())
               {
                    return Datatables::of($departments)
                         ->addIndexColumn()
                         ->addColumn('branch_name', function ($row)
                         {
                              return $row->branch ? $row->branch->name : '';
                         })
                         ->addColumn('status_badge', function ($row)
                         {
                              if ($row->status == 'Active')
                              {
                                   return '<span class="badge bg-success">Active</span>';
                              }
                              else
                              {
                                   return '<span class="badge bg-danger">Inactive</span>';
                              }
                         })
                         ->addColumn('action', function ($row)
                         {
                              $btn = '<span>';
                              if (Auth::user()->can('Edit Department'))
                              {
                                   $btn .= '<div class="action-btn bg-info ms-2">'
                                        . '<a href="#" class="btn btn-sm align-items-center" '
                                        . 'data-url="' . url('department/' . $row->id . '/edit') . '" '
                                        . 'data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" '
                                        . 'title="' . __('Edit') . '" data-title="' . __('Edit Department') . '">'
                                        . '<i class="ti ti-pencil text-white"></i>'
                                        . '</a>'
                                        . '</div>';
                              }
                              if (Auth::user()->can('Delete Department'))
                              {
                                   $btn .= '<div class="action-btn bg-danger ms-2">'
                                        . '<form method="POST" action="' . route('department.destroy', $row->id) . '" style="display:inline;">'
                                        . csrf_field()
                                        . method_field('DELETE')
                                        . '<button type="submit" class="btn btn-sm align-items-center bs-pass-para" '
                                        . 'title="' . __('Delete') . '">'
                                        . '<i class="ti ti-trash text-white"></i>'
                                        . '</button>'
                                        . '</form>'
                                        . '</div>';
                              }
                              $btn .= '</span>';

                              return $btn;
                         })
                         ->rawColumns(['id', 'status_badge', 'action'])
                         ->make(true);
               }
               else
               {
                    $departments = $departments->get();
                    return view('department.index', compact('departments'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Department'))
          {
               $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

               return view('department.create', compact('branch'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {

          if (\Auth::user()->can('Create Department'))
          {

               $validator = \Validator::make(
                    $request->all(),
                    [
                         'branch_id' => 'required',
                         'name' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $department             = new Department();
               $department->branch_id  = $request->branch_id;
               $department->name       = $request->name;
               $department->status       = $request->status;
               $department->created_by = \Auth::user()->creatorId();
               $department->save();

               return redirect()->route('department.index')->with('success', __('Department  successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Department $department)
     {
          return redirect()->route('department.index');
     }

     public function edit(Department $department)
     {
          if (\Auth::user()->can('Edit Department'))
          {
               if ($department->created_by == \Auth::user()->creatorId())
               {
                    $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                    return view('department.edit', compact('department', 'branch'));
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

     public function update(Request $request, Department $department)
     {
          if (\Auth::user()->can('Edit Department'))
          {
               if ($department->created_by == \Auth::user()->creatorId())
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'branch_id' => 'required',
                              'name' => 'required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }

                    $department->branch_id = $request->branch_id;
                    $department->name      = $request->name;
                    $department->status       = $request->status;
                    $department->save();

                    return redirect()->route('department.index')->with('success', __('Department successfully updated.'));
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

     public function destroy(Department $department)
     {
          if (\Auth::user()->can('Delete Department'))
          {
               if ($department->created_by == \Auth::user()->creatorId())
               {
                    $employee     = Employee::where('department_id', $department->id)->get();
                    if (count($employee) == 0)
                    {
                         Designation::where('department_id', $department->id)->delete();
                         $department->delete();
                    }
                    else
                    {
                         return redirect()->route('department.index')->with('error', __('This department has employees. Please remove the employee from this department.'));
                    }

                    return redirect()->route('department.index')->with('success', __('Department successfully deleted.'));
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

     public function department_active($id)
     {
          $data = Department::find($id);
          $data->status = 'Active';
          $data->save();
          return redirect()->route('department.index')->with('success', __('Department status active successfully.'));
     }

     public function department_in_active($id)
     {

          $data = Department::find($id);
          $data->status = 'In-Active';
          $data->save();

          return redirect()->route('department.index')->with('success', __('Department status in-active successfully.'));
     }
}
