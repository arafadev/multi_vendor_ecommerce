<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CategoryStoreRequest;
use App\Http\Requests\Backend\CategoryUpdateRequest;

class CategoryController extends Controller
{
    use UploadImageTrait;
    public function categories()
    {
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    }

    public function addCategory()
    {
        return view('backend.category.category_add');
    }


    public function storeCategory(CategoryStoreRequest $request)
    {
        $save_url = $this->uploadImages($request, 'category_image', 'category', 800, 800);
        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
            'category_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    } // End Method

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', ['category' => $category]);
    }


    public function updateCategory(CategoryUpdateRequest $request, $id)
    {

        $category = Category::findOrFail($id);

        // catch old image
        $save_url = $category->category_image;

        if ($request->hasFile('category_image')) {
            if (file_exists($category->category_image)) {
                unlink($category->category_image);
            }
            $save_url = $this->uploadImage($request, 'category_image', 'category');
            $category->category_image = $save_url;
            $category->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'category_image' => $save_url,
            ]);
        } else {
            $category->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'category_image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }


    public function deleteCategory($id)
    {

        $category = Category::find($id);
        if ($category) {
            @unlink(public_path($category->category_image));
            $category->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
