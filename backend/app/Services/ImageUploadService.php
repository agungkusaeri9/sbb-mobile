<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function handleImageUpload($image, $directory)
    {
        $imagePath = $image->store($directory, 'public');
        return $imagePath;
    }
}
