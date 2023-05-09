<?php

namespace App\Http\Controllers\User;

use App\Models\ShipState;
use Illuminate\Http\Request;
use App\Models\ShipDistricts;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{

    public function DistrictGetAjax($division_id)
    {
        $ship = ShipDistricts::where('division_id', $division_id)->orderBy('district_name', 'ASC')->get();
        return json_encode($ship);
    }

    public function StateGetAjax($district_id)
    {
        $ship = ShipState::where('district_id', $district_id)->orderBy('state_name', 'ASC')->get();
        return json_encode($ship);
    }


    public function CheckoutStore(Request $request)
    {
        $data = [
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'post_code'     => $request->post_code,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_id' => $request->state_id,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
        ];
        $cartTotal = Cart::total();

        if ($request->payment_option === 'stripe') {
            return view('frontend.payment.stripe', compact('data', 'cartTotal'));
        } elseif ($request->payment_option === 'card') {
            return 'card page';
        } else {
            return view('frontend.payment.cash', compact('data', 'cartTotal'));
        }
    }
}
