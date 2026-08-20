<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{

    protected $table = 'expense_types';

    protected $fillable = [
        'id',
        'expense_type',
        'created_by',
    ];
}
