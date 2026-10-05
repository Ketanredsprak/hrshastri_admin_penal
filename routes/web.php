<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDataChangeLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\IncomeTypeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\AttendanceEmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\AccountListController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\SetSalaryController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\CrmInvoiceController;
use App\Http\Controllers\Form16Controller;
use App\Http\Controllers\TestNotificationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AwardTypeController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\TerminationTypeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\PaySlipController;
use App\Http\Controllers\ResignationController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PayeesController;
use App\Http\Controllers\PayerController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\TransferBalanceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\SubscriptionSyncController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DucumentUploadController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\AppraisalController;
use App\Http\Controllers\GoalTypeController;
use App\Http\Controllers\GoalTrackingController;
use App\Http\Controllers\GradeTypeController;
use App\Http\Controllers\CategoriesTypesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompanyPolicyController;
use App\Http\Controllers\TrainingTypeController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobStageController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\CustomQuestionController;
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\LandingPageSectionController;
use App\Http\Controllers\PaystackPaymentController;
use App\Http\Controllers\FlutterwavePaymentController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\PaytmPaymentController;
use App\Http\Controllers\MercadoPaymentController;
use App\Http\Controllers\MolliePaymentController;
use App\Http\Controllers\SkrillPaymentController;
use App\Http\Controllers\CoingatePaymentController;
use App\Http\Controllers\PaymentWallPaymentController;
use App\Http\Controllers\CompetenciesController;
use App\Http\Controllers\PerformanceTypeController;
use App\Http\Controllers\ZoomMeetingController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OtherPaymentController;
use App\Http\Controllers\SaturationDeductionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\DeductionOptionController;
use App\Http\Controllers\LoanOptionController;
use App\Http\Controllers\AllowanceOptionController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\PayslipTypeController;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\MyClaimReimbursement;
use App\Http\Controllers\FireEmailUsingCronJobController;
use App\Http\Controllers\EarlyLateCommingLeaveController;
use App\Http\Controllers\GetPassController;
use App\Http\Controllers\ApplyWFMController;
use App\Http\Controllers\VisitTypeController;
use App\Http\Controllers\CityTierController;
use App\Http\Controllers\MyPaySlipController;
use App\Http\Controllers\MyvisitController;
use App\Http\Controllers\StatutoryDeductionsController;
use App\Http\Controllers\WorkingShiftsController;
use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Mail;

// use App\Http\Controllers\PlanRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('send-mail', [MailController::class, 'index']);
Route::get('/', function ()
{
     return view('welcome');
     // return redirect('login');
});

route::get('wizard_form', 'CompanyDetailsLogin@wizard_form')->name('wizard_form');
route::get('first_form', 'CompanyDetailsLogin@first_form')->name('first_form');
route::get('himl_call_back', 'CompanyDetailsLogin@himl_call_back')->name('himl_call_back');
route::POST('finally_submit_data_company', 'CompanyDetailsLogin@finally_submit_data_company')->name('finally_submit_data_company');

Route::get('bgetcities/{id}', 'BranchController@getcities')->name('bgetcities');
Route::get('bgetdistict/{id}', 'BranchController@getdistict')->name('bgetdistict');

// api 
Route::get('payslip/pdf_api/{id}/{m}', [PaySlipController::class, 'pdf_api']);

Route::get('/optimize', function ()
{

     // $providerClass = 'Barryvdh\DomPDF\ServiceProvider';

     // Run the vendor:publish command
     // Artisan::call('vendor:publish', [
     //     '--provider' => $providerClass,
     // ]);

     Artisan::call('view:clear');
     Artisan::call('route:clear');
     Artisan::call('config:clear');
     Artisan::call('cache:clear');
     Artisan::call('optimize');
     return '<h1>Reoptimized class loader</h1>';
});
Route::get('/check-mail', function ()
{
     Mail::raw('Test email from Laravel', function ($message)
     {
          $message->to('nirav@redsparkinfo.com')
               ->from('support@hrshastri.com', 'HRMS')
               ->subject('Test Email');
     });

     return 'Mail sent';
});
Route::get('/clear-route', function ()
{

     // $providerClass = 'Barryvdh\DomPDF\ServiceProvider';

     // Run the vendor:publish command
     // Artisan::call('vendor:publish', [
     //     '--provider' => $providerClass,
     // ]);

     Artisan::call('view:clear');
     Artisan::call('route:clear');
     Artisan::call('config:clear');
     Artisan::call('cache:clear');
     return '<h1>Reoptimized class loader</h1>';
});



Route::get('employeeBday', 'FireEmailUsingCronJobController@employeeBday')->name('employeeBday');
Route::get('employeeWorkAnyversary', 'FireEmailUsingCronJobController@employeeWorkAnyversary')->name('employeeWorkAnyversary');
Route::get('add-monthly-leave', 'FireEmailUsingCronJobController@addMonthlyLeave')->name('addMonthlyLeave');
Route::get('add-monthly-leave-by-employee', 'FireEmailUsingCronJobController@addMonthlyLeaveByEmployee')->name('add-monthly-leave-by-employee');

Route::get('test_email', 'FireEmailUsingCronJobController@test_email')->name('test_email');
use App\Http\Controllers\Auth\LocalDevAuthController;

require __DIR__ . '/auth.php';

if (app()->environment('local')) {
    Route::post('/local-quick-login', [LocalDevAuthController::class, 'quickLogin'])->name('local.quick.login');
    Route::get('/local-quick-login/{role}', [LocalDevAuthController::class, 'quickLoginGet'])->name('local.quick.login.get');
}

// Subscription Limit Management & Synchronization Endpoint
Route::match(['get', 'post'], '/sync-subscription-limits', [SubscriptionSyncController::class, 'syncSubscriptionLimits'])->name('subscription.sync');
Route::post('/sync-subscription-limits/update', [SubscriptionSyncController::class, 'updateCompanyLimits'])->name('subscription.sync.update');

Route::get('/check', [HomeController::class, 'check'])->middleware([
     'auth',
     'XSS',
]);
Route::post('/notification-status', [HomeController::class, 'notificationStatus'])->name('notification.status')->middleware([
     'auth',
     // 'company_check',
     'XSS',
]);
Route::get('/clear-notification', [HomeController::class, 'clear_notification'])->middleware(['auth', 'XSS'])->name('clear-notification');
// Route::get('/password/resets/{lang?}', 'Auth\LoginController@showLinkRequestForm')->name('change.langPass');
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware(['XSS']);

// Route::get('test_email', [TestEmailController::class, 'test_email'])->name('test_email');


// GET logout kept for bookmarks / old links; named route `logout` is POST in auth.php
Route::get('logout', [HomeController::class, 'logout'])->name('logout.get');
Route::get('cron/auto_leave_deduction', [CronController::class, 'auto_leave_deduction'])->name('cron.auto_leave_deduction');
Route::get('cron/auto_clockout', [CronController::class, 'auto_clockout'])->name('cron.auto_clockout');
Route::get('cron/attendance_punch_image_cleanup', [CronController::class, 'attendance_punch_image_cleanup'])->name('cron.attendance_punch_image_cleanup');
Route::get('cron/crm_monthly_invoices', [CronController::class, 'crm_monthly_invoices'])->name('cron.crm_monthly_invoices');

