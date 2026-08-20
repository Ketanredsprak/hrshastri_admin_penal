<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\InterviewSchedule as LocalInterviewSchedule;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobStage;
use App\Models\User;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Throwable;
use Spatie\Permission\Models\Role;

class InterviewScheduleController extends Controller
{

     public function index()
     {

          $sdf = '';
          $schedules   = LocalInterviewSchedule::where('created_by', \Auth::user()->creatorId())->get();
          $arrSchedule = [];
          $today_date = date('m');
          //$current_month_event = LocalInterviewSchedule::where('employee',\Auth::id())->select('id', 'candidate', 'date', 'employee', 'time', 'comment')->whereNotNull(['date'])->whereMonth('date', $today_date)->get(); // commented by nirav
          $current_month_event = LocalInterviewSchedule::where('created_by', \Auth::user()->creatorId())
               ->whereNotNull(['date'])->whereMonth('date', $today_date)->get();

          // dd($schedules,$current_month_event);
          foreach ($schedules as $key => $schedule)
          {
               $arr['id']     = $schedule['id'];
               $arr['title']  = !empty($schedule->applications) ? !empty($schedule->applications->jobs) ? $schedule->applications->jobs->title : '' : '';
               $arr['start']  = $schedule['date'];
               $arr['url']    = route('interview-schedule.show', $schedule['id']);
               $arr['className'] = ' event-primary';
               $arrSchedule[] = $arr;
               $sdf = !empty($current_month_event[$key]->applications) ? (!empty($current_month_event[$key]->applications->jobs) ? $current_month_event[$key]->applications->jobs->title : '') : '';
          }

          $arrSchedule = json_encode($arrSchedule);

          return view('interviewSchedule.index', compact('arrSchedule', 'schedules', 'current_month_event', 'sdf'));
     }

     public function create($jobApplicationId = 0)
     {
          try
          {
               $interviewJobsList = LocalInterviewSchedule::where('created_by', \Auth::user()->creatorId())->whereNotNull(['candidate_job_application_ids'])->get()->pluck("candidate_job_application_ids");

               // Flatten the array
               $allCandidateIds = $interviewJobsList->flatten()->map(function ($id)
               {
                    return trim($id, '[]"'); // Remove the brackets and quotes if needed
               })->all();

               // Optionally, you can convert the IDs to integers if needed
               $allCandidateIds = array_map('intval', $allCandidateIds);

               $employees = User::where('created_by', \Auth::user()->creatorId())->orderBy('name', 'ASC')->get()->pluck('name', 'id');

               $candidates = JobApplication::where('created_by', \Auth::user()->creatorId())->whereNotIn("id", $allCandidateIds)->get()->pluck('name', 'id');

               $selectJobApplication = JobApplication::where("id", $jobApplicationId)->where('created_by', \Auth::user()->creatorId())->first();
               return view('interviewSchedule.create', compact('employees', 'candidates', 'selectJobApplication'));
          }
          catch (Exception | Throwable $e)
          {
               return redirect()->back()->with('error', showExceptionMessage($e));
          }
     }

