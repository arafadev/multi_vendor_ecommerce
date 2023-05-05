<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Image;

trait UploadImageTrait
{


    public function uploadImages($request, $img, $folder, $height, $weight,  $insideFolder = '')
    {
        $images = $request->file($img);
        $image_paths = [];

        if (is_array($images)) {
            foreach ($images as $image) {
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                Image::make($image)->resize($height, $weight)->save('upload/' . $folder . '/' . $insideFolder . '/' . $name_gen);
                $image_paths[] = 'upload/' . $folder . '/' . $insideFolder . '/' . $name_gen;
            }
        } else {
            $image = $images;
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize($height, $weight)->save('upload/' . $folder . '/' . $insideFolder . '/' . $name_gen);
            $image_paths = 'upload/' . $folder . '/' . $insideFolder . '/' . $name_gen;
        }

        return $image_paths;
    }
}
