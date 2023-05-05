<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function Index()
    {
        $categories = Category::get();

        $skip_category_0 = $categories->get(0);
        $skip_product_0 = $skip_category_0->products()->where('status', 1)->orderBy('id', 'DESC')->limit(5)->get();

        $skip_category_2 = $categories->get(2);
        $skip_product_2 = $skip_category_2->products()->where('status', 1)->orderBy('id', 'DESC')->limit(5)->get();

        $skip_category_7 = $categories->get(7);
        $skip_product_7 = $skip_category_7->products()->where('status', 1)->orderBy('id', 'DESC')->limit(5)->get();


        $hot_deals = Product::where('hot_deals', 1)->whereNotNull('discount_price')->orderBy('id', 'DESC')->limit(3)->get();

        $special_offer = Product::where('special_offer', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $new = Product::where('status', 1)->orderBy('id', 'DESC')->limit(3)->get();

        $special_deals = Product::where('special_deals', 1)->orderBy('id', 'DESC')->limit(3)->get();

        return view('frontend.index', compact('skip_category_0', 'skip_product_0', 'skip_category_2', 'skip_product_2', 'skip_category_7', 'skip_product_7', 'hot_deals', 'special_offer', 'new', 'special_deals'));
    } // End Method

    public function ProductDetails($id, $slug)
    {
        $product = Product::findOrFail($id);
        $product_color = explode(',', $product->product_color);
        $product_size = explode(',', $product->product_size);
        $multiImage = MultiImg::where('product_id', $id)->get();;
        $relatedProduct = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->orderBy('id', 'DESC')->limit(4)->get();
        return view('frontend.product.product_details', compact('product', 'product_color', 'product_size', 'multiImage', 'relatedProduct'));
    }


    public function VendorDetails($id)
    {

        $vendor = User::findOrFail($id);
        $vproduct = Product::where('vendor_id', $id)->get();
        return view('frontend.vendor.vendor_details', compact('vendor', 'vproduct'));
    }

    public function VendorAll()
    {

        $vendors = User::where('status', 'active')->where('role', 'vendor')->orderBy('id', 'DESC')->get();
        return view('frontend.vendor.vendor_all', compact('vendors'));
    }
    public function catWithProducts(Request $request, $id, $slug)
    {
        $products = Product::where('status', 1)->where('category_id', $id)->orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('category_name', 'asc')->get();
        $category = Category::findOrFail($id);
        $newProduct = Product::orderBy('id', 'DESC')->limit(3)->get();

        return view('frontend.product.category_view', compact('products', 'categories', 'category', 'newProduct'));
    } // End Method

    public function SubCatWiseProduct(Request $request, $id, $slug)
    {
        $products = Product::where('status', 1)->where('subcategory_id', $id)->orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('category_name', 'ASC')->get();

        $breadsubcat = SubCategory::findOrFail($id);

        $newProduct = Product::orderBy('id', 'DESC')->limit(3)->get();

        return view('frontend.product.subcategory_view', compact('products', 'categories', 'breadsubcat', 'newProduct'));
    } // End Method


    public function ProductViewAjax($id)
    {

        $product = Product::with('category', 'brand')->findOrFail($id);
        $product_color = explode(',', $product->product_color);
        $product_size = explode(',', $product->product_size);

        return response()->json([

            'product' => $product,
            'color' => $product_color,
            'size' => $product_size,

        ]);
    } // End Method
}