     public function store(Request $request)
     {
          if (\Auth::user()->can('Create Interview Schedule'))
          {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         'candidate_job_application_ids' => 'required',
                         'interviewer_ids' => 'required',
                         'date' => 'required',
                         'time' => 'required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               DB::beginTransaction();

               $candidate_job_application_ids = $request->candidate_job_application_ids ?? [];
               $interviewer_ids = $request->interviewer_ids ?? [];

               $schedule = new LocalInterviewSchedule();

               $schedule->candidate_job_application_ids  = json_encode($candidate_job_application_ids);
               $schedule->interviewer_ids   = json_encode($interviewer_ids);

               $schedule->candidate       = 0;
               $schedule->employee       = 0;
               $schedule->date       = $request->date;
               $schedule->time       = $request->time;
               $schedule->comment    = $request->comment;
               $schedule->created_by = \Auth::user()->creatorId();

               $schedule->save();

               DB::commit();

               $comments = $request->comment ?? "";

               // Google Celander
               if ($request->get('synchronize_type')  == 'google_calender')
               {

                    $type = 'interview_schedule';
                    $request1 = new GoogleEvent();
                    $request1->title = Self::index()->sdf;
                    $request1->start_date = $request->date;
                    $request1->end_date = $request->date;

                    Utility::addCalendarData($request1, $type);
               }

               // send emails
               try
               {
                    $settings = Utility::settings();
                    $companyName = $settings['company_name'];
                    $companyAddress = $settings['company_address'];
                    $companyPhoneNumber = $settings['company_phone_numbar'];

                    // $interviewDate = Auth::user()->dateFormat($request->date);
                    // $interviewTime = Auth::user()->timeFormat($request->time);

                    $interviewDate = date('M d, Y', strtotime($request->date));
                    $interviewTime = date('g:i A', strtotime($request->time));

                    $appName = env('APP_NAME');
                    $appUrl = '<a href="' . env('APP_URL') . 'public/" target="_blank">' . env('APP_URL') . 'public/</a>';

                    $companyHrDetailsRaw = User::whereRaw('LOWER(`type`) = ?', ['hr'])
                         ->where('created_by', \Auth::user()->creatorId())
                         ->orderBy("id", "DESC")
                         ->first();

                    $companyHrDetails = Employee::where("user_id", ($companyHrDetailsRaw->id ?? 0))->first();

                    $contactPersonName = $companyName ?? "";
                    $contactPersonPhoneNumber = $companyPhoneNumber ?? "";

                    if ($$companyHrDetails != null)
                    {
                         $contactPersonName = $companyHrDetails->name;
                         $contactPersonPhoneNumber = $companyHrDetails->phone;
                    }

                    foreach ($interviewer_ids as $interviewer_id)
                    {
                         $interviewerDetailsRaw = User::where('created_by', \Auth::user()->creatorId())->where("id", $interviewer_id)->first();
                         $interviewerDetails = Employee::where("user_id", $interviewerDetailsRaw->id)->first();

                         if ($interviewerDetails != null)
                         {
                              $interviewerName = $interviewerDetails->name;
                              $interviewerEmail = $interviewerDetails->email;

                              // $interviewerEmail = "jarvis@mailinator.com";

                              foreach ($candidate_job_application_ids as $candidate_job_application_id)
                              {
                                   $candidateData = JobApplication::where('id', $candidate_job_application_id)->first();
                                   if ($candidateData != null)
                                   {
                                        $candidateName = $candidateData->name;
                                        $candidateEmail = $candidateData->email;
                                        $candidatePhone = $candidateData->phone;

                                        // $candidateEmail = "jarvis@mailinator.com";

                                        $appliedJobDetails = Job::where("id", $candidateData->job)->first();

                                        if ($appliedJobDetails != null)
                                        {
                                             //  send email to candidate for interview update
                                             $jobTitle = $appliedJobDetails->title;

                                             $emailForCancidate = $candidateEmail;
                                             $subjectForCancidate = "Interview Scheduled for $jobTitle";

                                             $bodyForCancidate = "<p>Dear $candidateName,</p>
                                             <p>I hope this email finds you well.</p>
                                             <p>We are pleased to confirm your interview with $companyName. Please find the interview details below:</p>
                              
                                             <p>Date: $interviewDate</p>
                                             <p>Time: $interviewTime</p>
                                             <p>Position: $jobTitle</p>
                                             <p>Address: $companyAddress</p>
                                             <p>Contact Person: $contactPersonName (Mobile: $contactPersonPhoneNumber)</p>
                                             <p>Should you have any questions or need to reschedule, please feel free to contact us at your earliest convenience.</p>
                              
                                             <p>We look forward to meeting with you and discussing your potential role at $companyName.</p>
                                             <p>Best regards,</p>
                                             <p>$contactPersonName</p>
                                             <p>$companyName</p>
                                             ";

                                             $sendEmailToCandidate = Mail::html($bodyForCancidate, function ($message) use ($emailForCancidate, $subjectForCancidate)
                                             {
                                                  $message->to($emailForCancidate)->subject($subjectForCancidate);
                                             });

                                             //  send email to candidate for interview update


                                             // --------------------------------------------------------------------------


                                             //  send email to Interviewer

                                             $emailForInterviewer = $interviewerEmail;
                                             $subjectForInterviewer = "Interview Scheduled for $candidateName - $jobTitle";

                                             $bodyForInterviewer = "<p>Dear $interviewerName,</p>
                                             <p>I hope this email finds you well.</p>
                                             <p>I wanted to inform you that we have scheduled an interview with $candidateName for the $jobTitle position. Below are the details of the interview:</p>
                              
                                             <p>Date: $interviewDate</p>
                                             <p>Time: $interviewTime</p>
                                             
                                             <p>Comments : $comments</p>
                              
                                             <p>Best regards,</p>
                                             <p>$contactPersonName</p>
                                             <p>$companyName</p>
                                             ";

                                             $sendEmailToInterviewer = Mail::html($bodyForInterviewer, function ($message) use ($emailForInterviewer, $subjectForInterviewer)
                                             {
                                                  $message->to($emailForInterviewer)->subject($subjectForInterviewer);
                                             });

                                             //  send email to Interviewer

                                        }
                                   }
                              }
                         }
                    }
               }
               catch (Exception | Throwable $ex)
               {
                    //throw $th;
               }
               // send emails

               return redirect()->back()->with('success', __('Interview schedule successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(LocalInterviewSchedule $interviewSchedule, Request $request)
     {
          $job_application_id = $request->query("job_application_id") ?? 0;
          $job_id = $request->query("job_id") ?? 0;

          $stages = JobStage::where('created_by', \Auth::user()->creatorId())->get();
          return view('interviewSchedule.show', compact('interviewSchedule', 'stages', 'job_application_id', 'job_id'));
     }

     public function edit(LocalInterviewSchedule $interviewSchedule)
     {
          $employees = User::where('type', 'employee')->get()->pluck('name', 'id');

          // $employees->prepend('--', '');

          $candidates = JobApplication::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
          // $candidates->prepend('--', '');

          return view('interviewSchedule.edit', compact('employees', 'candidates', 'interviewSchedule'));
     }

     public function update(Request $request, LocalInterviewSchedule $interviewSchedule)
     {
          if (\Auth::user()->can('Edit Interview Schedule'))
          {
               $validator = \Validator::make(
                    $request->all(),
                    [
                         'candidate_job_application_ids' => 'required',
                         'interviewer_ids' => 'required',
                         'date' => 'required',
                         'time' => 'required',
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }

               $candidate_job_application_ids = $request->candidate_job_application_ids ?? [];
               $interviewer_ids = $request->interviewer_ids ?? [];


               $interviewSchedule->candidate_job_application_ids  = json_encode($candidate_job_application_ids);
               $interviewSchedule->interviewer_ids   = json_encode($interviewer_ids);

               $interviewSchedule->date      = $request->date;
               $interviewSchedule->time      = $request->time;
               $interviewSchedule->comment   = $request->comment;
               $interviewSchedule->save();

               $comments = $request->comment ?? "";




               // send emails

               $settings = Utility::settings();
               $companyName = $settings['company_name'];
               $companyAddress = $settings['company_address'];
               $companyPhoneNumber = $settings['company_phone_numbar'];

               // $interviewDate = Auth::user()->dateFormat($request->date);
               // $interviewTime = Auth::user()->timeFormat($request->time);

               $interviewDate = date('M d, Y', strtotime($request->date));
               $interviewTime = date('g:i A', strtotime($request->time));

               $appName = env('APP_NAME');
               $appUrl = '<a href="' . env('APP_URL') . 'public/" target="_blank">' . env('APP_URL') . 'public/</a>';

               $companyHrDetailsRaw = User::whereRaw('LOWER(`type`) = ?', ['hr'])
                    ->where('created_by', \Auth::user()->creatorId())
                    ->orderBy("id", "DESC")
                    ->first();

               $companyHrDetails = Employee::where("user_id", ($companyHrDetailsRaw->id ?? 0))->first();

               $contactPersonName = $companyName ?? "";
               $contactPersonPhoneNumber = $companyPhoneNumber ?? "";

               if ($companyHrDetailsRaw != null)
               {
                    $contactPersonName = $companyHrDetails->name;
                    $contactPersonPhoneNumber = $companyHrDetails->phone;
               }

               foreach ($interviewer_ids as $interviewer_id)
               {
                    $interviewerDetailsRaw = User::where('created_by', \Auth::user()->creatorId())->where("id", $interviewer_id)->first();
                    $interviewerDetails = Employee::where("user_id", $interviewerDetailsRaw->id)->first();

                    if ($interviewerDetails != null)
                    {
                         $interviewerName = $interviewerDetails->name;
                         $interviewerEmail = $interviewerDetails->email;

                         // $interviewerEmail = "sumit.redspark@gmail.com";

                         foreach ($candidate_job_application_ids as $candidate_job_application_id)
                         {
                              $candidateData = JobApplication::where('id', $candidate_job_application_id)->first();
                              if ($candidateData != null)
                              {
                                   $candidateName = $candidateData->name;
                                   $candidateEmail = $candidateData->email;
                                   $candidatePhone = $candidateData->phone;

                                   // $candidateEmail = "sumit.redspark@gmail.com";

                                   $appliedJobDetails = Job::where("id", $candidateData->job)->first();

                                   if ($appliedJobDetails != null)
                                   {
                                        //  send email to candidate for interview update
                                        $jobTitle = $appliedJobDetails->title;

                                        $emailForCancidate = $candidateEmail;
                                        $subjectForCancidate = "Interview Rescheduled for $jobTitle";

                                        $bodyForCancidate = "<p>Dear $candidateName,</p>
                                        <p>I hope this email finds you well.</p>
                                        <p>We have rescheduled your interview. Please find the interview details below:</p>
                         
                                        <p>Date: $interviewDate</p>
                                        <p>Time: $interviewTime</p>
                                        <p>Position: $jobTitle</p>
                                        <p>Address: $companyAddress</p>
                                        <p>Contact Person: $contactPersonName (Mobile: $contactPersonPhoneNumber)</p>
                                        <p>Should you have any questions or need to reschedule, please feel free to contact us at your earliest convenience.</p>
                         
                                        <p>We look forward to meeting with you and discussing your potential role at $companyName.</p>
                                        <p>Best regards,</p>
                                        <p>$contactPersonName</p>
                                        <p>$companyName</p>
                                        ";

                                        $sendEmailToCandidate = Mail::html($bodyForCancidate, function ($message) use ($emailForCancidate, $subjectForCancidate)
                                        {
                                             $message->to($emailForCancidate)->subject($subjectForCancidate);
                                        });

                                        //  send email to candidate for interview update


                                        // --------------------------------------------------------------------------


                                        //  send email to Interviewer

                                        $emailForInterviewer = $interviewerEmail;
                                        $subjectForInterviewer = "Interview Rescheduled for $candidateName - $jobTitle";

                                        $bodyForInterviewer = "<p>Dear $interviewerName,</p>
                                        <p>I hope this email finds you well.</p>
                                        <p>I wanted to inform you that we have rescheduled an interview with $candidateName for the $jobTitle position. Below are the details of the interview:</p>
                         
                                        <p>Date: $interviewDate</p>
                                        <p>Time: $interviewTime</p>
                                        
                                        <p>Comments : $comments</p>
                         
                                        <p>Best regards,</p>
                                        <p>$contactPersonName</p>
                                        <p>$companyName</p>
                                        ";

                                        $sendEmailToInterviewer = Mail::html($bodyForInterviewer, function ($message) use ($emailForInterviewer, $subjectForInterviewer)
                                        {
                                             $message->to($emailForInterviewer)->subject($subjectForInterviewer);
                                        });

                                        //  send email to Interviewer

                                   }
                              }
                         }
                    }
               }
               // send emails






               return redirect()->back()->with('success', __('Interview schedule successfully updated.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function destroy(LocalInterviewSchedule $interviewSchedule)
     {
          $interviewSchedule->delete();

          return redirect()->back()->with('success', __('Interview schedule successfully deleted.'));
     }

     public function get_interview_schedule_data(Request $request)
     {
          $arrayJson = [];
          if ($request->get('calender_type') == 'google_calender')
          {
               $type = 'interview_schedule';
               $arrayJson =  Utility::getCalendarData($type);
               // dd($type,$arrayJson);
          }
          else
          {

               //$data = LocalInterviewSchedule::where('employee',\Auth::id())->get();
               $data = LocalInterviewSchedule::where('created_by', \Auth::user()->creatorId())->get();


               foreach ($data as $val)
               {
                    $end_date = date_create($val->end_date);
                    date_add($end_date, date_interval_create_from_date_string("1 days"));

                    // dd([
                    //      "Start Date" => $val->date,
                    //      "End Date" => $end_date,
                    // ]);

                    foreach ($val->candidateJobApplicationData() as $scheduleRow)
                    {
                         $arrayJson[] = [
                              "id" => $val->id,
                              //"title" => Self::index()->sdf,
                              "title" => $scheduleRow->jobs->title,
                              "start" => $val->date,
                              // "end" => date_format($end_date, "Y-m-d"),
                              "end" => $val->date,
                              "className" => $val->color,
                              "textColor" => '#000',
                              "allDay" => true,
                              // "url" => route('interview-schedule.show', $val['id']),
                              "url" => route('interview-schedule.show', $val['id']) . '?job_application_id=' . $scheduleRow->id . '&job_id=' . $scheduleRow->jobs->id,


                         ];
                    }
               }
          }

          return $arrayJson;
     }
}
