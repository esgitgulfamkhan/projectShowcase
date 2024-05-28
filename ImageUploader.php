<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageUploader
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function upload(UploadedFile $file, $path, $width = null, $height = null)
    {
        $image = $this->imageManager->make($file->getRealPath());

        if ($width && $height) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $filename = $this->generateFilename($file);
        $fullPath = $path . '/' . $filename;
        Storage::put($fullPath, (string) $image->encode());

        return $fullPath;
    }

    protected function generateFilename(UploadedFile $file)
    {
        return time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    }
}

// Usage example in a Controller
namespace App\Http\Controllers;

use App\Services\ImageUploader;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    protected $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:2048',
        ]);

        $path = $this->imageUploader->upload($request->file('image'), 'uploads/images', 800, 600);

        return response()->json(['path' => $path], 200);
    }
}
