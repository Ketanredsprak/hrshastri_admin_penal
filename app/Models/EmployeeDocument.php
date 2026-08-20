<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
     protected $fillable = [
          'employee_id', 'document_id', 'document_value', 'created_by'
     ];


     public function getDocumentLinkAttribute($value)
     {
          if (!empty($this->document_value))
          {
               return Utility::get_file('uploads/document') . "/" . $this->document_value;
          }
          return "";
     }
}