Route::group(['middleware' => ['verified', 'company_check']], function ()
{

     // company_check

     Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'XSS'])->name('dashboard');
     Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('/home/getlanguvage', [HomeController::class, 'getlanguvage'])->name('home.getlanguvage');

     Route::group(
          [
               'middleware' => [
                    'auth',
                    'XSS',
               ],
          ],
          function ()
          {

               Route::resource('settings', SettingsController::class);


               //mobile menu
               Route::get('mobile_menu', 'MobileMenuController@index')->name('mobile_menu');
               Route::get('create_mobile_menu', 'MobileMenuController@create_mobile_menu')->name('create_mobile_menu');
               Route::post('submit_mobile_menu', 'MobileMenuController@submit_mobile_menu')->name('submit_mobile_menu');
               Route::get('edit_mobile_menu/{id}', 'MobileMenuController@edit_mobile_menu')->name('edit_mobile_menu');
               Route::post('update_mobile_menu', 'MobileMenuController@update_mobile_menu')->name('update_mobile_menu');
               Route::post('delete_mobile_menu/{id}', 'MobileMenuController@delete_mobile_menu')->name('delete_mobile_menu');

               Route::get('mobile_assign_menu/{id}', 'MobileMenuController@mobile_assign_menu')->name('mobile_assign_menu');
               Route::POST('assign_store_menu', 'MobileMenuController@assign_store_menu')->name('assign_store_menu');


               Route::get('letter_setting', 'SettingsController@letter_setting')->name('letter_setting');
               Route::post('email-settings', [SettingsController::class, 'saveEmailSettings'])->name('email.settings');
               Route::post('company-settings', [SettingsController::class, 'saveCompanySettings'])->name('company.settings');
               Route::post('payment-settings', [SettingsController::class, 'savePaymentSettings'])->name('payment.settings');
               Route::post('system-settings', [SettingsController::class, 'saveSystemSettings'])->name('system.settings');

               // Google Calendar
               Route::post('setting/google-calender', [SettingsController::class, 'saveGoogleCalenderSettings'])->name('google.calender.settings')->middleware(['auth', 'XSS']);
               Route::any('event/get_event_data', [EventController::class, 'get_event_data'])->name('event.get_event_data')->middleware(['auth', 'XSS']);
               Route::any('event/export-event', [EventController::class, 'export_event'])->name('event.export-event')->middleware(['auth', 'XSS']);


               Route::post('leave_approval', [SettingsController::class, 'leave_approval'])->name('leave_approval')->middleware(['auth', 'XSS']);
               Route::get('company-setting', [SettingsController::class, 'companyIndex'])->name('company.setting');
               Route::get('company-email-setting/{name}', [EmailTemplateController::class, 'updateStatus'])->name('company.email.setting');
               // Route::post('company-email-setting/{name}', 'EmailTemplateController@updateStatus')->name('status.email.language')->middleware(['auth']);

               Route::post('pusher-settings', [SettingsController::class, 'savePusherSettings'])->name('pusher.settings');
               Route::post('business-setting', [SettingsController::class, 'saveBusinessSettings'])->name('business.setting');

               Route::post('zoom-settings', [SettingsController::class, 'zoomSetting'])->name('zoom.settings');

               Route::get('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
               Route::post('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
               Route::post('test-mail/send', [SettingsController::class, 'testSendMail'])->name('test.send.mail');



               Route::get('create/ip', [SettingsController::class, 'createIp'])->name('create.ip');
               Route::post('create/ip', [SettingsController::class, 'storeIp'])->name('store.ip');
               Route::get('edit/ip/{id}', [SettingsController::class, 'editIp'])->name('edit.ip');
               Route::post('edit/ip/{id}', [SettingsController::class, 'updateIp'])->name('update.ip');
               Route::delete('destroy/ip/{id}', [SettingsController::class, 'destroyIp'])->name('destroy.ip');
          }
     );

     Route::group(
          [
               'middleware' => [
                    'auth',
                    'XSS',
               ],
          ],
          function ()
          {

               Route::get('/orders', [StripePaymentController::class, 'index'])->name('order.index');
               Route::get('/stripe/{code}', [StripePaymentController::class, 'stripe'])->name('stripe');
               Route::get('/stripe_request/{code}', [StripePaymentController::class, 'stripe_request'])->name('stripe_request');
               Route::post('/stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
               Route::post('/stripe/dev-renew', [StripePaymentController::class, 'devRenewWithoutPay'])->name('stripe.dev.renew');
          }
     );
     // Email Templates
     Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language')->middleware(['auth', 'XSS']);
     Route::post('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language')->middleware(['auth']);
     Route::post('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language')->middleware(['auth']);


     //roles managment 
     Route::get('role-index', [RolesController::class, 'index'])->name('role.index')->middleware(['auth']);
     Route::get('role-create', [RolesController::class, 'create'])->name('role.create')->middleware(['auth']);
     Route::get('role-store', [RolesController::class, 'store'])->name('role.store')->middleware(['auth']);
     Route::get('role-edit/{id}', [RolesController::class, 'edit'])->name('role.edit')->middleware(['auth']);
     Route::POST('role-update/{id}', [RolesController::class, 'update'])->name('role.update')->middleware(['auth']);
     //early leave and late comming

     //ketan route add
     Route::resource('visit_type', VisitTypeController::class)->middleware(['auth', 'XSS']);




     Route::resource('grade_type', GradeTypeController::class)->middleware(['auth', 'XSS']);
     Route::resource('categories', CategoriesController::class)->middleware(['auth', 'XSS']);
     Route::resource('category_type', CategoriesTypesController::class)->middleware(['auth', 'XSS']);
     Route::resource('commuting_type', CommuteTypeController::class)->middleware(['auth', 'XSS']);
     Route::get('commuting_active/{id}', 'CommuteTypeController@commuting_active')->name('commuting_active');
     Route::get('commuting_in_active/{id}', 'CommuteTypeController@commuting_in_active')->name('commuting_in_active');
     Route::resource('city_tier', CityTierController::class)->middleware(['auth', 'XSS']);
     Route::get('delete_tierData', 'GradeTypeController@delete_tierData')->name('delete_tierData');
     Route::resource('earlyandlate_leave', EarlyLateCommingLeaveController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('earlyandlate_leave_request', [EarlyLateCommingLeaveController::class, 'earlyandlate_leave_request'])->name('earlyandlate_leave_request')->middleware(['auth']);

     Route::get('earlyandlate_leave_action/{id}', [EarlyLateCommingLeaveController::class, 'action'])->name('earlyandlate_leave_action')->middleware(['auth']);
     Route::POST('earlyandlate_leave/changeaction', [EarlyLateCommingLeaveController::class, 'changeaction'])->name('earlyandlate_leave.changeaction')->middleware(['auth']);

     //apply work from home
     Route::resource('applywfm', ApplyWFMController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('applywfm_request', [ApplyWFMController::class, 'applywfm_request'])->name('applywfm_request')->middleware(['auth']);

     Route::get('applywfm_action/{id}', [ApplyWFMController::class, 'applywfm_action'])->name('applywfm_action')->middleware(['auth']);
     Route::POST('applywfm_update_action', [ApplyWFMController::class, 'applywfm_update_action'])->name('applywfm_update_action')->middleware(['auth']);

     Route::resource('email_template', EmailTemplateController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     // Dead resource removed: EmailTemplateLangController class does not exist.
     // Language templates are handled by EmailTemplateController via manage.email.language routes above.
     // Route::resource('email_template_lang', EmailTemplateLangController::class)->middleware([...]);

     Route::get(
          '/test',

          [SettingsController::class, 'testEmail']
     )->name('test.email')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post(
          '/test/send',
          [SettingsController::class, 'testEmailSend']

     )->name('test.email.send')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     // End



     Route::resource('user', UserController::class)
          ->middleware(
               [
                    'auth',
                    'XSS',
               ]
          );

     //get_users_data
     Route::get('get_users_data_this', [UserController::class, 'get_users_data_this'])->name('get_users_data_this');

     Route::post('employee/json', [EmployeeController::class, 'json'])->name('employee.json')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('employee/designation/description', [EmployeeController::class, 'getDesignationDescription'])->name('employee.designation.description')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('employee/Branch_department_json', [EmployeeController::class, 'Branch_department_json'])->name('employee.Branch_department_json')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('branch/employee/json', [EmployeeController::class, 'employeeJson'])->name('branch.employee.json')->middleware(
          [
               'auth',
               'company_check',
               'XSS',
          ]
     );
     Route::get('employee-profile', [EmployeeController::class, 'profile'])->name('employee.profile')->middleware(
          [
               'auth',
               'company_check',
               'XSS',
          ]
     );

     Route::get('exp_table_remove/{id}', 'EmployeeController@exp_table_remove')->name('exp_table_remove')->middleware(['auth', 'XSS']);
     Route::get('eduction_table_remove/{id}', 'EmployeeController@eduction_table_remove')->name('eduction_table_remove')->middleware(['auth', 'XSS']);
     Route::get('Dependent_table_remove/{id}', 'EmployeeController@Dependent_table_remove')->name('Dependent_table_remove')->middleware(['auth', 'XSS']);
     Route::get('show-employee-profile/{id}', [EmployeeController::class, 'profileShow'])->name('show.employee.profile')->middleware(
          [
               'auth',
               'company_check',
               'XSS',
          ]
     );
     Route::get('lastlogin', [EmployeeController::class, 'lastLogin'])->name('lastlogin')->middleware(
          [
               'auth',
               // 'company_check',
               'XSS',
          ]
     );
     Route::resource('employee', EmployeeController::class)->middleware(
          [
               'auth',
               // 'company_check',
               'XSS',
          ]
     );

     Route::get('employee-data-log', [EmployeeDataChangeLogController::class, 'index'])
          ->name('employee-data-log.index')
          ->middleware(['auth', 'XSS']);
     Route::get('employee-data-log/{id}', [EmployeeDataChangeLogController::class, 'show'])
          ->name('employee-data-log.show')
          ->middleware(['auth', 'XSS']);

     // Form 16 routes stay registered (direct URL / testing). Sidebar + Employee List stay hidden
     // until FORM16_ENABLED=true in .env.
     Route::middleware(['auth', 'XSS'])->prefix('form16')->name('form16.')->group(function () {
          Route::get('/', [Form16Controller::class, 'index'])->name('index');
          Route::get('/employee/{employee}', [Form16Controller::class, 'start'])->name('start');
          Route::post('/init', [Form16Controller::class, 'init'])->name('init');
          Route::get('/{id}', [Form16Controller::class, 'show'])->name('show');
          Route::get('/{id}/refresh', [Form16Controller::class, 'refresh'])->name('refresh');
          Route::post('/{id}/part-a', [Form16Controller::class, 'uploadPartA'])->name('part-a.upload');
          Route::post('/{id}/part-a/delete', [Form16Controller::class, 'deletePartA'])->name('part-a.delete');
          Route::post('/{id}/generate', [Form16Controller::class, 'generate'])->name('generate');
          Route::post('/{id}/verify', [Form16Controller::class, 'verify'])->name('verify');
          Route::post('/{id}/issue', [Form16Controller::class, 'issue'])->name('issue');
          Route::match(['get', 'post'], '/{id}/regenerate', [Form16Controller::class, 'regenerate'])->name('regenerate');
          Route::get('/{id}/preview', [Form16Controller::class, 'preview'])->name('preview');
          Route::get('/{id}/download', [Form16Controller::class, 'download'])->name('download');
          Route::post('/{id}/send', [Form16Controller::class, 'send'])->name('send');
     });

     // Test push notifications (no DB — session log only)
     Route::middleware(['auth', 'XSS'])->prefix('test-notification')->name('test.notification.')->group(function () {
          Route::get('/', [TestNotificationController::class, 'index'])->name('index');
          Route::post('/send', [TestNotificationController::class, 'send'])->name('send');
          Route::post('/clear-log', [TestNotificationController::class, 'clearLog'])->name('clear-log');
     });

     Route::resource('department', DepartmentController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //department active deactive
     Route::get('department_active/{id}', 'DepartmentController@department_active')->name('department_active');
     Route::get('department_in_active/{id}', 'DepartmentController@department_in_active')->name('department_in_active');


     Route::resource('designation', DesignationController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //designation active deactive
     Route::get('designation_active/{id}', 'DesignationController@designation_active')->name('designation_active');
     Route::get('designation_in_active/{id}', 'DesignationController@designation_in_active')->name('designation_in_active');

     Route::resource('document', DocumentController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('branch', BranchController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     // getcities
     Route::get('getcities/{id}', 'BranchController@getcities')->name('getcities');
     Route::get('getdistict/{id}', 'BranchController@getdistict')->name('getdistict');

     Route::get('get-district-by-state-name/{stateName}', 'BranchController@getDistrictByStateName')->name('get-district-by-state-name');
     Route::get('get-city-by-district-name/{districtName}', 'BranchController@getCityByDistrictName')->name('get-city-by-district-name');
     Route::get('get-state-by-country/{country_id}', 'BranchController@getStateByCountryId')->name('get-state-by-country');



     //LocationController
     Route::resource('location', LocationController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     //billing_addres
     Route::resource('billing_addres', BillingAddresController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('awardtype', AwardTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('award', AwardController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('termination/{id}/description', [TerminationController::class, 'description'])->name('termination.description');

     Route::resource('termination', TerminationController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('terminationtype', TerminationTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('announcement/getdepartment', [AnnouncementController::class, 'getdepartment'])->name('announcement.getdepartment')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('announcement/getemployee', [AnnouncementController::class, 'getemployee'])->name('announcement.getemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('announcement', AnnouncementController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::get('holiday/calender', [HolidayController::class, 'calender'])->name('holiday.calender')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('holiday', HolidayController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     // Google Calendar
     Route::any('holiday/get_holiday_data', [HolidayController::class, 'get_holiday_data'])->name('holiday.get_holiday_data')->middleware(['auth', 'XSS']);
     // Route::any('holiday/export-event', [HolidayController::class, 'export_event'])->name('holiday.export-holiday')->middleware(['auth', 'XSS']);

     Route::get('employee/salary/{eid}', [SetSalaryController::class, 'employeeBasicSalary'])->name('employee.basic.salary')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('allowances/create/{eid}', [AllowanceController::class, 'allowanceCreate'])->name('allowances.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('commissions/create/{eid}', [CommissionController::class, 'commissionCreate'])->name('commissions.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('loans/create', [LoanController::class, 'loanCreate'])->name('loans.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('loans', 'LoanController@index')->name('loans');
     Route::get('saturationdeductions/create/{eid}', [SaturationDeductionController::class, 'saturationdeductionCreate'])->name('saturationdeductions.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('otherpayments/create/{eid}', [OtherPaymentController::class, 'otherpaymentCreate'])->name('otherpayments.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('overtimes/create/{eid}', [OvertimeController::class, 'overtimeCreate'])->name('overtimes.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     //payslip

     Route::resource('paysliptype', PayslipTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('allowance', AllowanceController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('commission', CommissionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('commission_option', Commission_optionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('getCommission/{id}', 'Commission_optionController@getCommission')->name('getCommission');




     Route::resource('allowanceoption', AllowanceOptionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     //creae me 
     Route::get('getAllowance/{id}', 'AllowanceOptionController@getAllowance')->name('getAllowance');

     Route::resource('loanoption', LoanOptionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('deductionoption', DeductionOptionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('loan', LoanController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('otherpaymentotion', OtherPaymentOptionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]

     );

     Route::resource('saturationdeduction', SaturationDeductionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('otherpayment', OtherPaymentController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('overtime', OvertimeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('event/getdepartment', [EventController::class, 'getdepartment'])->name('event.getdepartment')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('event/getemployee', [EventController::class, 'getemployee'])->name('event.getemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('event/data/{id}', [EventController::class, 'showData'])->name('eventsshow');
     Route::resource('event', EventController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );



     Route::get('import/event/file', [EventController::class, 'importFile'])->name('event.file.import');
     Route::post('import/event', [EventController::class, 'import'])->name('event.import');
     Route::get('export/event', [EventController::class, 'export'])->name('event.export');

     Route::post('meeting/getdepartment', [MeetingController::class, 'getdepartment'])->name('meeting.getdepartment')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('meeting/getemployee', [MeetingController::class, 'getemployee'])->name('meeting.getemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('meeting', MeetingController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('calender/meeting', [MeetingController::class, 'calender'])->name('meeting.calender')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::any('/meeting/get_meeting_data', [MeetingController::class, 'get_meeting_data'])->name('meeting.get_meeting_data')->middleware(['auth', 'XSS']);

     Route::post('employee/update/sallary/{id}', [SetSalaryController::class, 'employeeUpdateSalary'])->name('employee.salary.update')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('salary/employeeSalary', [SetSalaryController::class, 'employeeSalary'])->name('employeesalary')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('setsalary', SetSalaryController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('payslip/paysalary/{id}/{date}', [PaySlipController::class, 'paysalary'])->name('payslip.paysalary')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/bulk_pay_create/{date}', [PaySlipController::class, 'bulk_pay_create'])->name('payslip.bulk_pay_create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('payslip/apply_leave_absent/', [PaySlipController::class, 'apply_leave_absent'])->name('payslip.apply_leave_absent')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('payslip/apply_leave_absent_save/', [PaySlipController::class, 'apply_leave_absent_save'])->name('payslip.apply_leave_absent_save')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('payslip/bulkpayment/{date}', [PaySlipController::class, 'bulkpayment'])->name('payslip.bulkpayment')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('payslip/search_json', [PaySlipController::class, 'search_json'])->name('payslip.search_json')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/employeepayslip', [PaySlipController::class, 'employeepayslip'])->name('payslip.employeepayslip')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/showemployee/{id}', [PaySlipController::class, 'showemployee'])->name('payslip.showemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/editemployee/{id}', [PaySlipController::class, 'editemployee'])->name('payslip.editemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('payslip/editemployee/{id}', [PaySlipController::class, 'updateEmployee'])->name('payslip.updateemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/pdf/{id}/{m}', [PaySlipController::class, 'pdf'])->name('payslip.pdf')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/payslipPdf/{id}', [PaySlipController::class, 'payslipPdf'])->name('payslip.payslipPdf')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('payslip/send/{id}/{m}', [PaySlipController::class, 'send'])->name('payslip.send')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('payslip/delete/{id}', [PaySlipController::class, 'destroy'])->name('payslip.delete')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('payslip', PaySlipController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('my-payslip', MyPaySlipController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('resignation', ResignationController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('resignation_request', [ResignationController::class, 'resignation_request'])->name('resignation_request');

     Route::get('resignation/{id}/action', [ResignationController::class, 'action'])->name('resignation.action')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('resignation_request_view/{id}', [ResignationController::class, 'resignation_request_view'])->name('resignation_request_view')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::POST('resignation/{id}/actionupdate', [ResignationController::class, 'actionupdate'])->name('resignation.actionupdate')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::GET('resignation/{id}/exit_inerview', [ResignationController::class, 'exit_inerview'])->name('resignation.exit_inerview')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::POST('resignation/{id}/exit_inerviewupdate', [ResignationController::class, 'exit_inerviewupdate'])->name('resignation.exit_inerviewupdate')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('travel', TravelController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('promotion', PromotionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('transfer', TransferController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('complaint', ComplaintController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('warning', WarningController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.account')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('accountlist', AccountListController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('accountbalance', [AccountListController::class, 'account_balance'])->name('accountbalance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::get('leave/{id}/action', [LeaveController::class, 'action'])->name('leave.action')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('leave_request_create', [LeaveController::class, 'leave_request_create'])->name('leave_request_create')->middleware(['auth', 'XSS']);

     // /HR approved
     Route::get('leave/{id}/action_hr', [LeaveController::class, 'action_hr'])->name('leave.action_hr')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('leave/changeaction', [LeaveController::class, 'changeaction'])->name('leave.changeaction')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('leave/changeaction_hr', [LeaveController::class, 'changeaction_hr'])->name('leave.changeaction_hr')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::post('leave/jsoncount', [LeaveController::class, 'jsoncount'])->name('leave.jsoncount')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('leave', LeaveController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('my_leave_balance', [LeaveController::class, 'my_leave_balance'])->name('leave.my_leave_balance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('calender/leave', [LeaveController::class, 'calender'])->name('leave.calender')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::any('leave/get_leave_data', [LeaveController::class, 'get_leave_data'])->name('leave.get_leave_data')->middleware(['auth', 'XSS']);

     Route::get('ticket/{id}/reply', [TicketController::class, 'reply'])->name('ticket.reply')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('ticket/changereply', [TicketController::class, 'changereply'])->name('ticket.changereply')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('ticket', TicketController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('attendanceemployee/bulkattendance', [AttendanceEmployeeController::class, 'bulkAttendance'])->name('attendanceemployee.bulkattendance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('attendanceemployee/bulkattendance', [AttendanceEmployeeController::class, 'bulkAttendanceData'])->name('attendanceemployee.bulkattendance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('attendanceemployee/attendance', [AttendanceEmployeeController::class, 'attendance'])->name('attendanceemployee.attendance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //my-attendance
     Route::get('attendanceemployee/my-attendance', [AttendanceEmployeeController::class, 'my_attendance'])->name('attendanceemployee.my-attendance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('attendanceemployee', AttendanceEmployeeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //import attendance
     Route::get('import/attendance/file', [AttendanceEmployeeController::class, 'importFile'])->name('attendance.file.import');
     Route::post('import/attendance', [AttendanceEmployeeController::class, 'importEmployeeAttendance'])->name('attendance.import');
     Route::get('export/attendance/{type}/{month}/{branch?}/{department?}', [AttendanceEmployeeController::class, 'export'])->name('attendance.export');

     Route::get('employee_birthday/{id}', 'AttendanceEmployeeController@employee_birthday')->name('employee_birthday');
     Route::get('send_employee_email/{id}', 'AttendanceEmployeeController@send_employee_email')->name('send_employee_email');
     Route::get('employee_anniversary', 'AttendanceEmployeeController@employee_anniversary')->name('employee_anniversary');
     Route::get('currunt_month_holiday', 'AttendanceEmployeeController@currunt_month_holiday')->name('currunt_month_holiday');
     Route::get('employee_anniversary_send_email/{id}', 'AttendanceEmployeeController@employee_anniversary_send_email')->name('employee_anniversary_send_email');
     Route::get('google_map/{id}', 'AttendanceEmployeeController@google_map')->name('google_map');

     //added by monali
     Route::post('save_address_from_lat_long', 'AttendanceEmployeeController@saveAddressFromLatLng')->name('save_address_from_lat_long');
     Route::post('check_address_saved', 'AttendanceEmployeeController@checkAddressSaved')->name('check_address_saved');
     Route::get('phone_configuration/{id}', [EmployeeController::class, 'phoneConfiguration'])->name('employee.phone.configuration');
     //added by monali

     Route::resource('timesheet', TimeSheetController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('timesheet_request', 'TimeSheetController@timesheet_request')->name('timesheet_request');

     Route::resource('expensetype', ExpenseTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('incometype', IncomeTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('paymenttype', PaymentTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('leavetype', LeaveTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('payees', PayeesController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('payer', PayerController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('deposit', DepositController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('expense', ExpenseController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('transferbalance', TransferBalanceController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('approved-claims', 'MyClaimReimbursement@approvedClaims')->middleware(
          [
               'auth',
               'XSS',
          ]
     )->name("approved-claims");


     Route::group(
          [
               'middleware' => [
                    'auth',
                    'XSS',
               ],
          ],
          function ()
          {
               Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
               Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
               Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
               Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
               Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
               Route::delete('/lang/{id}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
          }
     );

     Route::resource('roles', RoleController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );



     Route::get('roles_show_data/{id}', 'RoleController@show_data');
     // show_data

     Route::resource('permissions', PermissionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('plans', PlanController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     // CRM Company Invoices (Super Admin)
     Route::middleware(['auth', 'XSS'])->prefix('crm/invoices')->name('crm.invoices.')->group(function () {
          Route::get('/', [CrmInvoiceController::class, 'index'])->name('index');
          Route::get('/create', [CrmInvoiceController::class, 'create'])->name('create');
          Route::post('/', [CrmInvoiceController::class, 'store'])->name('store');
          Route::get('/settings', [CrmInvoiceController::class, 'settings'])->name('settings');
          Route::post('/settings', [CrmInvoiceController::class, 'updateSettings'])->name('settings.update');
          Route::get('/company-count', [CrmInvoiceController::class, 'companyEmployeeCount'])->name('company-count');
          Route::get('/{id}', [CrmInvoiceController::class, 'show'])->name('show');
          Route::get('/{id}/edit', [CrmInvoiceController::class, 'edit'])->name('edit');
          Route::put('/{id}', [CrmInvoiceController::class, 'update'])->name('update');
          Route::post('/{id}/mark-paid', [CrmInvoiceController::class, 'markPaid'])->name('mark-paid');
          Route::get('/{id}/pdf', [CrmInvoiceController::class, 'downloadPdf'])->name('pdf');
          Route::get('/{id}/regenerate-pdf', [CrmInvoiceController::class, 'regeneratePdf'])->name('regenerate-pdf');
          Route::post('/{id}/send', [CrmInvoiceController::class, 'send'])->name('send');
     });
     // Route::get('/plan_request/{code}', 'PlanController@plan_request')->name('plan_request')->middleware(
     //     [
     //         'auth',
     //         'XSS',
     //     ]
     // );


     // Route::resource('plan_requests', 'PlanRequestController');

     // Route::get('/plan_requests/update/{id}', 'PlanRequestController@update')->name('plan_request.update')->middleware(
     //     [
     //         'auth',
     //         'XSS',
     //     ]
     // );

     Route::group(
          [
               'middleware' => [
                    'auth',
                    'XSS',

               ],
          ],
          function ()
          {
               Route::resource('plan_request', PlanRequestController::class);
          }
     );



     // Plan Request Module
     Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index')->middleware(['auth', 'XSS',]);
     Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view')->middleware(['auth', 'XSS',]);
     Route::get('request_send/{id}', [PlanRequestController::class, 'userRequest'])->name('send.request')->middleware(['auth', 'XSS',]);
     Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request')->middleware(['auth', 'XSS',]);
     Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel')->middleware(['auth', 'XSS',]);
     // End Plan Request Module



     Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');
     Route::get('inactive_user/{id}', [UserController::class, 'inactive_user'])->name('inactive_user');
     Route::get('active_user/{id}', [UserController::class, 'active_user'])->name('active_user');
     Route::get('inactive_role/{id}', [RoleController::class, 'inactive_role'])->name('inactive_role');
     Route::get('active_role/{id}', [RoleController::class, 'active_role'])->name('active_role');


     //// wage_reports
     Route::get('wage_reports_index', 'ReportsManageController@wage_reports_index')->name('wage_reports_index');
     Route::get('wagereports_export', 'ReportsManageController@wagereports_export')->name('wagereports_export');

     //Esic Register Reports
     Route::get('esic_register_reports', 'ReportsManageController@esic_register_reports')->name('esic_register_reports');
     Route::get('esic_register_reports_export', 'ReportsManageController@esic_register_reports_export')->name('esic_register_reports_export');


     Route::resource('coupons', CouponController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('account-assets', AssetController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('document-upload', DucumentUploadController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('indicator', IndicatorController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('appraisal', AppraisalController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('goaltype', GoalTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('goaltracking', GoalTrackingController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('company-policy', CompanyPolicyController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('trainingtype', TrainingTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('trainer', TrainerController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('training/status', [TrainingController::class, 'updateStatus'])->name('training.status')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::resource('training', TrainingController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('{id}/plan-get-payment-status', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get(
          '/apply-coupon',
          [CouponController::class, 'applyCoupon'],

     )->name('apply.coupon')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/resigned-employee-report', [ReportController::class, 'resignedEmployeeReport'])->name('resigned-employee-report')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/new-joinee-report', [ReportController::class, 'newJoineeReport'])->name('new-joinee-report')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/income-expense', [ReportController::class, 'incomeVsExpense'])->name('report.income-expense')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/leave', [ReportController::class, 'leave'])->name('report.leave')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/claim', [ReportController::class, 'claim'])->name('report.claim')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //allreaports


     Route::get('employee/{id}/leave/{status}/{type}/{start_date}/{end_date}', [ReportController::class, 'employeeLeave'])->name('report.employee.leave')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/account-statement', [ReportController::class, 'accountStatement'])->name('report.account.statement')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('report/payroll', [ReportController::class, 'payroll'])->name('report.payroll')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/monthly/attendance', [ReportController::class, 'monthlyAttendance'])->name('report.monthly.attendance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('employee-attendance-geofencing-report', [ReportController::class, 'employeeAttendanceGeofencingReport'])->name('employee-attendance-geofencing-report')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('late-coming-report', [ReportController::class, 'lateComingReport'])->name('late-coming-report')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('monthly/getdepartment', [ReportController::class, 'getdepartment'])->name('monthly.getdepartment')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('monthly/getemployee', [ReportController::class, 'getemployee'])->name('monthly.getemployee')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/attendance/{start_date}/{end_date}/{branch}/{department}/{employee}', [ReportController::class, 'monthelyAttendanceReport'])->name('report.attendance')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/employee-attendance-geofencing-report/{start_date}/{end_date}/{branch}/{department}/{employee}', [ReportController::class, 'employeeAttendanceGeofencingReportExport'])->name('employee-attendance-geofencing-report-export')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/timesheet', [ReportController::class, 'timesheet'])->name('report.timesheet')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/birthday_list', [ReportController::class, 'getBirthdayListReport'])->name('report.birthday_list')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/work_anniversary_list', [ReportController::class, 'getWorkAnniversaryListReport'])->name('report.work_anniversary_list')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('report/marriage_anniversary_list', [ReportController::class, 'getMarriageAnniversaryListReport'])->name('report.marriage_anniversary_list')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //------------------------------------  Recurtment --------------------------------

     Route::resource('job-category', JobCategoryController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('job-stage', JobStageController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-stage/order', [JobStageController::class, 'order'])->name('job.stage.order')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('job', JobController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('career/{id}/{lang}', [JobController::class, 'career'])->name('career');
     Route::get('job/requirement/{code}/{lang}', [JobController::class, 'jobRequirement'])->name('job.requirement');
     Route::get('job/apply/{code}/{lang}', [JobController::class, 'jobApply'])->name('job.apply');
     Route::post('job/apply/data/{code}', [JobController::class, 'jobApplyData'])->name('job.apply.data');
     Route::get('job_reference/{id}', [JobController::class, 'reference'])->name('job_reference');

     Route::get('job_referencelist', [JobController::class, 'job_referencelist'])->name('job_referencelist');
     Route::POST('job_reference_delete/{id}', [JobController::class, 'job_reference_delete'])->name('job_reference_delete');


     Route::get('reference_action/{id}', [JobController::class, 'reference_action'])->name('reference_action');
     Route::POST('reference_action_status/{id}', [JobController::class, 'reference_action_status'])->name('reference_action_status');


     Route::post('reference_upload/{id}', [JobController::class, 'reference_upload'])->name('reference_upload');


     Route::get('candidates-job-applications', [JobApplicationController::class, 'candidate'])->name('job.application.candidate')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('job-application', JobApplicationController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     //get pass
     Route::resource('getpass', GetPassController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('getpass_request', [GetPassController::class, 'getpass_request'])->name('getpass_request')->middleware(['auth']);

     Route::get('getpass_changeaction/{id}', [GetPassController::class, 'changeaction'])->name('getpass_changeaction')->middleware(['auth']);
     Route::POST('getpass/changeactionupdate', [GetPassController::class, 'changeactionupdate'])->name('changeactionupdate')->middleware(['auth']);


     Route::post('job-application/order', [JobApplicationController::class, 'order'])->name('job.application.order')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-application/{id}/rating', [JobApplicationController::class, 'rating'])->name('job.application.rating')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::delete('job-application/{id}/archive', [JobApplicationController::class, 'archive'])->name('job.application.archive')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('job-application/{id}/skill/store', [JobApplicationController::class, 'addSkill'])->name('job.application.skill.store')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-application/{id}/note/store', [JobApplicationController::class, 'addNote'])->name('job.application.note.store')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::delete('job-application/{id}/note/destroy', [JobApplicationController::class, 'destroyNote'])->name('job.application.note.destroy')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-application/getByJob', [JobApplicationController::class, 'getByJob'])->name('get.job.application')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::get('job-onboard', [JobApplicationController::class, 'jobOnBoard'])->name('job.on.board')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('job-onboard/create/{id}', [JobApplicationController::class, 'jobBoardCreate'])->name('job.on.board.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-onboard/store/{id}', [JobApplicationController::class, 'jobBoardStore'])->name('job.on.board.store')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('job-onboard/edit/{id}', [JobApplicationController::class, 'jobBoardEdit'])->name('job.on.board.edit')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-onboard/update/{id}', [JobApplicationController::class, 'jobBoardUpdate'])->name('job.on.board.update')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::delete('job-onboard/delete/{id}', [JobApplicationController::class, 'jobBoardDelete'])->name('job.on.board.delete')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('job-onboard/convert/{id}', [JobApplicationController::class, 'jobBoardConvert'])->name('job.on.board.convert')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('job-onboard/convert/{id}', [JobApplicationController::class, 'jobBoardConvertData'])->name('job.on.board.convert')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::post('job-application/stage/change', [JobApplicationController::class, 'stageChange'])->name('job.application.stage.change')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('custom-question', CustomQuestionController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     Route::resource('interview-schedule', InterviewScheduleController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::get('interview-schedule/create/{id?}', [InterviewScheduleController::class, 'create'])->name('interview-schedule.create')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::any('/interview-schedule/get_interview-schedule_data', [InterviewScheduleController::class, 'get_interview_schedule_data'])->name('interview-schedule.get_interview-schedule_data')->middleware(['auth', 'XSS']);


     //================================= Custom Landing Page ====================================//

     // Route::get('/landingpage', 'LandingPageSectionController@index')->name('custom_landing_page.index')->middleware(['auth', 'XSS']);
     Route::get('/LandingPage/show/{id}', [LandingPageSectionController::class, 'show']);
     Route::post('/LandingPage/setConetent', [LandingPageSectionController::class, 'setConetent'])->middleware(['auth', 'XSS']);
     Route::get('/get_landing_page_section/{name}', function ($name)
     {
          $plans = \DB::table('plans')->get();

          return view('custom_landing_page.' . $name, compact('plans'));
     });
     Route::post('/LandingPage/removeSection/{id}', [LandingPageSectionController::class, 'removeSection'])->middleware(['auth', 'XSS']);
     Route::post('/LandingPage/setOrder', [LandingPageSectionController::class, 'setOrder'])->middleware(['auth', 'XSS']);
     Route::post('/LandingPage/copySection', [LandingPageSectionController::class, 'copySection'])->middleware(['auth', 'XSS']);


     //================================= Payment Gateways  ====================================//

     Route::post('/plan-pay-with-paystack', [PaystackPaymentController::class, 'planPayWithPaystack'])->name('plan.pay.with.paystack')->middleware(['auth', 'XSS']);
     Route::get('/plan/paystack/{pay_id}/{plan_id}', [PaystackPaymentController::class, 'getPaymentStatus'])->name('plan.paystack');

     Route::post('/plan-pay-with-flaterwave', [FlutterwavePaymentController::class, 'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave')->middleware(['auth', 'XSS']);
     Route::get('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class, 'getPaymentStatus'])->name('plan.flaterwave');

     Route::post('/plan-pay-with-razorpay',  [RazorpayPaymentController::class, 'planPayWithRazorpay'])->name('plan.pay.with.razorpay')->middleware(['auth', 'XSS']);
     Route::get('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::class, 'getPaymentStatus'])->name('plan.razorpay');

     Route::post('/plan-pay-with-paytm',  [PaytmPaymentController::class, 'planPayWithPaytm'])->name('plan.pay.with.paytm')->middleware(['auth', 'XSS']);
     Route::post('/plan/paytm/{plan}', [PaytmPaymentController::class, 'getPaymentStatus'])->name('plan.paytm');

     Route::post('/plan-pay-with-mercado',  [MercadoPaymentController::class, 'planPayWithMercado'])->name('plan.pay.with.mercado')->middleware(['auth', 'XSS']);
     Route::get('/plan/mercado/{plan}',  [MercadoPaymentController::class, 'getPaymentStatus'])->name('plan.mercado');

     Route::post('/plan-pay-with-mollie',  [MolliePaymentController::class, 'planPayWithMollie'])->name('plan.pay.with.mollie')->middleware(['auth', 'XSS']);
     Route::get('/plan/mollie/{plan}', [MolliePaymentController::class, 'getPaymentStatus'])->name('plan.mollie');

     Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth', 'XSS']);
     Route::get('/plan/skrill/{plan}',  [SkrillPaymentController::class, 'getPaymentStatus'])->name('plan.skrill');

     Route::post('/plan-pay-with-coingate', [CoingatePaymentController::class, 'planPayWithCoingate'])->name('plan.pay.with.coingate')->middleware(['auth', 'XSS']);
     Route::get('/plan/coingate/{plan}', [CoingatePaymentController::class, 'getPaymentStatus'])->name('plan.coingate');

     Route::post('paymentwall', [PaymentWallPaymentController::class, 'paymentwall'])->name('paymentwall');
     Route::post('plan-pay-with-paymentwall/{plan}', [PaymentWallPaymentController::class, 'planPayWithPaymentwall'])->name('plan.pay.with.paymentwall');
     Route::any('/plan/{flag}', [PaymentWallPaymentController::class, 'paymenterror'])->name('callback.error');
     // Route::get('/plans/{flag}', ['as' => 'error.plan.show','uses' => 'PaymentWallPaymentController@planeerror']);


     Route::resource('competencies', CompetenciesController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::resource('performanceType', PerformanceTypeController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //employee Import & Export
     Route::get('import/employee/file', [EmployeeController::class, 'importFile'])->name('employee.file.import');
     Route::post('import/employee', [EmployeeController::class, 'importEmployee'])->name('employee.import');
     Route::get('download-import-employee-sample-file', [EmployeeController::class, 'downloadImportEmployeeSampleFile'])->name('download-import-employee-sample-file');

     Route::get('export/employee', [EmployeeController::class, 'export'])->name('employee.export');
     Route::get('getEmployees/{id}', 'EmployeeController@getEmployees')->name('getEmployees');
     Route::get('get_users_data/{id}', 'EmployeeController@get_users_data')->name('get_users_data');


     //Leave

     Route::get('export_import/leave/file', [LeaveController::class, 'export_import_file'])->name('leave.file.export_import_file');
     Route::post('export_import/leave', [LeaveController::class, 'exportImportLeave'])->name('leave.export_import');
     Route::get('download-import-leave-sample-file', [LeaveController::class, 'downloadImportLeaveSampleFile'])->name('download-import-leave-sample-file');

     // Timesheet Import & Export

     Route::get('import/timesheet/file', [TimeSheetController::class, 'importFile'])->name('timesheet.file.import');
     Route::post('import/timesheet', [TimeSheetController::class, 'import'])->name('timesheet.import');
     Route::get('export/timesheet', [TimeSheetController::class, 'export'])->name('timesheet.export');
     Route::get('export/timesheet/export', [ReportController::class, 'exportTimeshhetReport'])->name('timesheet.report.export');

     //leave export
     Route::get('export/leave', [LeaveController::class, 'export'])->name('leave.export');
     Route::get('export/leave/report', [ReportController::class, 'LeaveReportExport'])->name('leave.report.export');

     Route::get('leaverequest', [LeaveController::class, 'leaverequest'])->name('leaverequest');
     //deposite Export
     Route::get('export/deposite', [DepositController::class, 'export'])->name('deposite.export');

     //expense Export
     Route::get('export/expense', [ExpenseController::class, 'export'])->name('expense.export');

     //Transfer Balance Export
     Route::get('export/transfer-balance', [TransferBalanceController::class, 'export'])->name('transfer_balance.export');

     //Training Import & Export
     Route::get('export/training', [TrainingController::class, 'export'])->name('training.export');

     //Payroll Export
     Route::get('export/payroll', [ReportController::class, 'PayrollReportExport'])->name('payroll.report.export');

     // payslip export
     Route::post('export/payslip', [PaySlipController::class, 'PayslipExport'])->name('payslip.export');

     //Account Statement Export
     Route::get('export/accountstatement/report', [ReportController::class, 'AccountStatementReportExport'])->name('accountstatement.report.export');

     //Trainer Export
     Route::get('export/trainer', [TrainerController::class, 'export'])->name('trainer.export');
     Route::get('import/training/file', [TrainerController::class, 'importFile'])->name('trainer.file.import');
     Route::post('import/training', [TrainerController::class, 'import'])->name('trainer.import');

     //Holiday Export & Import
     Route::get('export/holidays', [HolidayController::class, 'export'])->name('holidays.export');
     Route::get('import/holidays/file', [HolidayController::class, 'importFile'])->name('holidays.file.import');
     Route::post('import/holidays', [HolidayController::class, 'import'])->name('holidays.import');


     //Asset Import & Export
     Route::get('export/assets', [AssetController::class, 'export'])->name('assets.export');
     Route::get('import/assets/file', [AssetController::class, 'importFile'])->name('assets.file.import');
     Route::post('import/assets', [AssetController::class, 'import'])->name('assets.import');

     //zoom meeting
     Route::any('zoommeeting/calendar', [ZoomMeetingController::class, 'calender'])->name('zoom_meeting.calender')->middleware(['auth', 'XSS']);
     Route::resource('zoom-meeting', ZoomMeetingController::class)->middleware(['auth', 'XSS']);
     Route::any('zoom-meeting/get_zoom_meeting_data', [ZoomMeetingController::class, 'get_zoom_meeting_data'])->name('zoommeeting.get_zoom_meeting_data')->middleware(['auth', 'XSS']);
     // Route::any('zoom-meeting/export-zoom_meeting', [ZoomMeetingController::class, 'export_zoom_meeting'])->name('zoommeeting.export-zoom_meeting')->middleware(['auth', 'XSS']);

     //slack
     Route::post('setting/slack', [SettingsController::class, 'slack'])->name('slack.setting');

     //telegram
     Route::post('setting/telegram', [SettingsController::class, 'telegram'])->name('telegram.setting');

     //twilio
     Route::post('setting/twilio', [SettingsController::class, 'twilio'])->name('twilio.setting');

     // recaptcha
     Route::post('/recaptcha-settings', [SettingsController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store')->middleware(['auth', 'XSS']);

     // user reset password
     Route::any('user-reset-password/{id}', [UserController::class, 'userPassword'])->name('user.reset');
     Route::post('user-reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('user.password.update');

     //contract
     Route::resource('contract_type', ContractTypeController::class)->middleware(['auth', 'XSS']);
     // Route::resource('visit_type', VisitTypeController::class)->middleware(['auth', 'XSS']);
     // Route::resource('grade_type', GradeTypeController::class)->middleware(['auth', 'XSS']);
     Route::resource('categories', CategoriesController::class)->middleware(['auth', 'XSS']);
     // Route::resource('category_type', CategoriesTypesController::class)->middleware(['auth', 'XSS']);

     Route::resource('my_claim', MyClaimReimbursement::class)->middleware(['auth', 'XSS']);
     Route::get('file-download/{id}', [MyClaimReimbursement::class, 'fileDownload'])->name('file.download')->middleware(['auth', 'XSS']);

     Route::get('my_claim/{id}/action_hr', [MyClaimReimbursement::class, 'action_hr'])->name('my_claim.action_hr')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::get('compare-actual', [MyClaimReimbursement::class, 'compareActual'])->name('compare.actual')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('my_claim/changeaction_hr', [MyClaimReimbursement::class, 'changeaction_hr'])->name('my_claim.changeaction_hr')->middleware(
          [
               'auth',
               'XSS',
          ]
     );



     Route::resource('my_visit', MyvisitController::class)->middleware(['auth', 'XSS']);
     Route::get('my_visit/{id}/action_hr', [MyvisitController::class, 'action_hr'])->name('my_visit.action_hr')->middleware(
          [
               'auth',
               'XSS',
          ]
     );
     Route::post('my_visit/changeaction_hr', [MyvisitController::class, 'changeaction_hr'])->name('my_visit.changeaction_hr')->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     //visit
     Route::get('get_visitCitiesData', 'MyvisitController@get_visitCitiesData')->name('get_visitCitiesData');
     Route::get('get_tierCitiesData', 'MyvisitController@get_tierCitiesData')->name('get_tierCitiesData');
     Route::get('get_getVisitTypeexpense', 'MyClaimReimbursement@get_getVisitTypeexpense')->name('get_getVisitTypeexpense');

     Route::get('my_visit_request', 'MyvisitController@my_visit_request')->name('my_visit_request');
     Route::get('my_claim_request', 'MyClaimReimbursement@my_claim_request')->name('my_claim_request');

     Route::get('get_commutingData', 'MyClaimReimbursement@get_commutingData')->name('get_commutingData');
     Route::get('get_getAmount', 'MyClaimReimbursement@getAmount')->name('get_getAmount');
     Route::get('get_getAmountinput', 'MyClaimReimbursement@get_getAmountinput')->name('get_getAmountinput');


     Route::get('get_getGradeType', 'MyClaimReimbursement@get_getGradeType')->name('get_getGradeType');
     Route::get('get_getCitiesData', 'MyClaimReimbursement@get_getCitiesData')->name('get_getCitiesData');




     Route::get('get_getExpensesdData', 'MyClaimReimbursement@get_getExpensesdData')->name('get_getExpensesdData');

     Route::get('visitcategory', 'MyClaimReimbursement@visitcategory')->name('visitcategory');


     Route::resource('contract', ContractController::class)->middleware(['auth', 'XSS']);
     Route::post('/contract_status_edit/{id}', [ContractController::class, 'contract_status_edit'])->name('contract.status')->middleware(['auth', 'XSS']);
     Route::post('/contract/{id}/file', [ContractController::class, 'fileUpload'])->name('contracts.file.upload')->middleware(['auth', 'XSS']);
     Route::get('/contract/{id}/file/{fid}',  [ContractController::class, 'fileDownload'])->name('contracts.file.download')->middleware(['auth', 'XSS']);
     Route::get('/contract/{id}/file/delete/{fid}', [ContractController::class, 'fileDelete'])->name('contracts.file.delete')->middleware(['auth', 'XSS']);
     Route::post('/contract/{id}/notestore', [ContractController::class, 'noteStore'])->name('contracts.note.store')->middleware(['auth']);
     Route::get('/contract/{id}/note', [ContractController::class, 'noteDestroy'])->name('contracts.note.destroy')->middleware(['auth']);

     Route::post('contract/{id}/description', [ContractController::class, 'descriptionStore'])->name('contracts.description.store')->middleware(['auth']);


     Route::post('/contract/{id}/commentstore', [ContractController::class, 'commentStore'])->name('comment.store');
     Route::get('/contract/{id}/comment', [ContractController::class, 'commentDestroy'])->name('comment.destroy');


     Route::get('/contract/copy/{id}', [ContractController::class, 'copycontract'])->name('contracts.copy')->middleware(['auth', 'XSS']);
     Route::post('/contract/copy/store/{id}', [ContractController::class, 'copycontractstore'])->name('contracts.copystore')->middleware(['auth', 'XSS']);

     Route::get('contract/{id}/get_contract', [ContractController::class, 'printContract'])->name('get.contract');
     Route::get('contract/pdf/{id}', [ContractController::class, 'pdffromcontract'])->name('contract.download.pdf');

     // Route::get('/signature/{id}', 'ContractController@signature')->name('signature')->middleware(['auth','XSS']);
     // Route::post('/signaturestore', 'ContractController@signatureStore')->name('signaturestore')->middleware(['auth','XSS']);

     Route::get('/contract/{id}/mail', [ContractController::class, 'sendmailContract'])->name('send.mail.contract');
     Route::get('/signature/{id}', [ContractController::class, 'signature'])->name('signature')->middleware(['auth', 'XSS']);
     Route::post('/signaturestore', [ContractController::class, 'signatureStore'])->name('signaturestore')->middleware(['auth', 'XSS']);

     //offer Letter
     Route::post('setting/offerlatter/{lang?}', [SettingsController::class, 'offerletterupdate'])->name('offerlatter.update');
     Route::get('setting/offerlatter', [SettingsController::class, 'index'])->name('get.offerlatter.language');
     Route::get('job-onboard/pdf/{id}', [JobApplicationController::class, 'offerletterPdf'])->name('offerlatter.download.pdf');
     Route::get('job-onboard/doc/{id}', [JobApplicationController::class, 'offerletterDoc'])->name('offerlatter.download.doc');

     //Incrementlatter
     Route::post('setting/incrementlatter/{lang?}', [SettingsController::class, 'incrementlatterupdate'])->name('incrementlatter.update');

     // relieving
     Route::post('setting/relievingtlatter/{lang?}', [SettingsController::class, 'relievingtlatterupdate'])->name('relievingtlatter.update');

     //joining Letter
     Route::post('setting/joiningletter/{lang?}', [SettingsController::class, 'joiningletterupdate'])->name('joiningletter.update');
     Route::get('setting/joiningletter/', [SettingsController::class, 'index'])->name('get.joiningletter.language');
     Route::get('employee/pdf/{id}', [EmployeeController::class, 'joiningletterPdf'])->name('joiningletter.download.pdf');
     Route::get('employee/doc/{id}', [EmployeeController::class, 'joiningletterDoc'])->name('joininglatter.download.doc');

     //Experience Certificate
     Route::post('setting/exp/{lang?}', [SettingsController::class, 'experienceCertificateupdate'])->name('experiencecertificate.update');
     Route::get('setting/exp', [SettingsController::class, 'index'])->name('get.experiencecertificate.language');
     Route::get('employee/exppdf/{id}', [EmployeeController::class, 'ExpCertificatePdf'])->name('exp.download.pdf');
     Route::get('employee/expdoc/{id}', [EmployeeController::class, 'ExpCertificateDoc'])->name('exp.download.doc');

     //NOC
     Route::post('setting/noc/{lang?}', [SettingsController::class, 'NOCupdate'])->name('noc.update');
     Route::get('setting/noc', [SettingsController::class, 'index'])->name('get.noc.language');
     Route::get('employee/nocpdf/{id}', [EmployeeController::class, 'NocPdf'])->name('noc.download.pdf');
     Route::get('employee/nocdoc/{id}', [EmployeeController::class, 'NocDoc'])->name('noc.download.doc');

     //appricalStar
     Route::post('/appraisals', [AppraisalController::class, 'empByStar'])->name('empByStar')->middleware(['auth', 'XSS']);
     Route::post('/appraisals1', [AppraisalController::class, 'empByStar1'])->name('empByStar1')->middleware(['auth', 'XSS']);
     Route::post('/getemployee', [AppraisalController::class, 'getemployee'])->name('getemployee');

     //storage Setting
     Route::post('storage-settings', [SettingsController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware(['auth', 'XSS']);

     // Route by disma


     Route::get('active_holiday/{id}', [HolidayController::class, 'active_holiday'])->name('active_holiday');
     Route::get('inactive_holiday/{id}', [HolidayController::class, 'inactive_holiday'])->name('inactive_holiday');

     // Route::post('shift/store', [ShiftController::class, 'store'])->name('shift.store');
     // Route::get('shift/create', [ShiftController::class, 'create'])->name('shift.create');
     // Route::get('shift', [ShiftController::class, 'index'])->name('shift.index');
     Route::get('extra_leave', 'HomeController@extra_leave')->name('extra_leave');

     Route::resource('working-shifts', WorkingShiftsController::class)->middleware(
          [
               'auth',
               'XSS',
          ]
     );

     Route::post('leave/allow-to-edit-leave', [LeaveController::class, 'allowToEditLeave'])->name('leave.allow-to-edit-leave')->middleware(
          [
               'auth',
               'XSS',
          ]
     );


     // statutory deductions routes
     Route::get('statutory-deductions', [StatutoryDeductionsController::class, 'index'])->name('statutory-deductions');
     Route::post('statutory-deductions/epf', [StatutoryDeductionsController::class, 'storeEpf'])->name('statutory-deductions.epf');
     Route::post('statutory-deductions/professional-tax', [StatutoryDeductionsController::class, 'storeProfessionalTax'])->name('statutory-deductions.professional-tax');
     Route::post('statutory-deductions/esi', [StatutoryDeductionsController::class, 'statutoryDeductionsEsi'])->name('statutory-deductions.esi');
     // statutory deductions routes

     // multiple clock in out route
     Route::post('sync-clock-in-clock-out', 'API\UserAuthController@syncClockInClockOut')->name("sync-clock-in-clock-out");
     // multiple clock in out route

     // Employee Profile Edit
     Route::get('employee-profile-show/{id}', 'EmployeeController@show')->name('employeeProfileShow');
     Route::get('employee-profile-edit/{id}', 'EmployeeController@edit')->name('employeeProfileEdit');
     // Employee Profile Edit


     Route::get('attendance-import-sample-file-download', [AttendanceEmployeeController::class, 'attendanceImportSampleFileDownload'])->name('attendance-import-sample-file-download');


     Route::get('company-weekoff', [SettingsController::class, 'companyWeekoffShow'])->name('company-weekoff.show');
     Route::get('company-weekoff-employees-list', [SettingsController::class, 'companyWeekoffEmployeesList'])->name('company-weekoff-employees-list');
     Route::get('company-weekoff-create', [SettingsController::class, 'companyWeekoffCreate'])->name('company-weekoff.create');
     Route::post('company-weekoff-store', [SettingsController::class, 'companyWeekoffStore'])->name('company-weekoff.store');

     Route::post('get-departments', [AttendanceEmployeeController::class, 'getDepartments'])->name('get-departments');

     // User Impersonation Routes
     Route::get('impersonate', [ImpersonateController::class, 'index'])->name('impersonate.index');
     Route::get('impersonate/search', [ImpersonateController::class, 'search'])->name('impersonate.search');
     Route::get('impersonate/login/{id}', [ImpersonateController::class, 'impersonate'])->name('impersonate.login');
     Route::get('impersonate/exit', [ImpersonateController::class, 'exitImpersonation'])->name('impersonate.exit');
});
