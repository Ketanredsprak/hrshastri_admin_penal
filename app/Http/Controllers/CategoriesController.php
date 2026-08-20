<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Mail\TransferSend;
use App\Models\Transfer;
use App\Models\Utility;
use App\Models\VisitType;
use App\Models\GradeType;
use App\Models\Categories;
use App\Models\CategoriesType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class CategoriesController extends Controller
{
    public function index(){
        $Categories = Categories::with('CategorytypeData','visittypeData','gradetypeData')->where('created_by', '=', \Auth::user()->creatorId())->where('soft_delete',null)->get();
        return view('categories.index', compact('Categories'));
    }

    public function create(){
        $CategoriesTypes    = CategoriesType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments        = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $gradetypes         = GradeType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $visittypes         = VisitType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        return view('categories.create', compact('visittypes', 'departments', 'gradetypes','CategoriesTypes'));
    }

    public function store(Request $request){
        
        $validator = \Validator::make(
            $request->all(),
            [
                'visit_type'  => 'required',
                'grade_type'  => 'required',
                'category_type' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['status' => 0,'message' => $messages]);
        }
        
        if(!empty($request->amount)){
            $validator = \Validator::make(
                $request->all(),
                [
                    'option_select'        => 'required',
                ],[
                    'option_select'  => 'Please any one option Per kilometer rate / Fixed',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 0,'message' => $messages]);
            }
        }

        $data = new Categories();
        $data->visit_type_id = $request->visit_type;
        $data->grade_type_id = $request->grade_type;
        $data->category_id   = $request->category_type;
        $data->amount        = $request->amount;
        $data->amount_status = $request->option_select;
        $data->status        = 1;
        $data->description   = $request->description;
        $data->created_by    = \Auth::user()->creatorId();
        $data->save();
        return response()->json(['status' => 1,'message' => __('Employee successfully created.')]);
    }

    public function show($id){
        //
    }

    public function edit($id){
        $CategoriesTypes    = CategoriesType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $gradetypes    = GradeType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $visittypes    = VisitType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $data = Categories::where('created_by', '=', \Auth::user()->creatorId())->find($id);
        return view('categories.edit', compact('visittypes', 'data', 'gradetypes','CategoriesTypes'));
    }

    public function update(Request $request, $id){
        $validator = \Validator::make(
            $request->all(),
            [
                'visit_type'  => 'required',
                'grade_type'  => 'required',
                'category_type' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['status' => 0,'message' => $messages]);
        }
        
        if(!empty($request->amount)){
            $validator = \Validator::make(
                $request->all(),
                [
                    'option_select'        => 'required',
                ],[
                    'option_select'  => 'Please any one option Per kilometer rate / Fixed',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 0,'message' => $messages]);
            }
        }
        $data = Categories::find($id);
        $data->visit_type_id = $request->visit_type;
        $data->grade_type_id = $request->grade_type;
        $data->category_id   = $request->category_type;
        $data->amount        = $request->amount;
        $data->amount_status = $request->option_select;
        $data->description   = $request->description;
        $data->save();
        return response()->json(['status' => 1,'message' => __('Employee successfully updated.')]);
    }

    public function destroy($id){
        $data = Categories::find($id);
        $data->soft_delete = 1;
        $data->save();
        return redirect()->route('categories.index')->with('success', __('Categorie successfully deleted.'));
    }
}
