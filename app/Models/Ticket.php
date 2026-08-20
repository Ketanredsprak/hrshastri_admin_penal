<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
     protected $fillable = [
          'title',
          'employee_id',
          'priority',
          'end_date',
          'description',
          'ticket_code',
          'created_by',
          'ticket_created',
          'status',
     ];

     public function ticketUnread()
     {
          if (\Auth::user()->type == 'employee')
          {

               return TicketReply::where('ticket_id', $this->id)->where('is_read', 0)->where('created_by', '!=', \Auth::user()->id)->count('id');
          }
          else
          {
               return TicketReply::where('ticket_id', $this->id)->where('is_read', 0)->where('created_by', '!=', \Auth::user()->creatorId())->count('id');
          }
     }

     public function createdBy()
     {
          return $this->hasOne('App\Models\user', 'id', 'ticket_created');
     }


     public function getCreatedAtFormattedAttribute($value)
     {
          return Auth::user()->dateFormat($this->created_at);
     }

     public function getUpdatedAtFormattedAttribute($value)
     {
          return Auth::user()->dateFormat($this->updated_at);
     }
}
