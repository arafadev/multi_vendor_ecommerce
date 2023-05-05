<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Backend\ProductUpdateRequest;
use App\Http\Requests\Backend\VendorProductStoreRequest;
use App\Http\Requests\Backend\VendorProductUpdateRequest;
use Image;

class VendorProductController extends Controller
{
    use UploadImageTrait;
    public function vendorAllProduct()
    {

        $products = Product::where('vendor_id', Auth::user()->id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all', compact('products'));
    }

    public function VendorAddProduct()
    {

        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('vendor.backend.product.vendor_product_add', compact('brands', 'categories'));
    } // End Method


    public function VendorGetSubCategory($category_id)
    {
        $subcat = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_encode($subcat);
    } // End Method

    public function VendorStoreProduct(VendorProductStoreRequest $request)
    {

        $save_url = $this->uploadImages($request, 'product_thambnail', 'product', 800, 800,  'thambnail');
        $data = collect($request->validated())->except(['multi_img'])->toArray();
        $data['product_thambnail'] = $save_url;
        $data['product_slug'] = strtolower(str_replace(' ', '-', $request->product_name));
        $data['status'] = 1;
        $data['vendor_id'] = Auth::user()->id;
        $data['created_at'] = Carbon::now();
        $product_id = Product::insertGetId($data);
        $save_urls = $this->uploadImages($request, 'multi_img', 'product', 800, 800,  'multi-image');
        $multi_images = [];

        foreach ($save_urls as $save_url) {
            $multi_images[] = [
                'product_id' => $product_id,
                'photo_name' => $save_url,
                'created_at' => Carbon::now(),
            ];
        }

        MultiImg::insert($multi_images);

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.all.product')->with($notification);
    }

    public function VendorEditProduct($id)
    {
        $multiImgs = MultiImg::where('product_id', $id)->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        $product = Product::findOrFail($id);
        return view('vendor.backend.product.vendor_product_edit', compact('brands', 'categories', 'product', 'subcategory', 'multiImgs'));
    }

    public function VendorUpdateProduct(VendorProductUpdateRequest $request)
    {

        $data = $request->except('product_id');
        $data = $data +  [
            'product_slug' => strtolower(str_replace(' ', '-', $request->product_name)),
            'vendor_id' => Auth::user()->id,
            'status' => 1,
            'created_at' => Carbon::now(),
        ];

        Product::findOrFail($request->product_id)->update($data);
        $notification = array(
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function VendorUpdateProductThambnail(Request $request)
    {
        // check if user upload photo or not here
        $save_url = $this->uploadImages($request, 'product_thambnail', 'product', 800, 800,  'thambnail');
        if (file_exists($request->old_img)) {
            unlink($request->old_img);
        }

        Product::findOrFail($request->product_id)->update([
            'product_thambnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Product Image Thambnail Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    //Vendor Multi Image Update
    public function VendorUpdateProductmultiImage(Request $request)
    {

        $imgs = $request->multi_img;

        foreach ($imgs as $id => $img) {
            $imgDel = MultiImg::findOrFail($id);
            unlink($imgDel->photo_name);
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            Image::make($img)->resize(800, 800)->save('upload/product/multi-image/' . $make_name);
            $uploadPath = 'upload/product/multi-image/' . $make_name;
            MultiImg::where('id', $id)->update([
                'photo_name' => $uploadPath,
                'updated_at' => Carbon::now(),
            ]);
        }
        $notification = array(
            'message' => 'Vendor Product Multi Image Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function VendorMultiimgDelete($id)
    {
        $oldImg = MultiImg::findOrFail($id);
        unlink($oldImg->photo_name);
        $oldImg->delete();
        $notification = array(
            'message' => 'Vendor Product Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function VendorProductInactive($id)
    {

        Product::findOrFail($id)->update(['status' => 0]);
        $notification = array(
            'message' => 'Product Inactive',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


    public function VendorProductActive($id)
    {

        Product::findOrFail($id)->update(['status' => 1]);
        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


    public function VendorProductDelete($id)
    {

        $product = Product::findOrFail($id);
        unlink($product->product_thambnail);
        Product::findOrFail($id)->delete();

        $imges = MultiImg::where('product_id', $id)->get();
        foreach ($imges as $img) {
            unlink($img->photo_name);
            MultiImg::where('product_id', $id)->delete();
        }

        $notification = array(
            'message' => 'Vendor Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
