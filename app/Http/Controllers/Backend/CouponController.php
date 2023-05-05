<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CouponStoreRequest;

class CouponController extends Controller
{
    public function AllCoupon()
    {
        $coupon = Coupon::latest()->get();
        return view('backend.coupon.coupon_all', compact('coupon'));
    } // End Method

    public function AddCoupon()
    {
        return view('backend.coupon.coupon_add');
    }

    public function StoreCoupon(CouponStoreRequest $request)
    {

        Coupon::insert($request->validated() + ['created_at' => Carbon::now()]);

        $notification = array(
            'message' => 'Coupon Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.coupon')->with($notification);
    } // End Method

}
