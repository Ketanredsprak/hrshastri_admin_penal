<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class InterviewSchedule extends Model
{
     protected $fillable = [
          'candidate',
          'employee',
          'date',
          'time',
          'comment',
          'employee_response',
          'created_by',
          'candidate_job_application_ids',
          'interviewer_ids'
     ];

     public function applications()
     {
          return $this->hasOne('App\Models\JobApplication', 'id', 'candidate');
     }

     public function candidateJobApplicationData()
     {
          try
          {
               $candidate_job_application_ids = (json_decode($this->candidate_job_application_ids ?? "") ?? []);
               return JobApplication::whereIn('id', $candidate_job_application_ids)->with("jobs")->get();
          }
          catch (Exception | Throwable $ex)
          {
               // dd($ex, $candidate_job_application_ids, $this->candidate_job_application_ids, $this->id, $this);
          }
     }

     public function interviewerData()
     {
          $interviewerUserIds = User::whereIn("id", json_decode($this->interviewer_ids))->get()->pluck("id")->toArray();
          return Employee::whereIn("user_id", $interviewerUserIds)->get();
     }

     public function users()
     {
          return $this->hasOne('App\Models\User', 'id', 'employee');
     }
}
