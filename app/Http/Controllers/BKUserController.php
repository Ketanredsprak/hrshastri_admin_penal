<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Invoice;
use App\Mail\UserCreate;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Plan;
use App\Models\RolesManagment;
use App\Models\User;
use App\Models\Utility;
use App\Models\GenerateOfferLetter;
use App\Models\JoiningLetter;
use App\Models\ExperienceCertificate;
use App\Models\NOC;
use File;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('Manage User')) {
            $user = \Auth::user();
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->orderBy('id', 'DESC')->get();
            } else {
                if(Auth::user()->type == 'company'){
                    $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'employee')->orderBy('id', 'DESC')->get();
                }else{
                    $users = User::where('id',Auth::id())->where('created_by', '=', $user->creatorId())->where('type', '!=', 'employee')->get();
                }
            }
            return view('user.index', compact('users'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function get_users_data_this(request $request){
        
        $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('roles_types',$request->data)->get();
        return $users; 
    }

    public function create()
    {
        if(\Auth::user()->can('Create User')) {
            $user  = \Auth::user();

            $employees = Employee::where('created_by', \Auth::user()->creatorId())->get();

            $roles = Role::where('activation_status',1)->where('created_by', '=', $user->creatorId())->where('name', '!=', 'employee')->get()->pluck('name', 'id');
            
            return view('user.create', compact('roles','employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        
        
       
        
        
        $employeeData = Employee::find($request->employee_id);
        // dd($employeeData->user_id);
        $uArr = [
            'email'   => $request->email,
            'password' => $request->password,
        ];
        if (\Auth::user()->can('Create User')) {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            // $validator        = \Validator::make(
            //     $request->all(),
            //     [
            //         'name' => 'required',
            //         'email' => 'required|unique:users',
            //         'password' => 'required',
            //     ]
            // );
            // if ($validator->fails()) {
            //     $messages = $validator->getMessageBag();

            //     return redirect()->back()->with('error', $messages->first());
            // }

            if (\Auth::user()->type == 'super admin')
            {
                $date = date("Y-m-d H:i:s");
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make($request['password']),
                        'type' => 'company',
                        'plan' => $plan = Plan::where('price', '<=', 0)->first()->id,
                        'lang' => !empty($default_language) ? $default_language->value : '',
                        'created_by' => \Auth::user()->id,
                        'email_verified_at' => $date,
                        'assign_user'       => $request->assign_user,
                    ]
                );

                $user->assignRole('Company');
                $user->userDefaultData();
                $user->userDefaultDataRegister($user->id);
                GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                ExperienceCertificate::defaultExpCertificatRegister($user->id);
                JoiningLetter::defaultJoiningLetterRegister($user->id);
                NOC::defaultNocCertificateRegister($user->id);
                Utility::jobStage($user->id);
                $role_r = Role::findById(2);
                $setings = Utility::settings();
                
                if ($setings['new_user'] == 1) {

                    $uArr = [
                        'email' => $user->email,
                        'password' => $request->password,
                    ];

                    $resp = Utility::SuperadminsendEmailTemplate('new_user', [$user->id => $user->email], $uArr);
                }
            }else {
                $objUser    = \Auth::user();
                $total_user = $objUser->countUsers();
                $plan       = Plan::find($objUser->plan);
                if($objUser->plan_expire_date <= date('Y-m-d')){
                    return redirect()->back()->with('error', __('Your subscription is overdue, Please upgrade plan.'));
                }
                if ($total_user < $plan->max_users || $plan->max_users == -1) {

                    // $role_r = Role::findById($request->role);

                    $role_r  = Role::where('name',$request->role)->where('created_by', \Auth::user()->creatorId())->first();
                   
                    $rolename = RolesManagment::find($request->role);
                    // dd($rolename);
                    $date = date("Y-m-d H:i:s");
                    // $user   = User::create(
                    //     [
                    //         'name'      => $request['name'],
                    //         'email'     => $request['email'],
                    //         'password'  => Hash::make($request['password']),
                    //         'type'      => $role_r->id,
                    //         'roles_types' => $role_r->id,
                    //         'lang'      => !empty($default_language) ? $default_language->value : '',
                    //         'created_by' => \Auth::user()->id,
                    //         'email_verified_at' => $date,
                    //         'assign_user'=> $request->assign_user,
                    //     ]
                    // );
                    $user              = User::find($employeeData->user_id);

                    $user->type        = $request->role;

                    $user->roles_types = $request->role;

                    $user->lang        = !empty($default_language) ? $default_language->value : '';
                    $user->save();
                    
                    $user->roles()->detach();
                    $user->assignRole($rolename->name);
                
                } else {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }

            $setings = Utility::settings();

            
            if ($setings['new_user'] == 1) {

                $uArr = [
                    'email' => $user->email,
                    'password' => $request->password,
                ];

                $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $uArr);
                
                if(!empty($request->employees)){
                    foreach($request->employees as $emp){
                        $employee = Employee::find($emp);
                        $employee->users_types_id = $user->id;
                        $employee->save();
                    }   
                }
                return redirect()->route('user.index')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->route('user.index')->with('success', __('User successfully created.'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function show(User $user)
    {
        return view('profile.index');
    }

    public function edit($id)
    {
        
        if (\Auth::user()->can('Edit User')) {
            $user  = User::find($id);

            $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'employee')->get()->pluck('name', 'id');
            
            $employees  = Employee::where('users_types_id',null)->where('created_by', \Auth::user()->creatorId())->get();
            $selectemployees  = Employee::where('users_types_id',$id)->where('created_by', \Auth::user()->creatorId())->get();
            
            $merged = $selectemployees->merge($employees);

            $employees = $merged->all();
            
            return view('user.edit', compact('user', 'roles','employees','selectemployees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        
        Employee::where("users_types_id", $id)->update(["users_types_id" => null]);
        
        $validator = \Validator::make(
            $request->all(),[
                'name' => 'required',
                'email' => 'unique:users,email,' . $id,
            ]
        );
        if($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if(\Auth::user()->type == 'super admin') {
            $user  = User::findOrFail($id);
            $input = $request->all();
            $user->fill($input)->save();
        } else {
            $user = User::findOrFail($id);
            $role          = Role::where('name',$request->role)->where('created_by', \Auth::user()->creatorId())->first();
            $rolename       = RolesManagment::find($role->name);
            $input                = $request->all();
            $input['type']        = $request->role;
            $input['roles_types'] = $request->role;
            $user->fill($input)->save();

            $user->roles()->detach();
            $user->assignRole($role->id);
            if(!empty($request->employees)){
                foreach($request->employees as $emp){
                    $employee = Employee::find($emp);
                    $employee->users_types_id = $user->id;
                    $employee->save();
                }   
            }
        }
        return redirect()->route('user.index')->with('success', 'User successfully updated.');
    }
     

    public function destroy($id)
    {
        if (\Auth::user()->can('Delete User')) {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user.index')->with('success', 'User successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function profile()
    {
        $userDetail = \Auth::user();

        return view('user.profile')->with('userDetail', $userDetail);
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                'profile' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;


            $dir        = 'uploads/avatar';

            $image_path = $dir . $userDetail['avatar'];
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $url = '';
            $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('profile', \Auth::user()->id)->with('error', __($path['msg']));
            }
        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        if (\Auth::user()->type == 'employee') {
            $employee        = Employee::where('user_id', $user->id)->first();
            $employee->email = $request['email'];
            $employee->save();
        }

        return redirect()->back()->with(
            'success',
            'Profile successfully updated.'
        );
    }


    public function userPassword($id)
    {
        $eId        = \Crypt::decrypt($id);

        $user = User::find($eId);

        $employee = User::where('id', $eId)->first();

        return view('user.reset', compact('user', 'employee'));
    }

    public function userPasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
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
        if (\Auth::Check()) {
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
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
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
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if ($assignPlan['is_success'] == true && !empty($plan)) {
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
        } else {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }
    }

    public function notificationSeen($user_id)
    {
        Notification::where('user_id', '=', $user_id)->update(['is_read' => 1]);

        return response()->json(['is_success' => true], 200);
    }

    public function inactive_user($id){
        $url =  URL::previous() ;
        if (str_contains($url, 'user')) {
            $data = User::where('id',$id)->update(array('login_status'=>0));
        }
        else{
            $user_id = Employee::find($id);
            $data = User::where('id',$user_id->user_id)->update(array('login_status'=>0));
        }
        return redirect()->back()->with('success','User In-Active Successfully.');

    }

    public function active_user($id){
        $url =  URL::previous() ;
        if (str_contains($url, 'user')) {
            $data = User::where('id',$id)->update(array('login_status'=>1));
        }
        else{
            $user_id = Employee::find($id);
            $data = User::where('id',$user_id->user_id)->update(array('login_status'=>1));
        }
        return redirect()->back()->with('success','User Active Successfully.');

    }
}
