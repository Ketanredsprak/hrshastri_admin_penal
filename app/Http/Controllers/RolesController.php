<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolesManagment;
use Illuminate\Support\Facades\Artisan;

class RolesController extends Controller
{
     public function index()
     {
          $data = RolesManagment::get();
          return view("roles.index", compact('data'));
     }

     public function create()
     {
          return view("roles.create");
     }

     public function store(request $request)
     {
          $validator = \Validator::make(
               $request->all(),
               [
                    'name' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }
          $data = new RolesManagment();
          $data->name   = $request->name;
          $data->status = 1;
          $data->save();

          Artisan::call('cache:clear');

          return redirect()->route('role.index')->with('success', 'Role successfully created.');
     }

     public function edit($id)
     {
          $data = RolesManagment::find($id);
          return view("roles.edit", compact('data'));
     }

     public function update(request $request, $id)
     {
          $validator = \Validator::make(
               $request->all(),
               [
                    'name' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }
          $data = RolesManagment::find($id);
          $data->name  = $request->name;
          $data->status = 1;
          $data->save();

          Artisan::call('cache:clear');

          return redirect()->route('role.index')->with('success', 'Role successfully updated.');
     }
}
