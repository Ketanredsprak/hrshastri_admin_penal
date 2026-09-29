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
               // Always permit exit impersonation route
               if ($request->route() && $request->route()->getName() === 'impersonate.exit')
               {
                    return $next($request);
               }

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
               $user = Auth::user();
               if ($user->type !== 'super admin' && !$user->hasActiveSubscription())
               {
                    if ($user->type === 'company')
                    {
                         $allowedRoutes = [
                              'plans.index',
                              'plans.show',
                              'plans.edit',
                              'plans.update',
                              'plan.upgrade',
                              'plan.active',
                              'order.index',
                              'stripe',
                              'stripe_request',
                              'stripe.post',
                              'stripe.dev.renew',
                              'plan.pay.with.paypal',
                              'plan.get.payment.status',
                              'plan_request.index',
                              'request.view',
                              'send.request',
                              'request.cancel',
                              'apply.coupon',
                              'logout',
                              'profile',
                              'edit.profile',
                              'update.password',
                              'local_dev_login',
                              'impersonate.exit',
                              'home',
                              'dashboard',
                         ];

                         $currentRoute = $request->route() ? $request->route()->getName() : null;

                         if (!in_array($currentRoute, $allowedRoutes) && !$request->is('stripe*') && !$request->is('plan*') && !$request->is('orders*') && !$request->is('logout*') && !$request->is('local-dev-login*') && !$request->is('dashboard*') && !$request->is('home*'))
                         {
                              return redirect()->route('dashboard')->with('error', __('Your subscription plan is inactive or expired. Please renew your subscription.'));
                         }
                    }
                    else
                    {
                         auth()->logout();
                         $request->session()->invalidate();
                         $request->session()->regenerateToken();

                         return redirect()->route('login')->with('message', __('Your organization\'s subscription plan is inactive or expired. Please contact your company administrator.'));
                    }
               }
          }
          return $next($request);
     }
}
