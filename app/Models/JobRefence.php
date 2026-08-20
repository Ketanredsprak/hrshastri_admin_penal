<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRefence extends Model
{
     use HasFactory;
     protected $table = 'job_refence';
     protected $fillable = [
          'id',
          'job_id',
          'refence_file',
          'status',
          'created_by',
          'updated_at',
          'created_at',
     ];

     public function jobsData()
     {
          return $this->belongsTo(Job::class, 'job_id');
     }


     public function getDocumentLinkAttribute($value)
     {
          return asset('referenceDoc/' . $this->refence_file) ?? "";
     }
}
