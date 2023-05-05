<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductStoreRequest;
use App\Http\Requests\Backend\ProductUpdateRequest;
use Image;

class ProductController extends Controller
{
    use UploadImageTrait;

    public function allProduct()
    {
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }

    public function AddProduct()
    {
        $activeVendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('backend.product.product_add', compact('activeVendor', 'brands', 'categories'));
    }

    public function getSubcategory($category_id)
    {
        $subcategories = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_decode($subcategories);
    }

    public function storeProduct(ProductStoreRequest $request)
    {

        $save_url = $this->uploadImages($request, 'product_thambnail', 'product', 800, 800, 'thambnail');
        $data = collect($request->validated())->except(['multi_img'])->toArray();
        $data['product_thambnail'] = $save_url;
        $data['product_slug'] = strtolower(str_replace(' ', '-', $request->product_name));
        $data['status'] = 1;
        $data['created_at'] = Carbon::now();
        $product_id = Product::insertGetId($data);

        $save_urls = $this->uploadImages($request, 'multi_img', 'product', 800, 800, 'multi-image');


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

        return redirect()->route('all.products')->with($notification);
    }


    public function editProduct($id)
    {
        $multiImgs = MultiImg::where('product_id', $id)->get();
        $activeVendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        $product = Product::findOrFail($id);
        return view('backend.product.product_edit', compact('brands', 'categories', 'activeVendor', 'product', 'subcategory', 'multiImgs'));
    }

    public function updateProduct(ProductUpdateRequest $request)
    {

        $data = $request->except('product_id');
        $data = $data +  [
            'product_slug' => strtolower(str_replace(' ', '-', $request->product_name)),
            'status' => 1,
            'created_at' => Carbon::now(),
        ];

        Product::findOrFail($request->product_id)->update($data);
        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.products')->with($notification);
    }


    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            @unlink(public_path($product->product_thambnail));
            $multi_images = $product->multiImages;
            foreach ($multi_images as $image) {
                @unlink(public_path($image->photo_name));
            }
            $product->delete();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updateProductThambnail(Request $request)
    {
        $save_url = $this->uploadImages($request, 'product_thambnail', 'product', 800, 800, 'thambnail');
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

    public function UpdateProductMultiimage(Request $request)
    {

        $imgs = $request->multi_img;

        foreach ($imgs as $id => $img) {
            $imgDel = MultiImg::findOrFail($id);

            unlink($imgDel->photo_name);

            $save_urls = $this->uploadImages($request, 'multi_img', 'product', 800, 800, 'multi-image');

            $multi_images = [];

            foreach ($save_urls as $save_url) {
                $multi_images[] = [
                    'product_id' => $request->product_id,
                    'photo_name' => $save_url,
                    'created_at' => Carbon::now(),
                ];
            }

            MultiImg::insert($multi_images);
        }

        $notification = array(
            'message' => 'Product Multi Image Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function MulitImageDelelte($id)
    {
        $oldImg = MultiImg::findOrFail($id);
        unlink($oldImg->photo_name);


        MultiImg::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function ProductInactive($id)
    {

        Product::findOrFail($id)->update(['status' => 0]);
        $notification = array(
            'message' => 'Product Inactive',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


    public function ProductActive($id)
    {

        Product::findOrFail($id)->update(['status' => 1]);
        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function productDelete($id)
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
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

}
