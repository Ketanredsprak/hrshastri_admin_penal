<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\UserAuthController@login');

Route::post('forgot-password', 'API\UserAuthController@forgotPassword')->name('forget-password-api');
Route::get('test-notification', 'API\UserAuthController@testNotification');

//Route::group(['middleware' => 'auth:api'], function ()
// Route::group(['middleware' =>  ['auth:api', 'throttle:global']], function ()
Route::middleware(['auth:api', 'smartThrottle'])->group(function () 
{
     Route::post('dashboard', 'API\UserAuthController@dashboard');

     Route::post('clock_in', 'API\UserAuthController@clock_in');
     Route::post('clock_out', 'API\UserAuthController@clock_out');
     Route::post('sync-clock-in-clock-out', 'API\UserAuthController@syncClockInClockOut');
     Route::post('sync-clock-in-clock-out-status', 'API\UserAuthController@syncClockInClockOutStatus');

     Route::post('get_attendance', 'API\UserAuthController@get_attendance');
     Route::post('get_attendance_list', 'API\UserAuthController@get_attendance_list');

     //profile
     Route::post('get_profile', 'API\UserAuthController@get_profile');
     Route::post('set_logout', 'API\UserAuthController@set_logout');
     Route::post('get_experience', 'API\UserAuthController@get_experience');
     Route::post('get_education', 'API\UserAuthController@get_education');
     Route::post('get_dependent', 'API\UserAuthController@get_dependent');
     Route::post('get_documents', 'API\UserAuthController@get_documents');
     Route::post('updatePassword', 'API\UserAuthController@updatePassword');

     //Employee
     Route::post('get_employee', 'API\EmployeeeController@get_employee');
     Route::post('get_timesheet', 'API\TimesheetController@get_timesheet');
     Route::post('create_timesheet', 'API\TimesheetController@create_timesheet');
     Route::post('update_timesheet', 'API\TimesheetController@update_timesheet');
     Route::post('delete_timesheet', 'API\TimesheetController@delete_timesheet');

     //Employee Modules
     Route::post('get_transfer', 'API\EmployeeModulesController@get_transfer');
     Route::post('get_promotion', 'API\EmployeeModulesController@get_promotion');
     Route::post('get_announcement', 'API\EmployeeModulesController@get_announcement');
     Route::post('get_holiday', 'API\EmployeeModulesController@get_holiday');
     Route::post('get_document', 'API\EmployeeModulesController@get_document');
     Route::post('get_termination', 'API\EmployeeModulesController@get_termination');
     Route::post('get_company_policy', 'API\EmployeeModulesController@get_company_policy');
     Route::post('get_events', 'API\EmployeeModulesController@get_events');
     Route::post('get_upcomming_birthday', 'API\EmployeeModulesController@get_upcomming_birthday');

     //get_leaves
     Route::post('get_leaves', 'API\LeaveController@get_leaves');
     Route::post('create_leave', 'API\LeaveController@create_leave');
     Route::post('update_leave', 'API\LeaveController@update_leave');
     Route::post('delete_leave', 'API\LeaveController@delete_leave');
     Route::post('get_leave_type', 'API\LeaveController@get_leave_type');
     Route::post('my_leave_balance', 'API\LeaveController@my_leave_balance');

     //get_complaint
     Route::post('get_complaint', 'API\ComplaintController@get_complaint');
     Route::post('create_complaint', 'API\ComplaintController@create_complaint');
     Route::post('update_complaint', 'API\ComplaintController@update_complaint');
     Route::post('delete_complaint', 'API\ComplaintController@delete_complaint');
     Route::post('get_complaint_against', 'API\ComplaintController@get_complaint_against');

     //get_warning
     Route::post('get_warning', 'API\WarningController@get_warning');

     //gate_pass
     Route::post('get_gate_pass', 'API\GatePassController@get_gate_pass');
     Route::post('create_gate_pass', 'API\GatePassController@create_gate_pass');
     Route::post('update_gate_pass', 'API\GatePassController@update_gate_pass');
     Route::post('delete_gate_pass', 'API\GatePassController@delete_gate_pass');

     //get_apply_wfh
     Route::post('get_apply_wfh', 'API\ApplyWFHController@get_apply_wfh');
     Route::post('create_apply_wfh', 'API\ApplyWFHController@create_apply_wfh');
     Route::post('update_apply_wfh', 'API\ApplyWFHController@update_apply_wfh');
     Route::post('delete_apply_wfh', 'API\ApplyWFHController@delete_apply_wfh');

     //EarlyLeaveController
     Route::post('get_early_Leave', 'API\EarlyLeaveController@get_early_Leave');
     Route::post('create_early_Leave', 'API\EarlyLeaveController@create_early_Leave');
     Route::post('delete_early_Leave', 'API\EarlyLeaveController@delete_early_Leave');
     Route::post('update_early_Leave', 'API\EarlyLeaveController@update_early_Leave');

     //MySlip
     Route::post('my_slip', 'API\MySlipController@my_slip');

     //get_careers
     Route::post('get_careers', 'API\CareerController@get_careers');
     Route::post('get_references', 'API\CareerController@get_references');
     Route::post('create_references', 'API\CareerController@create_references');

     //gt_tickets
     Route::post('get_tickets', 'API\TicketsController@get_tickets');
     Route::post('create_ticket', 'API\TicketsController@create_ticket');
     Route::post('update_ticket', 'API\TicketsController@update_ticket');
     Route::post('delete_ticket', 'API\TicketsController@delete_ticket');
     Route::post('get_ticket_reply', 'API\TicketsController@get_ticket_reply');
     Route::post('update_ticket_status', 'API\TicketsController@update_ticket_status');
     Route::post('create_ticket_reply', 'API\TicketsController@create_ticket_reply');

     //visit type
     Route::post('get_visit', 'API\VisitController@get_visit');
     Route::post('create_visit', 'API\VisitController@create_visit');
     Route::post('update_visit', 'API\VisitController@update_visit');
     Route::post('delete_visit', 'API\VisitController@delete_visit');
     Route::post('get_tier', 'API\VisitController@get_tier');
     Route::post('get_visit_approved', 'API\VisitController@get_visit_approved');
     Route::post('get_tier_city', 'API\VisitController@get_tier_city');

     //claim
     Route::post('get_claim', 'API\ClaimController@get_claim');
     Route::post('create_claim', 'API\ClaimController@create_claim');
     Route::post('claim_edit', 'API\ClaimController@claim_edit');
     Route::post('get_expense', 'API\ClaimController@get_expense');
     Route::post('delete_claim', 'API\ClaimController@delete_claim');

     //mobile_menu
     Route::post('get_mobile_menu', 'API\MobileMenuController@get_mobile_menu');
     Route::post('get_mobile_menu_for_geofancing', 'API\MobileMenuController@GetMobileMenuForGeofancing');

     // Resignation
     Route::post('create_resignation', 'API\MobileMenuController@create_resignation');
     Route::post('get_resignation', 'API\MobileMenuController@get_resignation');
     Route::post('update_resignation', 'API\MobileMenuController@update_resignation');
     Route::post('delete_resignation', 'API\MobileMenuController@delete_resignation');


     // Notification List
     Route::post('notification-list', 'API\UserAuthController@getNotificationList');
     Route::post('notification-clear', 'API\UserAuthController@clearNotificationList');


     // Update Employee Profile
     Route::post('update-employee-profile', 'API\UserAuthController@updateEmployeeProfile');
     // Update Employee Profile

     // Employee Calender Data
     Route::post('employee-calendar-data', 'API\UserAuthController@employeeCalendarData');
     // Employee Calender Data

     // clock In Out Employees List
     Route::post('clock-in-out-employees-list', 'API\UserAuthController@clockInOutEmployeesList');
     // clock In Out Employees List

     // clock In Out Branch List
     Route::post('clock-in-out-branch-list', 'API\UserAuthController@clockInOutBranchList');
     // clock In Out Branch List
});
