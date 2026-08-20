<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Warning;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Throwable;

class WarningController extends Controller
{
     public function get_warning(request $request)
     {
          try
          {
               $emp = Employee::where('user_id', '=', \Auth::user()->id)->first();
               $data = Warning::join('employees', 'employees.id', 'warnings.warning_to')
                    ->where('warning_to', '=', $emp->id)
                    ->select('warnings.*', 'employees.name as warning_to_name')->get();

               foreach ($data as $row)
               {
                    $row->warning_by_name = $emp->name;
               }
               if (count($data) > 0)
               {
                    $response = [
                         'status' => 1,
                         'message' => 'success',
                         'data' => $data,
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data not found',
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
