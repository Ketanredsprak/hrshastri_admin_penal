<?php

namespace App\Http\Controllers\API;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeeController extends Controller
{
     public function get_employee(request $request)
     {
          $customer_data =  User::with('employee')->find(Auth::id());
          $response = [
               'status' => 1,
               'message' => __("Employee Details"),
               'data' => $customer_data,
          ];
          return ApiResponse($response);
     }
}
