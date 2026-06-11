<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function processCourseImage(UploadedFile $file): string
    {
        $filename = 'mpic_' . uniqid() . '.jpg';
        $directory = 'courses';

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $img = Image::read($file);
        
        $img->cover(300, 300);

        Storage::disk('public')->put($directory . '/' . $filename, (string) $img->toJpeg(85));

        return $directory . '/' . $filename;
    }
}