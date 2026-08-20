<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CityTier;
use DB;


class CityTierController extends Controller
{

     public function __construct()
     {
          $this->middleware(
               [
                    'auth',
                    'XSS',
               ]
          );
     }

     public function index()
     {
          $citys = CityTier::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
          return view('citytier.index')->with('citys', $citys);
     }

     public function create()
     {
          $cities = DB::table('city')->get();
          return view('citytier.create')->with('citys', $cities);
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
          //
          // return  $request;
          $validator = \Validator::make(
               $request->all(),
               [
                    'name' => 'required',
                    'citys_id' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->route('city_tier.index')->with('error', $messages->first());
          }
          $data = new CityTier();
          $data->name  = $request->name;
          $data->citysid  = $request->citys_id;
          $data->created_by = \Auth::user()->creatorId();
          $data->save();

          return redirect()->route('city_tier.index')->with('success', __('City Tier successfully created!'));
     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
          //
     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
          //
          $data = CityTier::find($id);
          $citys = DB::table('city')->get();
          return view('citytier.edit', compact('data', 'citys'));
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
          //
          $validator = \Validator::make(
               $request->all(),
               [
                    'name' => 'required',
                    'citys_id' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->route('city_tier.index')->with('error', $messages->first());
          }
          $data = CityTier::find($id);
          $data->name  = $request->name;
          $data->citysid  = $request->citys_id;
          $data->update();

          return redirect()->route('city_tier.index')->with('success', __('City Tier successfully updated!'));
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
          //
          $data = CityTier::find($id);
          $data->delete();
          return redirect()->route('city_tier.index')->with('success', __('City successfully deleted!'));
     }
}
