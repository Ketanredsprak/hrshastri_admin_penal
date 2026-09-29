<?php

namespace App\Http\Controllers;


use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
use Stripe;
use DataTables;
use Auth;

class StripePaymentController extends Controller
{

    public function index(request $request)
    {
        if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'company')
        {
            $objUser = \Auth::user();

            if(\Auth::user()->type == 'super admin'){
                $orders  = Order::select(
                [
                    'orders.*',
                    'users.name as user_name',
                ])->join('users', 'orders.user_id', '=', 'users.id')->orderBy('orders.created_at', 'DESC')->get();
            }

            if(\Auth::user()->type == 'company'){
                $orders  = Order::select(
                [
                    'orders.*',
                    'users.name as user_name',
                ])->join('users', 'orders.user_id', '=', 'users.id')->where('orders.user_id',\Auth::user()->id)->orderBy('orders.created_at', 'DESC')->get();
            }

            if($request->ajax()){
                $data  =$orders;
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('price', function($row){
                            return ((!empty(env('CURRENCY_SYMBOL')) ? env('CURRENCY_SYMBOL') : '$') . $row->price);
                        })
                        ->addColumn('payment_status', function($row){
                            return ($row->payment_status == 'succeeded' ? ' <i class="mdi mdi-circle text-success"></i>'.ucfirst($row->payment_status) .' ' : '<i class="mdi mdi-circle text-danger"></i>'.  ucfirst($row->payment_status) .'');
                        })
                        ->addColumn('created_at', function ($row) {
                            return $row->created_at->format('d M Y') ;
                        })
                        ->addColumn('total_coupon_used', function ($row) {
                            return !empty($row->total_coupon_used)? (!empty($row->total_coupon_used->coupon_detail)? $row->total_coupon_used->coupon_detail->code: '-'): '-';
                        })
                        ->addColumn('receipt', function ($row) {
                            return (!empty($row->receipt)) ?  '<a href="'.$row->receipt.'" class="btn  btn-outline-primary" target="_blank"><i class="fas fa-file-invoice"></i></a>' : '-';
                        })
                    ->rawColumns(['used_coupon','price','payment_status','created_at','total_coupon_used','receipt'])
                    ->make(true);
            }else{
                return view("order.index");
            }
            // return view('order.index', compact('orders'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BKindex()
    {
        if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'company')
        {
            $objUser = \Auth::user();
            if(\Auth::user()->type == 'super admin'){
                $orders  = Order::select(
                [
                    'orders.*',
                    'users.name as user_name',
                ]
                )->join('users', 'orders.user_id', '=', 'users.id')->orderBy('orders.created_at', 'DESC')->get();
            }

            if(\Auth::user()->type == 'company'){
                $orders  = Order::select(
                [
                    'orders.*',
                    'users.name as user_name',
                ])->join('users', 'orders.user_id', '=', 'users.id')->where('orders.user_id',\Auth::user()->id)->orderBy('orders.created_at', 'DESC')->get();
            }
            return view('order.index', compact('orders'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function stripe($code)
    {
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        if(isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret']))
        {
            if(\Auth::user()->can('Manage Company Settings') || \Auth::user()->type == 'company')
            {
                $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
                $plan    = Plan::find($plan_id);
                if($plan)
                {
                    if(!\Auth::user()->canSubscribePlan($plan))
                    {
                        return redirect()->route('plans.index')->with('error', $plan->getIneligibilityReason(\Auth::user()) ?? __('Your current active employee/user count exceeds this plan\'s capacity.'));
                    }
                    if(\Auth::user()->isPlanDowngrade($plan))
                    {
                        return redirect()->route('plans.index')->with('error', __('Downgrade is not allowed. You can renew your current plan or upgrade to a higher plan.'));
                    }
                    return view('stripe', compact('plan','admin_payment_setting'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Plan is deleted.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Local/dev helper: renew or upgrade plan without charging payment.
     */
    public function devRenewWithoutPay(Request $request)
    {
        if (!(config('app.env') === 'local' || config('app.debug'))) {
            abort(404);
        }

        if (!(\Auth::user()->type == 'company' || \Auth::user()->can('Manage Company Settings'))) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        try {
            $planId = Crypt::decrypt($request->plan_id);
        } catch (\Throwable $e) {
            return redirect()->route('plans.index')->with('error', __('Invalid plan.'));
        }

        $plan = Plan::find($planId);
        $user = \Auth::user();

        if (!$plan) {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }

        if (!$user->canSubscribePlan($plan)) {
            return redirect()->route('plans.index')->with('error', $plan->getIneligibilityReason($user) ?? __('Your current active employee/user count exceeds this plan\'s capacity.'));
        }

        if ($user->isPlanDowngrade($plan)) {
            return redirect()->route('plans.index')->with('error', __('Downgrade is not allowed. You can renew your current plan or upgrade to a higher plan.'));
        }

        $oldExpire = $user->plan_expire_date;
        $assignPlan = $user->assignPlan($plan->id);

        if (empty($assignPlan['is_success'])) {
            return redirect()->route('plans.index')->with('error', __($assignPlan['error'] ?? 'Plan activation failed.'));
        }

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        Order::create([
            'order_id' => $orderID,
            'name' => $user->name,
            'email' => $user->email,
            'card_number' => null,
            'card_exp_month' => null,
            'card_exp_year' => null,
            'plan_name' => $plan->name,
            'plan_id' => $plan->id,
            'price' => 0,
            'price_currency' => !empty(env('CURRENCY_CODE')) ? env('CURRENCY_CODE') : 'INR',
            'txn_id' => 'DEV-FREE-' . $orderID,
            'payment_type' => __('DEV Renew Without Pay'),
            'payment_status' => 'succeeded',
            'receipt' => null,
            'user_id' => $user->id,
        ]);

        $user->refresh();
        $msg = __('Dev renew applied without payment.')
            . ' ' . __('Previous expiry:') . ' ' . ($oldExpire ?: '-')
            . ' → ' . __('New expiry:') . ' ' . ($user->plan_expire_date ?: '-');

        return redirect()->route('plans.index')->with('success', $msg);
    }

    public function stripePost(Request $request)
    {
        $admin_payment_setting = Utility::getAdminPaymentSetting();

        if((\Auth::user()->can('Manage Company Settings') || \Auth::user()->type == 'company') && (isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled']== 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret'])))
        {
            $objUser = \Auth::user();
            $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
            $plan    = Plan::find($planID);

            if($plan)
            {
                if(!$objUser->canSubscribePlan($plan))
                {
                    return redirect()->route('plans.index')->with('error', $plan->getIneligibilityReason($objUser) ?? __('Your current active employee/user count exceeds this plan\'s capacity.'));
                }
                if($objUser->isPlanDowngrade($plan))
                {
                    return redirect()->route('plans.index')->with('error', __('Downgrade is not allowed. You can renew your current plan or upgrade to a higher plan.'));
                }
                try
                {
                    $price = $plan->price;
                    if(!empty($request->coupon))
                    {
                        $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                        if(!empty($coupons))
                        {
                            $usedCoupun     = $coupons->used_coupon();
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price          = $plan->price - $discount_value;

                            if($coupons->limit == $usedCoupun)
                            {
                                return redirect()->back()->with('error', __('This coupon code has expired.'));
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                        }
                    }

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    if($price > 0.0)
                    {
                        Stripe\Stripe::setApiKey($admin_payment_setting['stripe_secret']);
                        $data = Stripe\Charge::create(
                            [
                                "amount" => 100 * $price,
                                "currency" => !empty(env('CURRENCY')) ? env('CURRENCY') : 'inr',
                                "source" => $request->stripeToken,
                                "description" => " Plan - " . $plan->name,
                                "metadata" => ["order_id" => $orderID],
                                ]
                            );
                        }
                    else
                    {
                        $data['amount_refunded'] = 0;
                        $data['failure_code']    = '';
                        $data['paid']            = 1;
                        $data['captured']        = 1;
                        $data['status']          = 'succeeded';
                    }


                    if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                    {

                        $orders = Order::create(
                            [
                                'order_id' => $orderID,
                                'name' => $request->name,
                                'card_number' => isset($data['payment_method_details']['card']['last4']) ? $data['payment_method_details']['card']['last4'] : '',
                                'card_exp_month' => isset($data['payment_method_details']['card']['exp_month']) ? $data['payment_method_details']['card']['exp_month'] : '',
                                'card_exp_year' => isset($data['payment_method_details']['card']['exp_year']) ? $data['payment_method_details']['card']['exp_year'] : '',
                                'plan_name' => $plan->name,
                                'plan_id' => $plan->id,
                                'price' => $price,
                                'price_currency' => isset($data['currency']) ? $data['currency'] : '',
                                'txn_id' => isset($data['balance_transaction']) ? $data['balance_transaction'] : '',
                                'payment_status' => isset($data['status']) ? $data['status'] : 'succeeded',
                                'payment_type' => __('STRIPE'),
                                'receipt' => isset($data['receipt_url']) ? $data['receipt_url'] : 'free coupon',
                                'user_id' => $objUser->id,
                            ]
                        );


                        if(!empty($request->coupon))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $objUser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();

                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }

                        }
                        if($data['status'] == 'succeeded')
                        {
                            $assignPlan = $objUser->assignPlan($plan->id);
                            if($assignPlan['is_success'])
                            {
                                return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
                            }
                            else
                            {
                                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                            }
                        }
                        else
                        {
                            return redirect()->route('plans.index')->with('error', __('Your payment has failed.'));
                        }
                    }
                    else
                    {
                        return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
                    }
                }
                catch(\Exception $e)
                {
                    return redirect()->route('plans.index')->with('error', __($e->getMessage()));
                }
            }
            else
            {
                return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }
}
