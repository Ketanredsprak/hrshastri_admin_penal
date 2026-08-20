<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Branch;
use App\Models\Employee;
use Auth;
use DB;
use App\Models\JobRefence;
use Exception;
use Throwable;

class CareerController extends Controller
{
     public function get_careers(request $request)
     {
          try
          {
               $search = $request->search ?? "";
               $id = \Auth::user()->creatorId();

               $employeeData = Employee::where("user_id", \Auth::user()->id)->first();
               $employeeId = $employeeData->id ?? 0;

               $data = Job::where('created_by', $id)
                    ->when((!empty($search)), function ($query) use ($search)
                    {
                         $query
                              ->where("title", "LIKE", "%{$search}%")
                              // ->orWhere("description", "LIKE", "%{$search}%")
                              // ->orWhere("requirement", "LIKE", "%{$search}%")
                         ;
                    })
                    ->with(["JobRefence" => function ($query) use ($employeeId)
                    {
                         $query->where("created_by", $employeeId);
                    }])
                    ->orderBy('id', 'desc');

               $result = paginate($data, ($request->page ?? null));
               if (!empty($result['data']))
               {
                    foreach ($result['data'] as $row)
                    {
                         if ($row->branch != 0)
                         {
                              $branch = Branch::find($row->branch);
                              $row->branche_name = $branch->name;
                         }
                         else
                         {
                              $row->branche_name = 0;
                         }
                    }
               }
               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_references(request $request)
     {
          try
          {
               $data = Job::find($request->id);

               $employeeData = Employee::where("user_id", \Auth::user()->id)->first();
               $employeeId = $employeeData->id ?? 0;

               $data = JobRefence::where('job_id', $request->id)->select("*", "refence_file as document_link")->where("created_by", $employeeId)->orderBy('id', 'desc');

               $result = paginate($data, ($request->page ?? null));

               // if (!empty($result['data']))
               // {
               //      foreach ($result['data'] as $row)
               //      {
               //           $row->referenceDoc =  asset('referenceDoc/' . $row->refence_file);
               //      }
               // }
               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function create_references(request $request)
     {
          try
          {
               DB::beginTransaction();
               $id_photo = null;
               if ($request->hasFile('refence_doc'))
               {
                    $image = $request->file('refence_doc');
                    $id_photo = time() . $image->getClientOriginalName();
                    $destinationPath = public_path('/referenceDoc');
                    $image->move($destinationPath, $id_photo);
               }

               $employeeData = Employee::where("user_id", \Auth::user()->id)->first();
               $employeeId = $employeeData->id ?? 0;

               $data = new JobRefence();
               $data->job_id       = $request->job_id;
               $data->refence_file = $id_photo;
               $data->status       = 0;
               $data->created_by   = $employeeId;
               $data->save();

               $response = [
                    'status' => 1,
                    'message' => __('Job reference created successfully.')
               ];
               DB::commit();
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }
}
