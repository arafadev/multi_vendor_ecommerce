<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Determine the price based on whether or not the product is on sale
        $price = $product->discount_price ?? $product->selling_price;

        // Add the product to the cart
        Cart::add([
            'id' => $id,
            'name' => $request->product_name,
            'qty' => $request->quantity,
            'price' => $price,
            'weight' => 1,
            'options' => [
                'image' => $product->product_thambnail,
                'color' => $request->color,
                'size' => $request->size,
            ],
        ]);

        return response()->json(['success' => 'Product successfully added to cart.']);
    }
    public function AddToCartDetails(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Determine the price based on whether or not the product is on sale
        $price = $product->discount_price ?? $product->selling_price;

        // Add the product to the cart
        Cart::add([
            'id' => $id,
            'name' => $request->product_name,
            'qty' => $request->quantity,
            'price' => $price,
            'weight' => 1,
            'options' => [
                'image' => $product->product_thambnail,
                'color' => $request->color,
                'size' => $request->size,
            ],
        ]);

        return response()->json(['success' => 'Product successfully added to cart.']);
    }

    public function AddMiniCart()
    {

        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal

        ));
    } // End Method
    public function RemoveMiniCart($rowId)
    {
        Cart::remove($rowId);
        return response()->json(['success' => 'Product Remove From Cart']);
    } // End Method

    public function MyCart()
    {

        return view('frontend.mycart.mycart');
    } // End Method
    public function GetCartProduct()
    {

        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal

        ));
    } // End Method

    public function CartRemove($rowId)
    {
        Cart::remove($rowId);
        return response()->json(['success' => 'Successfully Remove From Cart']);
    } // End Method
    public function CartDecrement($rowId)
    {

        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty - 1);

        return response()->json('Decrement');
    } // End Method
    public function CartIncrement($rowId)
    {

        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty + 1);

        return response()->json('Increment');
    } // End Method

}
