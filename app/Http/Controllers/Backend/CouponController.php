<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\ShipDivision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CouponStoreRequest;
use App\Http\Requests\Backend\CouponUpdateRequest;

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


    public function EditCoupon($id)
    {

        $coupon = Coupon::findOrFail($id);
        return view('backend.coupon.edit_coupon', compact('coupon'));
    } // End Method

    public function UpdateCoupon(CouponUpdateRequest $request)
    {

        Coupon::findOrFail($request->id)->update($request->validated() + ['created_at' => Carbon::now()]);

        $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.coupon')->with($notification);
    } // End Method

    public function DeleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        if ($coupon) {
            $coupon->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
