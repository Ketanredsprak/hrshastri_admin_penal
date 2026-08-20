<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOne;

class DucumentUpload extends Model
{
    protected $fillable = [
        'name',
        'role',
        'document',
        'description',
        'document_type',
        'created_by',
    ];


    public function document_typeData(){
        return $this->belongsTo(Document::class,'document_type');
    }

    
}
