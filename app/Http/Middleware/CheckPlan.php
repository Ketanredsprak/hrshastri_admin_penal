<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPlan
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
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->type === 'super admin') {
                return $next($request);
            }

            if (!$user->hasActiveSubscription()) {
                if ($user->type === 'company') {
                    // Allowed routes for expired company to manage/renew plans
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
                        'home',
                        'dashboard',
                    ];

                    $currentRoute = $request->route() ? $request->route()->getName() : null;

                    if (!in_array($currentRoute, $allowedRoutes) && !$request->is('stripe*') && !$request->is('plan*') && !$request->is('orders*') && !$request->is('logout*') && !$request->is('dashboard*') && !$request->is('home*')) {
                        return redirect()->route('dashboard')->with('error', __('Your subscription plan is inactive or expired. Please renew your subscription.'));
                    }
                } else {
                    // Employees and Sub-users of expired company cannot access the panel
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('message', __('Your organization\'s subscription plan is inactive or expired. Please contact your company administrator.'));
                }
            }
        }

        return $next($request);
    }
}
