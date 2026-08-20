<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Mail;

use App\Mail\DemoMail;

class MailController extends Controller
{
     public function index()

     {

          $mailData = [

               'title' => 'Mail from Test Email',

               'body' => 'This is for testing email using smtp.'

          ];


          Mail::to('sumit.redspark@gmail.com')->send(new DemoMail($mailData));
          Mail::to('nirav.redspark@gmail.com')->send(new DemoMail($mailData));
          Mail::to('parthpdd@mailinator.com')->send(new DemoMail($mailData));

          // Mail::to('parthpdd@mailinator.com')->send(new DemoMail($mailData));



          dd("Email is sent successfully.");
     }
}
