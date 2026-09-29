<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use URL;
use File;
use Exception;
use Throwable;
use App\Models\NOC;
use App\Models\Plan;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Utility;
use App\Mail\UserCreate;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\JoiningLetter;
use App\Models\RolesManagment;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\GenerateOfferLetter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\ExperienceCertificate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
     public function index(Request $request)
     {
          // if ($request->has("debug"))
          // {
          // $user = User::where("id", 309)->first();

          // $uArr = [
          //      'email' => $user->email,
          //      'password' => $request->password,
          // ];

          // // $resp = Mail::to("milton@mailinator.com")->send(new TestMail());
          // $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $uArr);
          // dd("Out", $resp);
          // }

          $logged_in_user_id = Auth::user()->id;
          if (Auth::user()->can('Manage User'))
          {
               $user = Auth::user();

               if (Auth::user()->type == 'super admin')
               {
                    $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->orderBy('id', 'DESC')->get();
               }
               else
               {
                    $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'employee')->orderBy('id', 'DESC')->get();
                    // if (Auth::user()->type == 'company')
                    // {
                    //      $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'employee')->orderBy('id', 'DESC')->get();
                    // }
                    // else
                    // {
                    //      $users = User::where('id', Auth::id())->where('created_by', '=', $user->creatorId())->where('type', '!=', 'employee')->get();
                    // }
               }
               return view('user.index', compact('users', 'logged_in_user_id'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function get_users_data_this(request $request)
     {
          $users = User::where('created_by', '=', Auth::user()->creatorId())->where('roles_types', $request->data)->get();
          return $users;
     }

     public function create()
     {
          if (Auth::user()->can('Create User'))
          {
               $user  = Auth::user();

               $employees = Employee::where('user_id', '!=', $user->id)
                    ->where('created_by', Auth::user()->creatorId())
                    ->where("employee_exit", 0)
                    ->get();

               $userIds = $employees->pluck("user_id");

               $employeeuser = User::where('type', 'employee')->where('created_by', Auth::user()->creatorId())->whereIn("id", $userIds)->get();

               $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'employee')->get()->pluck('name', 'id');
               $plans = Plan::pluck('name', 'id')->toArray();
               return view('user.create', compact('roles', 'employees', 'employeeuser', 'plans'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {

          if (Auth::user()->type == 'super admin')
          {
               $billingMode = $request->input('billing_mode', 'global');
               $rules = [
                    'name' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                    'billing_mode' => 'required|in:global,custom',
               ];
               if ($billingMode === 'custom') {
                    $rules['price_per_employee'] = 'required|numeric|min:0';
                    $rules['price_per_user'] = 'required|numeric|min:0';
               } else {
                    $rules['plan_id'] = 'required|integer|exists:plans,id';
               }
               $validator = Validator::make($request->all(), $rules);
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'employee_id' => 'required',
                         'role' => 'required',
                    ]
               );
          }

          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }
          DB::beginTransaction();
          try
          {
               if (Auth::user()->can('Create User'))
               {
                    $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();

                    if (Auth::user()->type == 'super admin')
                    {
                         $date = date("Y-m-d H:i:s");
                         $billingMode = $request->input('billing_mode', 'global');
                         $isCustom = $billingMode === 'custom';

                         if ($isCustom) {
                              $plan = Plan::find(\App\Models\CompanyBillingProfile::CUSTOM_PLAN_ID);
                              if (!$plan) {
                                   DB::rollBack();
                                   return redirect()->back()->with('error', __('Custom billing plan (#9) was not found. Please create/update plan id 9 first.'));
                              }
                         } else {
                              $plan = Plan::find($request['plan_id']);
                              if (!$plan) {
                                   DB::rollBack();
                                   return redirect()->back()->with('error', __('Selected plan not found.'));
                              }
                              if ((int) $plan->id === \App\Models\CompanyBillingProfile::CUSTOM_PLAN_ID) {
                                   DB::rollBack();
                                   return redirect()->back()->with('error', __('Plan #9 is reserved for Custom Employee/User billing. Choose Custom billing mode instead.'));
                              }
                         }

                         $user = User::create(
                              [
                                   'name' => $request['name'],
                                   'email' => $request['email'],
                                   'password' => Hash::make($request['password']),
                                   'type' => 'company',
                                   'plan' => $plan->id,
                                   'lang' => !empty($default_language) ? $default_language->value : '',
                                   'created_by' => Auth::user()->id,
                                   'email_verified_at' => $date,
                                   'custom_max_users' => $isCustom ? -1 : null,
                                   'custom_max_employees' => $isCustom ? -1 : null,
                              ]
                         );

                         $assignPlan = $user->assignPlan($plan->id);
                         $price      = $plan->price;

                         \App\Models\CompanyBillingProfile::updateOrCreate(
                              ['company_id' => $user->id],
                              [
                                   'billing_mode' => $isCustom
                                        ? \App\Models\CompanyBillingProfile::MODE_CUSTOM
                                        : \App\Models\CompanyBillingProfile::MODE_GLOBAL,
                                   'price_per_employee' => $isCustom ? (float) $request->price_per_employee : null,
                                   'price_per_user' => $isCustom ? (float) $request->price_per_user : null,
                                   'invoice_plan_id' => $isCustom ? \App\Models\CompanyBillingProfile::CUSTOM_PLAN_ID : null,
                                   'manual_invoice_only' => $isCustom,
                              ]
                         );

                         // Re-apply unlimited caps after assignPlan for custom companies
                         if ($isCustom) {
                              $user->custom_max_users = -1;
                              $user->custom_max_employees = -1;
                              $user->save();
                         }

                         $user->userDefaultData();
                         $user->userDefaultDataRegister($user->id);
                         GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                         ExperienceCertificate::defaultExpCertificatRegister($user->id);
                         JoiningLetter::defaultJoiningLetterRegister($user->id);
                         NOC::defaultNocCertificateRegister($user->id);
                         Utility::jobStage($user->id);

                         $role_r = Role::findById(2);
                         $setings = Utility::settings();

                         if ($assignPlan['is_success'] == true && !empty($plan))
                         {
                              $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                              Order::create([
                                   'order_id' => $orderID,
                                   'name' => null,
                                   'email' => null,
                                   'card_number' => null,
                                   'card_exp_month' => null,
                                   'card_exp_year' => null,
                                   'plan_name' => $plan->name,
                                   'plan_id' => $plan->id,
                                   'price' => $isCustom ? 0 : $price,
                                   'price_currency' => !empty(env('CURRENCY_CODE')) ? env('CURRENCY_CODE') : 'usd',
                                   'txn_id' => '',
                                   'payment_type' => $isCustom
                                        ? __('Custom Employee/User Billing By Super Admin')
                                        : __('Manually Upgrade By Super Admin'),
                                   'payment_status' => 'succeeded',
                                   'receipt' => null,
                                   'user_id' => $user->id,
                              ]);
                         }

                         if ($setings['new_user'] == 1)
                         {
                              $uArr = [
                                   'email' => $user->email,
                                   'password' => $request->password,
                              ];
                              $resp = Utility::SuperadminsendEmailTemplate('new_user', [$user->id => $user->email], $uArr);
                         }
                    }
                    else
                    {

                         $validator        = Validator::make(
                              $request->all(),
                              [
                                   'employee_id' => 'required',
                                   'role'        => 'required',
                              ]
                         );
                         if ($validator->fails())
                         {
                              $messages = $validator->getMessageBag();
                              return redirect()->back()->with('error', $messages->first());
                         }

                         $checkUserId = 0;

                         if (Auth::user()->type == "company")
                         {
                              $checkUserId = Auth::user()->id;
                         }
                         else
                         {
                              $checkUserId = Auth::user()->created_by;
                         }

                         // Auth::user()->created_by

                          $objUser    = User::where("id", $checkUserId)->first();
                          $total_user = $objUser->countUsers();
                          $max_users  = $objUser->getMaxUsers();

                          if ($objUser->plan_expire_date && $objUser->plan_expire_date < date('Y-m-d'))
                          {
                               return redirect()->back()->with('error', __('Your subscription is overdue, Please upgrade plan.'));
                          }

                          if ($max_users == -1 || $total_user < $max_users)
                          {

                              $role_r = Role::findById($request->role);
                              $date = date("Y-m-d H:i:s");
                              // $user   = User::create(
                              //     [
                              //         'name'      => $request['name'],
                              //         'email'     => $request['email'],
                              //         'password'  => Hash::make($request['password']),
                              //         'type'      => $role_r->id,
                              //         'roles_types' => $role_r->id,
                              //         'lang'      => !empty($default_language) ? $default_language->value : '',
                              //         'created_by' => Auth::user()->id,
                              //         'email_verified_at' => $date,
                              //         'assign_user'=> $request->assign_user,
                              //     ]
                              // );
                              $user              = User::find($request->employee_id);
                              $user->type        = $role_r->name;
                              $user->roles_types = $role_r->name;
                              $user->assign_employees = $request->assign_employees;
                              $user->lang        = !empty($default_language) ? $default_language->value : '';
                              $user->save();
                              $user->roles()->detach();
                              $user->assignRole($role_r);
                         }
                         else
                         {
                              return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                         }
                    }

                    $setings = Utility::settings();
                    if ($setings['new_user'] == 1)
                    {
                         $uArr = [
                              'email' => $user->email,
                              'password' => $request->password,
                         ];
                         $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $uArr);
                         if (!empty($request->employees))
                         {
                              foreach ($request->employees as $emp)
                              {
                                   $employee = Employee::find($emp);
                                   $employee->users_types_id = $user->id;
                                   $employee->save();
                              }
                         }
                         DB::commit();
                         return redirect()->route('user.index')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                    }

                    DB::commit();
                    return redirect()->route('user.index')->with('success', __('User successfully created.'));
               }
               else
               {
                    return response()->json(['error' => __('Permission denied.')], 401);
               }
          }
          catch (\Exception $e)
          {
               DB::rollBack(); //Rollback Transaction
               return response()->json(['error' => __('something went wrong.')], 401);
          }
          catch (\Throwable $e)
          {
               DB::rollBack();
               return response()->json(['error' => __('something went wrong.')], 401);
          }
     }

     public function show(User $user)
     {
          return view('profile.index');
     }

     public function edit($id)
     {
          if (Auth::user()->can('Edit User'))
          {
               $user  = User::find($id);
               $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'employee')->get()->pluck('name', 'id');
               $selectemployees[] = 0;
               if (!empty($user->assign_employees))
               {
                    $selectemployees  = json_decode($user->assign_employees);
               }
               $employees  = Employee::where('user_id', '!=', $user->id)->where('created_by', Auth::user()->creatorId())->get();
               $plans = Plan::pluck('name', 'id')->toArray();
               $billingProfile = $user->billingProfile;
               return view('user.edit', compact('user', 'roles', 'employees', 'selectemployees', 'plans', 'billingProfile'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function update(Request $request, $id)
     {
          if (Auth::user()->type == 'super admin')
          {
               $billingMode = $request->input('billing_mode', 'global');
               $rules = [
                    'name' => 'required',
                    'billing_mode' => 'required|in:global,custom',
               ];
               if ($billingMode === 'custom') {
                    $rules['price_per_employee'] = 'required|numeric|min:0';
                    $rules['price_per_user'] = 'required|numeric|min:0';
               } else {
                    $rules['plan_id'] = 'required|integer|exists:plans,id';
               }
               $validator = Validator::make($request->all(), $rules);
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'name' => 'required',
                         'role' => 'required',
                    ]
               );
          }

          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }

          if (Auth::user()->type == 'super admin')
          {
               $user = User::findOrFail($id);
               $previousPlanId = $user->plan;
               $billingMode = $request->input('billing_mode', 'global');
               $isCustom = $billingMode === 'custom';

               if ($isCustom) {
                    $plan = Plan::find(\App\Models\CompanyBillingProfile::CUSTOM_PLAN_ID);
                    if (!$plan) {
                         return redirect()->back()->with('error', __('Custom billing plan (#9) was not found.'));
                    }
               } else {
                    $plan = Plan::find($request['plan_id']);
                    if (!$plan) {
                         return redirect()->back()->with('error', __('Selected plan not found.'));
                    }
               }

               $input = $request->only(['name']);
               $input['custom_max_users'] = ($request->has('custom_max_users') && $request->custom_max_users !== '' && $request->custom_max_users !== null)
                    ? (int) $request->custom_max_users
                    : ($isCustom ? -1 : null);
               $input['custom_max_employees'] = ($request->has('custom_max_employees') && $request->custom_max_employees !== '' && $request->custom_max_employees !== null)
                    ? (int) $request->custom_max_employees
                    : ($isCustom ? -1 : null);

               if ($isCustom) {
                    $input['custom_max_users'] = -1;
                    $input['custom_max_employees'] = -1;
               }

               $user->fill($input)->save();

               \App\Models\CompanyBillingProfile::updateOrCreate(
                    ['company_id' => $user->id],
                    [
                         'billing_mode' => $isCustom
                              ? \App\Models\CompanyBillingProfile::MODE_CUSTOM
                              : \App\Models\CompanyBillingProfile::MODE_GLOBAL,
                         'price_per_employee' => $isCustom ? (float) $request->price_per_employee : null,
                         'price_per_user' => $isCustom ? (float) $request->price_per_user : null,
                         'invoice_plan_id' => $isCustom ? \App\Models\CompanyBillingProfile::CUSTOM_PLAN_ID : null,
                         'manual_invoice_only' => $isCustom,
                    ]
               );

               if (!empty($plan) && (int) $previousPlanId !== (int) $plan->id)
               {
                    $assignPlan = $user->assignPlan($plan->id);
                    $price      = $plan->price;
                    if ($isCustom) {
                         $user->custom_max_users = -1;
                         $user->custom_max_employees = -1;
                         $user->save();
                    }
                    if ($assignPlan['is_success'] == true)
                    {
                         $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                         Order::create([
                              'order_id' => $orderID,
                              'name' => null,
                              'email' => null,
                              'card_number' => null,
                              'card_exp_month' => null,
                              'card_exp_year' => null,
                              'plan_name' => $plan->name,
                              'plan_id' => $plan->id,
                              'price' => $isCustom ? 0 : $price,
                              'price_currency' => !empty(env('CURRENCY_CODE')) ? env('CURRENCY_CODE') : 'usd',
                              'txn_id' => '',
                              'payment_type' => $isCustom
                                   ? __('Custom Employee/User Billing By Super Admin')
                                   : __('Manually Upgrade By Super Admin'),
                              'payment_status' => 'succeeded',
                              'receipt' => null,
                              'user_id' => $user->id,
                         ]);
                    }
               }

               if (!$isCustom && $request->filled('plan_expire_date'))
               {
                    $user->plan_expire_date = $request->plan_expire_date;
                    $user->save();
               }

               return redirect()->route('user.index')->with('success', __('Company details & capacity limits successfully updated.'));
          }
          else
          {

               // $user   = User::find($id);

               // $role   = Role::where('id',$request->role)->where('created_by', Auth::user()->creatorId())->first();

               // $input                = $request->all();
               // $input['type']        = $role->name;
               // $input['roles_types'] = $role->name;
               // $input['assign_employees'] = $request->assign_employees;

               // $user->fill($input)->save();

               // $user->roles()->detach();
               // $user->assignRole($role->name);

               $user           = User::findOrFail($id);
               $role          = Role::findById($request->role);
               $input         = $request->all();
               $input['type'] = $role->name;
               $user->fill($input)->save();

               $user->roles()->detach();

               $user->assignRole($role);
          }
          return redirect()->route('user.index')->with('success', 'User successfully updated.');
     }


     public function destroy($id)
     {
          try
          {
               if (Auth::user()->can('Delete User'))
               {
                    DB::beginTransaction();
                    $user = User::findOrFail($id);

                    Employee::where("users_types_id", $user->id)->update(["users_types_id" => NULL]);

                    $user->type = "employee";
                    $user->roles_types = "";
                    $user->plan_start_date = "";
                    $user->assign_user = "";
                    $user->assign_employees = "";
                    $user->save();

                    $user->roles()->detach();
                    $user->assignRole('Employee');

                    DB::commit();

                    // $user->delete();

                    return redirect()->route('user.index')->with('success', 'User successfully deleted.');
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Exception $e)
          {
               DB::rollBack();
               return redirect()->back()->with('error', __('something went worng.'));
          }
          catch (Throwable $e)
          {
               DB::rollBack();
               return redirect()->back()->with('error', __('something went worng.'));
          }
     }

     public function profile()
     {
          $userDetail = Auth::user();

          return view('user.profile')->with('userDetail', $userDetail);
     }

     public function editprofile(Request $request)
     {
          $userDetail = Auth::user();
          $user       = User::findOrFail($userDetail['id']);

          $validator = Validator::make(
               $request->all(),
               [
                    'name' => 'required|max:120',
                    // 'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    // 'profile' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();

               return redirect()->back()->with('error', $messages->first());
          }

          if ($request->hasFile('profile'))
          {
               $filenameWithExt = $request->file('profile')->getClientOriginalName();
               $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
               $extension       = $request->file('profile')->getClientOriginalExtension();
               $fileNameToStore = $filename . '_' . time() . '.' . $extension;


               $dir        = 'uploads/avatar';

               $image_path = $dir . $userDetail['avatar'];
               if (File::exists($image_path))
               {
                    File::delete($image_path);
               }
               $url = '';
               $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

               if ($path['flag'] == 1)
               {
                    $url = $path['url'];
               }
               else
               {
                    return redirect()->route('profile', Auth::user()->id)->with('error', __($path['msg']));
               }
          }

          if (!empty($request->profile))
          {
               $user['avatar'] = $fileNameToStore;
          }
          $user['name']  = $request['name'];
          // $user['email'] = $request['email'];
          $user->save();

          if (Auth::user()->type == 'employee')
          {
               $employee        = Employee::where('user_id', $user->id)->first();
               // $employee->email = $request['email'];
               // $employee->save();
          }

          return redirect()->back()->with(
               'success',
               'Profile successfully updated.'
          );
     }


     public function userPassword($id)
     {
          $eId        = Crypt::decrypt($id);

          $user = User::find($eId);

          $employee = User::where('id', $eId)->first();

          return view('user.reset', compact('user', 'employee'));
     }

     public function userPasswordReset(Request $request, $id)
     {
          $validator = Validator::make(
               $request->all(),
               [
                    'password' => 'required|confirmed|same:password_confirmation',
               ]
          );

          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();

               return redirect()->back()->with('error', $messages->first());
          }


          $user                 = User::where('id', $id)->first();
          $user->forceFill([
               'password' => Hash::make($request->password),
          ])->save();

          return redirect()->route('user.index')->with(
               'success',
               'User Password successfully updated.'
          );
     }


     public function updatePassword(Request $request)
     {
          if (Auth::Check())
          {
               $request->validate(
                    [
                         'current_password' => 'required',
                         'new_password' => 'required|min:6',
                         'confirm_password' => 'required|same:new_password',
                    ]
               );
               $objUser          = Auth::user();
               $request_data     = $request->All();
               $current_password = $objUser->password;
               if (Hash::check($request_data['current_password'], $current_password))
               {
                    $user_id            = Auth::User()->id;
                    $obj_user           = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->save();

                    return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
               }
               else
               {
                    return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
               }
          }
          else
          {
               return redirect()->route('profile', Auth::user()->id)->with('error', __('Something is wrong.'));
          }
     }


     public function upgradePlan($user_id)
     {
          $user = User::find($user_id);

          $plans = Plan::get();

          return view('user.plan', compact('user', 'plans'));
     }

     public function activePlan($user_id, $plan_id)
     {

          $user       = User::find($user_id);
          $assignPlan = $user->assignPlan($plan_id, true);
          $plan       = Plan::find($plan_id);
          if ($assignPlan['is_success'] == true && !empty($plan))
          {
               $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
               Order::create(
                    [
                         'order_id' => $orderID,
                         'name' => null,
                         'card_number' => null,
                         'card_exp_month' => null,
                         'card_exp_year' => null,
                         'plan_name' => $plan->name,
                         'plan_id' => $plan->id,
                         'price' => $plan->price,
                         'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : '$',
                         'txn_id' => '',
                         'payment_status' => 'succeeded',
                         'receipt' => null,
                         'user_id' => $user->id,
                    ]
               );

               return redirect()->back()->with('success', 'Plan successfully upgraded.');
          }
          else
          {
               return redirect()->back()->with('error', 'Plan fail to upgrade.');
          }
     }

     public function notificationSeen($user_id)
     {
          Notification::where('user_id', '=', $user_id)->update(['is_read' => 1]);
          return response()->json(['is_success' => true], 200);
     }

     public function inactive_user($id)
     {
          $url =  URL::previous();
          if (str_contains($url, 'user'))
          {
               $data = User::where('id', $id)->update(array('login_status' => 0));
          }
          else
          {
               $user_id = Employee::find($id);
               $data = User::where('id', $user_id->user_id)->update(array('login_status' => 0));
          }
          return redirect()->back()->with('success', 'User In-Active Successfully.');
     }

     public function active_user($id)
     {
          $url =  URL::previous();
          if (str_contains($url, 'user'))
          {
               $data = User::where('id', $id)->update(array('login_status' => 1));
          }
          else
          {
               $user_id = Employee::find($id);
               $data = User::where('id', $user_id->user_id)->update(array('login_status' => 1));
          }
          return redirect()->back()->with('success', 'User Active Successfully.');
     }
}
