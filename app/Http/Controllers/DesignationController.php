<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use DataTables;

class DesignationController extends Controller
{
     public function index(Request $request)
     {
          $designations = Designation::where('created_by', Auth::user()->creatorId())->get();

          if (\Auth::user()->can('Manage Designation'))
          {
               $designations = Designation::where('created_by', Auth::user()->creatorId());
               if ($request->ajax())
               {
                    return Datatables::of($designations)
                         ->addIndexColumn()
                         ->addColumn('branch_name', function ($row)
                         {
                              return $row->branch ? $row->branch->name : '';
                         })
                         ->addColumn('department_name', function ($row)
                         {
                              $department = Department::where('id', $row->department_id)->first();
                              return $department ? $department->name : '';
                         })
                         ->addColumn('description', function ($row)
                         {
                              if ($row->description)
                              {
                                   return Str::limit($row->description, 50, '...');
                              }
                              return '';
                         })
                         ->addColumn('status_badge', function ($row)
                         {
                              if ($row->status == 'Active')
                              {
                                   return '<span class="badge bg-success">' . __('Active') . '</span>';
                              }
                              else
                              {
                                   return '<span class="badge bg-danger">' . __('Inactive') . '</span>';
                              }
                         })
                         ->addColumn('action', function ($row)
                         {
                              $btn = '';
                              if (Auth::user()->can('Edit Designation'))
                              {
                                   $btn .= '<a href="#" class="btn btn-sm btn-info" data-url="' . route('designation.edit', $row->id) . '" data-ajax-popup="true" data-title="' . __('Edit Designation') . '"><i class="ti ti-pencil text-white"></i></a> ';
                              }
                              if (Auth::user()->can('Delete Designation'))
                              {
                                   $btn .= '<form method="POST" action="' . route('designation.destroy', $row->id) . '" style="display:inline;">'
                                        . csrf_field()
                                        . method_field('DELETE')
                                        . '<button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-trash text-white"></i></button>'
                                        . '</form>';
                              }
                              return $btn;
                         })
                         ->rawColumns(['id', 'branch_name', 'department_name', 'description', 'status_badge', 'action'])
                         ->make(true);
               }
               else
               {
                    $designations = $designations->get();
                    return view('designation.index', compact('designations'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Designation'))
          {
               $departments = Department::where('status', 'Active')->where('created_by', '=', \Auth::user()->creatorId())->get();
               $departments = $departments->pluck('name', 'id');

               $branch = Branch::where('created_by', '=', Auth::user()->creatorId())->get();

               return view('designation.create', compact('departments', 'branch'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {

          if (\Auth::user()->can('Create Designation'))
          {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         'department_id' => 'required',
                         'name' => 'required',
                          'document' => 'nullable|file|max:2048',
                    ],
                    [
                         'document.max' => __('The document size must not exceed 2 MB.'),
                         'document.file' => __('Please upload a valid document file.'),
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }
                    if ($request->hasFile('document')) {
    $ext = strtolower($request->file('document')->getClientOriginalExtension());

    if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
        return back()->with('error', __('The document must be a PDF, DOC, or DOCX file.'));
    }
}
               $designation                = new Designation();
               $designation->department_id = $request->department_id;
               $designation->branch_id = $request->branch_id;
               $designation->name          = $request->name;
               $designation->description   = $request->description;
               $designation->status       = $request->status;
               $designation->created_by    = \Auth::user()->creatorId();

               if ($request->hasFile('document')) {
                    $file = $request->file('document');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('public/designation_documents'), $filename);

                    $designation->document = $filename;
               }

               $designation->save();

               return redirect()->route('designation.index')->with('success', __('Designation  successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Designation $designation)
     {
          return redirect()->route('designation.index');
     }

     public function edit(Designation $designation)
     {

          if (\Auth::user()->can('Edit Designation'))
          {
               if ($designation->created_by == \Auth::user()->creatorId())
               {

                    // $departments = Department::where('id', $designation->department_id)->first();
                    $departments = Department::where('status', 'Active')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $departments = $departments->pluck('name', 'id');

                    $branch = Branch::where('created_by', '=', Auth::user()->creatorId())->get();

                    return view('designation.edit', compact('designation', 'departments', 'branch'));
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

     public function update(Request $request, Designation $designation)
     {
          if (\Auth::user()->can('Edit Designation'))
          {
               if ($designation->created_by == \Auth::user()->creatorId())
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'department_id' => 'required',
                              'name' => 'required',
                              'document' => 'nullable|file|max:2048',
                         ],
                         [
                              'document.max' => __('The document size must not exceed 2 MB.'),
                              'document.file' => __('Please upload a valid document file.'),
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }

                    if ($request->hasFile('document')) {
                         $ext = strtolower($request->file('document')->getClientOriginalExtension());

                         if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                              return back()->with('error', __('The document must be a PDF, DOC, or DOCX file.'));
                         }
                    }
                    $designation->name          = $request->name;
                    $designation->department_id = $request->department_id;
                    $designation->branch_id = $request->branch_id;
                    $designation->description   = $request->description;
                    //  $designation->status       = $request->status;

                    if ($request->hasFile('document')) {

                    // Optional: delete old document
                    if (!empty($designation->document) && file_exists(public_path('public/designation_documents/'.$designation->document))) {
                         unlink(public_path('public/designation_documents/'.$designation->document));
                    }

                    $file = $request->file('document');
                    $filename = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('public/designation_documents'), $filename);

                    $designation->document = $filename;
                    }
                    $designation->save();

                    return redirect()->route('designation.index')->with('success', __('Designation  successfully updated.'));
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

     public function destroy(Designation $designation)
     {
          if (\Auth::user()->can('Delete Designation'))
          {
               if ($designation->created_by == \Auth::user()->creatorId())
               {
                    $designation->delete();

                    return redirect()->route('designation.index')->with('success', __('Designation successfully deleted.'));
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

     public function designation_active($id)
     {
          $data = Designation::find($id);
          $data->status = 'Active';
          $data->save();
          return redirect()->route('designation.index')->with('success', __('Designation status active successfully.'));
     }

     public function designation_in_active($id)
     {

          $data = Designation::find($id);
          $data->status = 'In-Active';
          $data->save();

          return redirect()->route('designation.index')->with('success', __('Designation status in-active successfully.'));
     }
}
