<?php

namespace App\Http\Controllers\Backend;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\UploadImageTrait;
use Image;

class SliderController extends Controller
{
    use UploadImageTrait;

    public function AllSlider()
    {
        $sliders = Slider::latest()->get();
        return view('backend.slider.slider_all', compact('sliders'));
    } // End Method

    public function AddSlider()
    {
        return view('backend.slider.slider_add');
    } // End Method

    public function StoreSlider(Request $request)
    {
        $save_url = $this->uploadImages($request, 'slider_image', 'slider', 2376, 807);
        Slider::insert([
            'slider_title' => $request->slider_title,
            'short_title' => $request->short_title,
            'slider_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);
    }

    public function EditSlider($id)
    {
        $sliders = Slider::findOrFail($id);
        return view('backend.slider.slider_edit', compact('sliders'));
    }

    public function UpdateSlider(Request $request)
    {

        $slider_id = $request->id;
        $old_img = $request->old_img;

        if ($request->file('slider_image')) {
            $save_url = $this->uploadImages($request, 'slider_image', 'slider', 2376, 807);
            if (file_exists($old_img)) {
                unlink($old_img);
            }

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Slider Updated with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        } else {

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
            ]);

            $notification = array(
                'message' => 'Slider Updated without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        } // end else

    } // End Method


    public function DeleteSlider($id)
    {

        $slider = Slider::findOrFail($id);
        unlink($slider->slider_image);
        Slider::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method
}
