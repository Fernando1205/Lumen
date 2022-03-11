<?php

namespace App\Http\Services;

class ImageService {

    public function saveImage($file): string
    {
        $fileName = $file->hashName();
        $path = './upload/';
        $file->move($path, $fileName);
        $data['image'] = ltrim($path,'.').$fileName;
        return ltrim($path, '.').$fileName;
    }
}
