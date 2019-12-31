<?php

namespace App\Repositories\FIle;

use Illuminate\Support\Facades\Storage;

class StorageImage
{
    public static function apply($imageContents, $extension)
    {
        $imageName = '00_Image_' . uniqid() . '.' . $extension;
        Storage::disk('public/product/')->put($imageName, $imageContents);
        return $imageName;
    }

    public static function delete($imageName)
    {
        Storage::disk('public/product/')->delete($imageName);
    }
}
