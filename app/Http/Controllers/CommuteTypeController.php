<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommuteType;
use App\Models\GradeType;

class CommuteTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
        $datas = CommuteType::with('grade')->where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('commutingType.index')->with('datas', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
       
    //    $gradeTypes = GradeType::where('created_by', '=', \Auth::user()->creatorId())->get();
        // return view('commutingType.create')->with('grade', $gradeTypes);
        return view('commutingType.create');
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
        // return $request;
        $validator = \Validator::make(
            $request->all(), [
                    'name' => 'required',
                ]
            );
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->route('commuting_type.index')->with('error', $messages->first());
            }
            $data = new CommuteType();
            $data->name       = $request->name;
            $data->status       = 1;
            $data->created_by = \Auth::user()->creatorId();
            $data->save();
            return redirect()->route('commuting_type.index')->with('success', __('Commute Type successfully created!'));
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
        // $grade= GradeType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $data = CommuteType::find($id);
        return view('commutingType.edit', compact('data'));
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
            $request->all(), [
                        'name' => 'required',
                        // 'visit_type' => 'required',
                        
                    ]
            );
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                return redirect()->route('commuting_type.index')->with('error', $messages->first());
            }
            $data = CommuteType::find($id);
            $data->name = $request->name;
            // $data->visit_type       = $request->visit_type;
            $data->update();
            return redirect()->route('commuting_type.index')->with('success', __('Commuting Type successfully updated!'));

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
        $data = CommuteType::find($id);
        $data->delete();
        return redirect()->route('commuting_type.index')->with('success', __('Commut    ing Type successfully deleted!'));
    }

    public function commuting_active($id){
        $data = CommuteType::find($id);
        $data->status = '1';
        $data->save();
        return redirect()->route('commuting_type.index')->with('success', __('Commuting Type status active successfully.'));
    }

    public function commuting_in_active($id){

        $data = CommuteType::find($id);
        $data->status = '0';
        $data->save();

        return redirect()->route('commuting_type.index')->with('success', __('Commuting Type status in-active successfully.'));
    }
}
