<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use Form;

class CouponController extends Controller
{
    public function index(request $request)
    {
        if(\Auth::user()->can('manage coupon'))
        {
            if ($request->ajax())
            {
                $data = Coupon::get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                            // $btn = ''. Auth::user()->hasPermissionTo('edit coupon').'<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center"data-url=" '.route('coupons.edit', $row->id) .'"data-ajax-popup="true" data-title="'. __('Edit Coupon') .'"data-bs-toggle="tooltip" data-bs-placement="top"title="'. __('Edit') .'"><i class="ti ti-pencil text-white"></i></a></div>'.Auth::user()->hasPermissionTo('delete coupon').'<div class="action-btn bg-danger ms-2">'. Form::open(['method' => 'DELETE', 'route' => ['coupons.destroy', $row->id], 'id' => 'delete-form-' . $row->id]) .'<a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para"data-bs-toggle="tooltip" data-bs-placement="bottom"\title="'. __('Delete') .'">\<i class="ti ti-trash text-white"></i></a>'. Form::close() .'</div>';
                            return ((Auth::user()->hasPermissionTo('edit coupon')) ? ' <span>
                            <div class="action-btn bg-warning ms-2"><a href="'. route('coupons.show', $row->id) .'"class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"data-bs-placement="top" title="'. __('View') .'"><i class="ti ti-eye text-white"></i></a></div><div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center"data-url=" '.route('coupons.edit', $row->id) .'"data-ajax-popup="true" data-title="'. __('Edit Coupon') .'"data-bs-toggle="tooltip" data-bs-placement="top"title="'. __('Edit') .'"><i class="ti ti-pencil text-white"></i></a></div>' : '') . ((Auth::user()->hasPermissionTo('delete coupon')) ? '<div class="action-btn bg-danger ms-2">'. Form::open(['method' => 'DELETE', 'route' => ['coupons.destroy', $row->id], 'id' => 'delete-form-' . $row->id]) .'<a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para"data-bs-toggle="tooltip" data-bs-placement="bottom"\title="'. __('Delete') .'"><i class="ti ti-trash text-white"></i></a>'. Form::close() .'</div> </span>' : '');
                        })
                        ->addColumn('used_coupon', function ($row) {
                            return $row->used_coupon();
                        })
                    ->rawColumns(['action','used_coupon'])
                    ->make(true);
            }else{
                return view("coupon.index");
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BKindex()
    {
        if(\Auth::user()->can('manage coupon'))
        {
            $coupons = Coupon::get();

            return view('coupon.index', compact('coupons'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create coupon'))
        {
            return view('coupon.create');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create coupon'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'discount' => 'required|numeric',
                                   'limit' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if(empty($request->manualCode) && empty($request->autoCode))
            {
                return redirect()->back()->with('error', 'Coupon code is required');
            }
            $coupon           = new Coupon();
            $coupon->name     = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit    = $request->limit;

            if(!empty($request->manualCode))
            {
                $coupon->code = strtoupper($request->manualCode);
            }

            if(!empty($request->autoCode))
            {
                $coupon->code = $request->autoCode;
            }

            $coupon->save();

            return redirect()->route('coupons.index')->with('success', __('Coupon successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Coupon $coupon)
    {
        $userCoupons = UserCoupon::where('coupon', $coupon->id)->get();

        return view('coupon.view', compact('userCoupons'));
    }


    public function edit(Coupon $coupon)
    {
        if(\Auth::user()->can('edit coupon'))
        {
            return view('coupon.edit', compact('coupon'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Coupon $coupon)
    {
        if(\Auth::user()->can('edit coupon'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'discount' => 'required|numeric',
                                   'limit' => 'required|numeric',
                                   'code' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $coupon           = Coupon::find($coupon->id);
            $coupon->name     = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit    = $request->limit;
            $coupon->code     = $request->code;

            $coupon->save();

            return redirect()->route('coupons.index')->with('success', __('Coupon successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Coupon $coupon)
    {
        if(\Auth::user()->can('delete coupon'))
        {
            $coupon->delete();

            return redirect()->route('coupons.index')->with('success', __('Coupon successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applyCoupon(Request $request)
    {

        $plan = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($request->plan_id));
        if($plan && $request->coupon != '')
        {
            $original_price = self::formatPrice($plan->price);
            $coupons        = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            if(!empty($coupons))
            {
                $usedCoupun = $coupons->used_coupon();
                if($coupons->limit == $usedCoupun)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($plan->price, 2),
                            'message' => __('This coupon code has expired.'),
                        ]
                    );
                }
                else
                {
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $plan_price     = $plan->price - $discount_value;
                    $price          = self::formatPrice($plan->price - $discount_value);
                    $discount_value = '-' . self::formatPrice($discount_value);

                    return response()->json(
                        [
                            'is_success' => true,
                            'discount_price' => $discount_value,
                            'final_price' => $price,
                            'price' => number_format($plan_price, 2),
                            'message' => __('Coupon code has applied successfully.'),
                        ]
                    );
                }
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, 2),
                        'message' => __('This coupon code is invalid or has expired.'),
                    ]
                );
            }
        }
    }

    public function formatPrice($price)
    {
        return env('CURRENCY_SYMBOL') . number_format($price);
    }
}
