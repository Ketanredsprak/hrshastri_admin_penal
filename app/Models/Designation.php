<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
     protected $fillable = [
          'department_id',
          'name',
          'document',
          'status',
          'created_by',
          'branch_id',
          'description'
     ];

     public function branch()
     {
          return $this->hasOne(Branch::class, 'id', 'branch_id');
     }

     public function getDocumentAttribute($value)
    {
        $document = "";
        if (!empty($value)) {
            $document = asset('public/designation_documents/' . $value);
        }
        return $document;
    }
    
}
