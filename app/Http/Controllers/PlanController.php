<?php

namespace App\Http\Controllers;

use File;
use Exception;
use Throwable;
use App\Models\Plan;
use App\Models\User;
use App\Models\Module;
use App\Models\Utility;
use App\Models\PlanRequest;
use App\Models\PlansModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function index()
    {
        // Companies must always be able to view plans (renew/activate), even without "Manage Plan"
        // and even when subscription is expired (Isactive/CheckPlan redirects them here).
        $user = Auth::user();
        if ($user->can('Manage Plan') || $user->type == 'company') {
            $plans                 = Plan::get();
            $admin_payment_setting = Utility::getAdminPaymentSetting();

            $activeEmployees = 0;
            $activeUsers     = 0;
            $suggestedPlan   = null;

            if ($user->type == 'company' || $user->type != 'super admin') {
                $companyUser     = ($user->type == 'company') ? $user : User::find($user->creatorId());
                if ($companyUser) {
                    $activeEmployees = $companyUser->countEmployees();
                    $activeUsers     = $companyUser->countUsers();
                    $suggestedPlan   = $companyUser->getSuggestedPlan();
                }
            }

            return view('plan.index', compact('plans', 'admin_payment_setting', 'activeEmployees', 'activeUsers', 'suggestedPlan'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (Auth::user()->can('Create Plan')) {
            $arrDuration = Plan::$arrDuration;
            $modules = Module::whereIn('type', [0, 1])->pluck('name', 'id')->toArray();
            return view('plan.create', compact('arrDuration', 'modules'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (Auth::user()->can('Create Plan')) {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            if (!empty($admin_payment_setting) && ($admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' || $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' || $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on')) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|unique:plans',
                        'price' => 'required|numeric|min:0',
                        'duration' => 'required',
                        'max_users' => 'required|numeric',
                        'max_employees' => 'required|numeric',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                try {
                    DB::beginTransaction();
                    $post = $request->all();
                    $modules = Module::whereIn('id', $post['modules'])->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();

                    $roles = Role::create([
                        'name' => 'Role ' . $plan->name,
                        'created_by' => Auth::user()->creatorId(),
                    ]);
                
                    $post['role_id'] = $roles->id;
                    $plan = Plan::create($post);


                    $permission_ids = [];
                    foreach ($modules as $key => $module) {
                        PlansModule::create([
                            'plans_id' => $plan->id,
                            'modules_id' => $key,
                        ]);
                        if (in_array('Manage ' . $module, (array) $permissions)) {
                            if ($key = array_search('Manage ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Create ' . $module, (array) $permissions)) {
                            if ($key = array_search('Create ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Edit ' . $module, (array) $permissions)) {
                            if ($key = array_search('Edit ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Delete ' . $module, (array) $permissions)) {
                            if ($key = array_search('Delete ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Show ' . $module, (array) $permissions)) {
                            if ($key = array_search('Show ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Move ' . $module, (array) $permissions)) {
                            if ($key = array_search('Move ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Client Permission ' . $module, (array) $permissions)) {
                            if ($key = array_search('Client Permission ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Invite User ' . $module, (array) $permissions)) {
                            if ($key = array_search('Invite User ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Buy ' . $module, (array) $permissions)) {
                            if ($key = array_search('Buy ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                        if (in_array('Add ' . $module, (array) $permissions)) {
                            if ($key = array_search('Add ' . $module, $permissions)) {
                                $permission_ids[] = $key;
                            }
                        }
                    }

                    

                    // $plan->update([
                    //     'role_id' => $roles->id
                    // ]);

                    $roles->syncPermissions($permission_ids);

                    DB::commit();
                    if ($plan) {
                        return redirect()->back()->with('success', __('Plan Successfully created.'));
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('error', showExceptionMessage($e));
                } catch (Throwable $e) {
                    DB::rollBack();
                    return redirect()->back()->with('error', showExceptionMessage($e));
                }
            } else {
                return redirect()->back()->with('error', __('Please set stripe/paypal api key & secret key for add new plan'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($plan_id)
    {
        if (Auth::user()->can('Edit Plan')) {
            $arrDuration = Plan::$arrDuration;
            $plan        = Plan::find($plan_id);

            $planModule = PlansModule::where('plans_id', $plan->id)->pluck('modules_id')->toArray();
            $role = Role::find($plan->role_id);
            $modules = Module::whereIn('type', [0, 1])->pluck('name', 'id')->toArray();

            return view('plan.edit', compact('plan', 'arrDuration', 'modules', 'role', 'planModule'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $plan_id)
    {
        if (Auth::user()->can('Edit Plan')) {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            if (!empty($admin_payment_setting) &&  ($admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' || $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' || $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on')) {
                $plan = Plan::find($plan_id);
                if (!empty($plan)) {
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'name' => 'required|unique:plans,name,' . $plan_id,
                            'duration' => 'required',
                            'max_users' => 'required|numeric',
                            'max_employees' => 'required|numeric',
                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();
                        return redirect()->back()->with('error', $messages->first());
                    }
                    try {
                        DB::beginTransaction();

                        $post = $request->all();

                        $modules = Module::whereIn('id', $post['modules'])->pluck('name', 'id')->toArray();

                        $permissions = Permission::all()->pluck('name', 'id')->toArray();
                        $permission_ids = [];
                        foreach ($modules as $key => $module) {
                            PlansModule::updateOrCreate([
                                'plans_id' => $plan->id,
                                'modules_id' => $key,
                            ], [
                                'plans_id' => $plan->id,
                                'modules_id' => $key,
                            ]);
                            if (in_array('Manage ' . $module, (array) $permissions)) {
                                if ($key = array_search('Manage ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Create ' . $module, (array) $permissions)) {
                                if ($key = array_search('Create ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Edit ' . $module, (array) $permissions)) {
                                if ($key = array_search('Edit ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Delete ' . $module, (array) $permissions)) {
                                if ($key = array_search('Delete ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Show ' . $module, (array) $permissions)) {
                                if ($key = array_search('Show ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Move ' . $module, (array) $permissions)) {
                                if ($key = array_search('Move ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Client Permission ' . $module, (array) $permissions)) {
                                if ($key = array_search('Client Permission ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Invite User ' . $module, (array) $permissions)) {
                                if ($key = array_search('Invite User ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Buy ' . $module, (array) $permissions)) {
                                if ($key = array_search('Buy ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                            if (in_array('Add ' . $module, (array) $permissions)) {
                                if ($key = array_search('Add ' . $module, $permissions)) {
                                    $permission_ids[] = $key;
                                }
                            }
                        }

                        $roles = Role::find($plan->role_id);
                        if(empty($roles)){
                            $roles = Role::create([
                                'name' => 'Role ' . $plan->name,
                                'created_by' => Auth::user()->creatorId(),
                            ]);
                        }
                        $roles->syncPermissions($permission_ids);

                        $plans = $plan->update($post);

                        DB::commit();
                        if ($plans) {
                            return redirect()->back()->with('success', __('Plan successfully updated.'));
                        } else {
                            return redirect()->back()->with('error', __('Something is wrong.'));
                        }
                    } catch (Exception $e) {
                        DB::rollBack();
                        return redirect()->back()->with('error', showExceptionMessage($e));
                    } catch (Throwable $e) {
                        DB::rollBack();
                        return redirect()->back()->with('error', showExceptionMessage($e));
                    }
                } else {
                    return redirect()->back()->with('error', __('Plan not found.'));
                }
            } else {
                return redirect()->back()->with('error', __('Please set stripe/paypal api key & secret key for add new plan'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $user = User::where('id', '=',  $user->id)->first();
            $user->requested_plan = "0";
            $user->save();

            $plan = Plan::findOrFail($id);
            PlanRequest::where('plan_id', $plan->id)->where('user_id', '=',  $user->id)->delete();

            DB::commit();
            return redirect()->route('plans.index')->with('success', 'Plan request successfully deleted.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('plans.index')->with('error', showExceptionMessage($e));
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->route('plans.index')->with('error', showExceptionMessage($e));
        }
    }

    public function plan_request($code)
    {
        try {
            DB::beginTransaction();

            $objUser = Auth::user();

            $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
            $plan    = Plan::find($plan_id);

            $plan_request_check_user = PlanRequest::where('user_id', '=', $objUser->id)->first();

            if ($plan_request_check_user) {
                return redirect()->back()->with('error', __('you already request sended for plan.'));
            } else {
                $planRequest = new PlanRequest();
                $planRequest['user_id'] = $objUser->id;
                $planRequest['plan_id'] = $plan->id;
                $planRequest['duration'] = $plan->duration;
                $planRequest->save();

                $objUser['requested_plan'] = $plan->id;
                $objUser->save();

                DB::commit();
                return redirect()->back()->with('success', __('Plan request successfully sended.'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', showExceptionMessage($e));
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', showExceptionMessage($e));
        }
    }


    public function userPlan(Request $request)
    {
        // Company accounts must be able to activate free plans even without "Buy Plan" permission
        if (Auth::user()->can('Buy Plan') || Auth::user()->type == 'company') {
            try {
                DB::beginTransaction();
                $objUser = Auth::user();
                $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
                $plan    = Plan::find($planID);
                if ($plan) {
                    if ($plan->price <= 0) {
                        $objUser->assignPlan($plan->id);

                        DB::commit();
                        return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Plan not found.'));
                }
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', showExceptionMessage($e));
            } catch (Throwable $e) {
                DB::rollBack();
                return redirect()->back()->with('error', showExceptionMessage($e));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
