<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BrandStoreRequest;
use App\Http\Requests\Backend\BrandUpdateRequest;
use App\Traits\UploadImageTrait;

class BrandController extends Controller
{
    use UploadImageTrait;
    public function brands()
    {
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all', compact('brands'));
    }

    public function AddBrand()
    {
        return view('backend.brand.brand_add');
    }

    public function StoreBrand(BrandStoreRequest $request)
    {
        $save_url = $this->uploadImages($request, 'brand_image', 'brand', 300, 300  );
        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
            'brand_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.brand')->with($notification);
    } // End Method


    public function deleteBrand($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            @unlink(public_path($brand->brand_image));
            $brand->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function EditBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.brand.brand_edit', ['brand' => $brand]);
    }

    public function UpdateBrand(BrandUpdateRequest $request, $id)
    {

        $brand = Brand::findOrFail($id);

        $save_url = $brand->brand_image;

        if ($request->hasFile('brand_image')) {
            if (file_exists($brand->brand_image)) {
                unlink($brand->brand_image);
            }
            $save_url = $this->uploadImage($request, 'brand_image', 'brand');
            $brand->brand_image = $save_url;
            $brand->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
                'brand_image' => $save_url,
            ]);
        } else {
            $brand->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
                'brand_image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Brand Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.brand')->with($notification);
    }
}
