<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SubCategoryStoreRequest;
use App\Http\Requests\Backend\SubCategoryUpdateRequest;

class SubCategoryController extends Controller
{
    public function subcategories()
    {
        $subcategories = SubCategory::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategories'));
    }

    public function addSubcategory()
    {
        $categories = Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_add', ['categories' => $categories]);
    }

    public function storeSubcategory(SubCategoryStoreRequest $request)
    {
        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);
    }



    public function editSubcategory($id)
    {
        $categories = Category::orderBy('category_name', 'ASC')->get();
        $subcategory = SubCategory::findOrFail($id);
        return view('backend.subcategory.subcategory_edit', ['categories' => $categories, 'subcategory' => $subcategory]);
    }

    public function updateSubcategory(SubCategoryUpdateRequest $request,  $id)
    {
        SubCategory::findOrFail($id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);
    }


    public function deleteSubcategory($id)
    {
        $subcategory = SubCategory::find($id);
        if ($subcategory) {
            $subcategory->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
