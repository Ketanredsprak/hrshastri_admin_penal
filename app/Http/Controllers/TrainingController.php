<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Imports\TrainingImport;
use App\Exports\TrainingExport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationModel;
use Carbon\Carbon;

class TrainingController extends Controller
{

     public function index()
     {
          if (\Auth::user()->can('Manage Training') || \Auth::user()->type == 'employee')
          {
               $trainings = Training::where('created_by', '=', \Auth::user()->creatorId())->get();
               $status    = Training::$Status;

               //st 102
               if (\Auth::user()->type != 'company')
               {
                    // echo Auth::user()->creatorId(); die("xit..104");
                    // echo Auth::id(); die("xit..103");
                    $employee     = Employee::where('user_id', Auth::id())->first();
                    // echo "<pre>"; print_r($employee); die("xit..105");
                    $trainings = Training::where('employee', '=', $employee->id)->get();
                    // die("xit..102");
               }
               //en 102
               return view('training.index', compact('trainings', 'status'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function create()
     {
          if (\Auth::user()->can('Create Training'))
          {
               // echo \Auth::user()->creatorId(); die("xit..101");
               $branches      = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               $trainingTypes = TrainingType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               $trainers      = Trainer::where('created_by', \Auth::user()->creatorId())->get()->pluck('firstname', 'id');
               $employees     = Employee::where('created_by', \Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');
               $options       = Training::$options;

               //st 101
               if (\Auth::user()->type != 'company')
               {
                    $users = User::where('id', Auth::id())->first();
                    $users_assign_employees = json_decode($users->assign_employees) ?? [];
                    $employees = Employee::whereIn('id', $users_assign_employees)->where("employee_exit", 0)->get()->pluck('name', 'id');
               }
               //en 101

               return view('training.create', compact('branches', 'trainingTypes', 'trainers', 'employees', 'options'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function store(Request $request)
     {

          if (\Auth::user()->can('Create Training'))
          {

               $validator = \Validator::make(
                    $request->all(),
                    [
                         'branch' => 'required',
                         'training_type' => 'required',
                         'training_cost' => 'required',
                         'employee' => 'required',
                         'start_date' => 'required',
                         'end_date' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               // dd("here",$request->employee);

               $training                 = new Training();
               $training->branch         = $request->branch;
               $training->trainer_option = $request->trainer_option;
               $training->training_type  = $request->training_type;
               $training->trainer        = $request->trainer;
               $training->training_cost  = $request->training_cost;
               $training->employee       = $request->employee;
               $training->start_date     = $request->start_date;
               $training->end_date       = $request->end_date;
               $training->description    = $request->description;
               $training->created_by     = \Auth::user()->creatorId();
               $training->save();

               //notification code st
               $employee = Employee::where('id', '=', $request->employee)->first();
               // echo "<pre>"; var_dump($employee); die("xit..101");
               /*$employee = Employee::where('user_id', '=', Auth::id())->first();
            $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get(); */

               $notification               = new NotificationModel();
               $notification->user_id      = $employee->user_id; // Auth::id(); //$row->id;
               $notification->employee_id  = $employee->id; // $employee->id;
               $notification->title        = "Your training has been schedule on " . $training->start_date . " ";
               $notification->details      = "Your training has been schedule on " . $training->start_date . " ";
               $notification->status       = 0;
               $notification->date         = Carbon::now();
               $notification->created_by   = Auth::user()->creatorId(); // $employee->created_by;
               $notification->redirect   = 'training';
               $notification->save();
               //notification code en

               return redirect()->route('training.index')->with('success', __('Training successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function show($id)
     {
          $traId       = Crypt::decrypt($id);
          $training    = Training::find($traId);
          $performance = Training::$performance;
          $status      = Training::$Status;

          return view('training.show', compact('training', 'performance', 'status'));
     }


     public function edit(Training $training)
     {
          if (\Auth::user()->can('Create Training'))
          {
               $branches      = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               $trainingTypes = TrainingType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
               $trainers      = Trainer::where('created_by', \Auth::user()->creatorId())->get()->pluck('firstname', 'id');
               $employees     = Employee::where('created_by', \Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');
               $options       = Training::$options;

               return view('training.edit', compact('branches', 'trainingTypes', 'trainers', 'employees', 'options', 'training'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function update(Request $request, Training $training)
     {
          if (\Auth::user()->can('Edit Training'))
          {

               $validator = \Validator::make(
                    $request->all(),
                    [
                         'branch' => 'required',
                         'training_type' => 'required',
                         'training_cost' => 'required',
                         'employee' => 'required',
                         'start_date' => 'required',
                         'end_date' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $training->branch         = $request->branch;
               $training->trainer_option = $request->trainer_option;
               $training->training_type  = $request->training_type;
               $training->trainer        = $request->trainer;
               $training->training_cost  = $request->training_cost;
               $training->employee       = $request->employee;
               $training->start_date     = $request->start_date;
               $training->end_date       = $request->end_date;
               $training->description    = $request->description;
               $training->save();

               return redirect()->route('training.index')->with('success', __('Training successfully updated.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }


     public function destroy(Training $training)
     {
          if (\Auth::user()->can('Delete Training'))
          {
               if ($training->created_by == \Auth::user()->creatorId())
               {
                    $training->delete();

                    return redirect()->route('training.index')->with('success', __('Training successfully deleted.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function export()
     {
          $name = 'training_' . date('Y-m-d i:h:s');
          $data = Excel::download(new TrainingExport(), $name . '.xlsx');


          return $data;
     }

     public function updateStatus(Request $request)
     {
          $training              = Training::find($request->id);
          $training->performance = $request->performance;
          $training->status      = $request->status;
          $training->remarks     = $request->remarks;
          $training->save();

          return redirect()->route('training.index')->with('success', __('Training status successfully updated.'));
     }
}
