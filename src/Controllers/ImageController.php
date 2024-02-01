<?php

namespace ReesMcIvor\ImageTools\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ReesMcIvor\ImageTools\Classes\ImageResizer;

class ImageController extends Controller
{
    public function resize(Request $request)
    {
        $imagePath = $request->input('image_path');
        $width = $request->input('width');
        $height = $request->input('height');

        // Use the ImageResizer to perform the resize operation
        $resizer = new ImageResizer();
        $resizedImagePath = $resizer->resize($imagePath, $width, $height);

        // Return the path to the resized image, or directly serve the image file
        return response()->json(['resized_image_path' => $resizedImagePath]);
    }
}