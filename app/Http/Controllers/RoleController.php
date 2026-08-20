<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\PlansModule;
use Illuminate\Http\Request;
use App\Models\IsOptionModel;
use App\Models\RolesManagment;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
     public function index()
     {
          if (Auth::user()->can('Manage Role'))
          {
               $roles = Role::where('created_by', '=', Auth::user()->creatorId())->get();

               return view('role.index')->with('roles', $roles);
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Role'))
          {
               $user = Auth::user();
               if ($user->type == 'super admin')
               {
                    $modules = Module::whereIn('type', [0, 1])->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
                    // return $permissions;
               }
               elseif ($user->type == 'company')
               {
                    $planModule = PlansModule::where('plans_id', $user->plan)->pluck('modules_id')->toArray();
                    $modules = Module::whereIn('type', [0, 1])->whereIn('id', $planModule)->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
               }
               else
               {
                    $user = User::find($user->creatorId());
                    $planModule = PlansModule::where('plans_id', $user->plan)->pluck('modules_id')->toArray();
                    $modules = Module::whereIn('type', [0, 1])->whereIn('id', $planModule)->pluck('name', 'id')->toArray();
                    // $permissions = new Collection();
                    // foreach($user->roles as $role)
                    // {
                    //     $permissions = $permissions->merge($role->permissions);
                    // }
                    // $permissions = $permissions->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
               }

               $data = RolesManagment::get();
               return view('role.create', compact('permissions', 'data', 'modules'));
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function store(Request $request)
     {
          if (Auth::user()->can('Create Role'))
          {
               $role = Role::where('name', '=', $request->name)->where('created_by', Auth::user()->creatorId())->first();
               if (isset($role))
               {
                    return redirect()->back()->with('error', __('The Role has Already Been Taken.'));
               }
               else
               {

                    $this->validate(
                         $request,
                         [
                              'name' => 'required|max:100|unique:roles,name,NULL,id,created_by,' . Auth::user()->creatorId(),
                              'permissions' => 'required',
                         ]
                    );

                    $name             = $request['name'];
                    $role             = new Role();
                    $role->name       = $name;
                    // $role->name       = $request->name;
                    $role->created_by = Auth::user()->creatorId();
                    $permissions      = $request['permissions'];
                    $role->save();
                    //is option store
                    if (!empty($request->is_hr))
                    {

                         $isoption           = new IsOptionModel();
                         $isoption->role_id  = $role->id;
                         $isoption->name     = $request->is_hr;
                         $isoption->status   = 1;
                         $isoption->created_by = Auth::user()->creatorId();
                         $isoption->save();
                    }

                    foreach ($permissions as $permission)
                    {
                         $p    = Permission::where('id', '=', $permission)->firstOrFail();
                         $role = Role::where('name', '=', $name)->where('created_by', Auth::user()->creatorId())->first();
                         $role->givePermissionTo($p);
                    }

                    return redirect()->route('roles.index')->with('success', 'Role successfully created.');
               }
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function edit(Role $role)
     {
          if (Auth::user()->can('Edit Role'))
          {
               $user = Auth::user();
               $modules = Module::whereIn('type', [0, 1])->pluck('name', 'id')->toArray();


               if ($user->type == 'super admin')
               {
                    $modules = Module::whereIn('type', [0, 1])->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
               }
               elseif ($user->type == 'company')
               {
                    $planModule = PlansModule::where('plans_id', Auth::user()->plan)->pluck('modules_id')->toArray();
                    $modules = Module::whereIn('type', [0, 1])->whereIn('id', $planModule)->pluck('name', 'id')->toArray();
                    // $permissions = new Collection();
                    // foreach ($user->roles as $role1)
                    // {
                    //      $permissions = $permissions->merge($role1->permissions);
                    // }
                    // $permissions = $permissions->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
               }
               else
               {
                    $user = User::find(Auth::user()->creatorId());
                    $planModule = PlansModule::where('plans_id', $user->plan)->pluck('modules_id')->toArray();
                    $modules = Module::whereIn('type', [0, 1])->whereIn('id', $planModule)->pluck('name', 'id')->toArray();
                    // $permissions = new Collection();
                    // foreach ($user->roles as $role1)
                    // {
                    //      $permissions = $permissions->merge($role1->permissions);
                    // }
                    // $permissions = $permissions->pluck('name', 'id')->toArray();
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
               }

               // if ($user->type == 'super admin' || $user->type == 'company') {
               //      $permissions = Permission::all()->pluck('name', 'id')->toArray();
               // } else {
               //      $permissions = new Collection();
               //      foreach ($user->roles as $role1) {
               //           $permissions = $permissions->merge($role1->permissions);
               //      }
               //      $permissions = $permissions->pluck('name', 'id')->toArray();
               // }
               $data = RolesManagment::get();
               $is_option = IsOptionModel::where('name', 1)->where('role_id', $role->id)->where('created_by', Auth::user()->creatorId())->first();
               return view('role.edit', compact('role', 'permissions', 'data', 'is_option', 'modules'));
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function show_data($id)
     {
          $role = Role::find($id);
          if (Auth::user()->can('Edit Role'))
          {
               $user = Auth::user();
               $modules = Module::whereIn('type', [0, 1])->pluck('name', 'id')->toArray();
               if ($user->type == 'super admin' || $user->type == 'company')
               {
                    $permissions = Permission::all()->pluck('name', 'id')->toArray();
               }
               else
               {
                    $permissions = new Collection();
                    foreach ($user->roles as $role1)
                    {
                         $permissions = $permissions->merge($role1->permissions);
                    }
                    $permissions = $permissions->pluck('name', 'id')->toArray();
               }

               $is_option = IsOptionModel::where('name', 1)->where('role_id', $role->id)->where('created_by', Auth::user()->creatorId())->first();
               return view('role.show', compact('role', 'permissions', 'is_option', 'modules'));
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function update(Request $request, Role $role)
     {

          IsOptionModel::where('role_id', $role->id)->delete();

          if (Auth::user()->can('Edit Role'))
          {
               if ($role->name == 'employee')
               {
                    $this->validate(
                         $request,
                         [
                              'permissions' => 'required',
                         ]
                    );
               }
               else
               {
                    $this->validate(
                         $request,
                         [
                              'name' => 'required|max:100|unique:roles,name,' . $role['id'] . ',id,created_by,' . Auth::user()->creatorId(),
                              'permissions' => 'required',
                         ]
                    );
               }
               $input       = $request->except(['permissions']);
               $permissions = $request['permissions'];
               $role->fill($input)->save();

               $isoption           = new IsOptionModel();
               $isoption->role_id  = $role->id;
               $isoption->name     = $request->is_hr;
               $isoption->status   = 1;
               $isoption->created_by = Auth::user()->creatorId();
               $isoption->save();

               $p_all = Permission::all();



               foreach ($p_all as $p)
               {
                    $role->revokePermissionTo($p);
               }

               foreach ($permissions as $permission)
               {
                    $p = Permission::where('id', '=', $permission)->firstOrFail();
                    $role->givePermissionTo($p);
               }

               return redirect()->route('roles.index')->with('success', 'Role successfully updated.');
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function destroy(Role $role)
     {
          if (Auth::user()->can('Delete Role'))
          {
               $role->delete();

               return redirect()->route('roles.index')->with(
                    'success',
                    'Role successfully deleted.'
               );
          }
          else
          {
               return redirect()->back()->with('error', 'Permission denied.');
          }
     }

     public function inactive_role($id)
     {
          $data = Role::where('id', $id)->update(array('activation_status' => 0));
          return redirect()->back()->with('success', 'User In-Active Successfully.');
     }

     public function active_role($id)
     {
          $data = Role::where('id', $id)->update(array('activation_status' => 1));
          return redirect()->back()->with('success', 'User Active Successfully.');
     }
}
