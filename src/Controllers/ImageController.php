<?php

namespace ReesMcIvor\ImageTools\Controllers;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use ReesMcIvor\ImageTools\Classes\ImageResizer;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * @var ImageResizer
     */
    protected $imageResizer;

    /**
     * Create a new controller instance.
     *
     * @param ImageResizer $imageResizer
     */
    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }

    /**
     * Handle the request to resize an image.
     *
     * @param Request $request
     * @param string $image Base64 encoded image or URL
     * @param int $width Desired width
     * @param int $height Desired height
     * @return \Illuminate\Http\Response
     */
    public function resize(Request $request, $image, $width, $height)
    {
        $image = base64_decode($image);
        $imageName = $this->generateImageName($image, $width, $height);
        if ($this->imageExists($imageName)) {
            return $this->serveImage($imageName);
        } else {
            return $this->processAndStoreImage($image, $imageName, $width, $height);
        }
    }

    /**
     * Generate a unique name for the resized image.
     *
     * @param string $image
     * @param int $width
     * @param int $height
     * @return string
     */
    protected function generateImageName($image, $width, $height)
    {
        $imageName = basename(parse_url($image, PHP_URL_PATH));
        return "{$width}x{$height}_{$imageName}";
    }

    /**
     * Check if the resized image already exists.
     *
     * @param string $imageName
     * @return bool
     */
    protected function imageExists($imageName)
    {
        return Storage::disk('thumbnails')->exists($imageName);
    }

    /**
     * Serve the resized image.
     *
     * @param string $imageName
     * @return \Illuminate\Http\Response
     */
    protected function serveImage($imageName)
    {
        return Storage::disk('thumbnails')->response($imageName);
    }

    /**
     * Process the image resize request, store the new image, and serve it.
     *
     * @param string $image URL or Base64 encoded image
     * @param string $imageName
     * @param int $width
     * @param int $height
     * @return \Illuminate\Http\Response
     */
    protected function processAndStoreImage($image, $imageName, $width, $height)
    {
        try {

            $resizedImage = Image::make($image)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('jpg', 90);

            Storage::disk('thumbnails')->put($imageName, (string) $resizedImage);

            return $this->serveImage($imageName);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
