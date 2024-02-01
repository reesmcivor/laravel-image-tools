<?php

namespace ReesMcIvor\ImageTools\Classes;

use Intervention\Image\Facades\Image;

class ImageResizer
{
    /**
     * Resize an image using Intervention Image.
     *
     * @param string $sourcePath Path to the source image.
     * @param int $targetWidth The desired width of the image.
     * @param int $targetHeight The desired height of the image.
     * @param string $destinationPath Path to save the resized image.
     * @return void
     */
    public function resize($sourcePath, $targetWidth, $targetHeight)
    {
        $image = Image::make($sourcePath);
        $image->resize($targetWidth, $targetHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        return $image;
    }
}
