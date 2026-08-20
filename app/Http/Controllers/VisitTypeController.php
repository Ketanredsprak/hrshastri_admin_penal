<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContractType;
use App\Models\VisitType;


class VisitTypeController extends Controller
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

    public function index(){
        $visitTypes = VisitType::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('visit_type.index')->with('visitTypes', $visitTypes);
    }

    public function create(){
        return view('visit_type.create');
    }
    
    public function store(Request $request){
        $validator = \Validator::make(
        $request->all(), [
                            'name' => 'required',
                        ]
        );
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            return redirect()->route('visit_type.index')->with('error', $messages->first());
        }
        $data = new VisitType();
        $data->name       = $request->name;
        $data->created_by = \Auth::user()->creatorId();
        $data->save();
        return redirect()->route('visit_type.index')->with('success', __('Grade Type successfully created!'));
    }
    
    public function show($id){
        //
    }

    
    public function edit($id){
        $data = VisitType::find($id);
        return view('visit_type.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $validator = \Validator::make(
            $request->all(), [
                                'name' => 'required',
                            ]
            );
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->route('visit_type.index')->with('error', $messages->first());
            }
            $data = VisitType::find($id);
            $data->name       = $request->name;
            $data->save();
            return redirect()->route('visit_type.index')->with('success', __('Grade Type successfully updated!'));
    }

    
    public function destroy($id){
        $data = VisitType::find($id);
        $data->delete();
        return redirect()->route('visit_type.index')->with('success', __('Grade Type successfully deleted!'));
    }
}
