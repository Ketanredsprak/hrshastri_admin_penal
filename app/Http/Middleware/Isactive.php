<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Employee;
use Illuminate\Http\Request;

class Isactive
{
     /**
      * Handle an incoming request.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
      * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
      */
     public function handle(Request $request, Closure $next)
     {
          if (Auth::check())
          {
               // dd('fmd');
               // dd(Auth::user()->login_status);
               if (Auth::user()->login_status == 0)
               {
                    auth()->logout();
                    return redirect()->route('login')->with('message', 'Your account is blocked. please,contact to admin');
               }

               $data = Employee::where('user_id', auth::id())->first();
               if (!empty($data))
               {
                    if ($data->hasExited())
                    {
                         auth()->logout();
                         return redirect()->route('login')->with('message', __('Your account is deactivated. Please contact the administrator.'));
                    }

                    if (!$data->isWebLoginAllowed())
                    {
                         auth()->logout();
                         return redirect()->route('login')->with('message', __('Web login is not enabled for your account. Please contact the administrator.'));
                    }
               }
          }
          return $next($request);
     }
}
