<?php

namespace App\Services;

use App\Helper\ImageHelper;
use Intervention\Image\Facades\Image;

class ImageService {
    public static function upload($image, $destination_path, $old_image = null) {
        if ($old_image) {
            @unlink(public_path($destination_path . $old_image));
        }

        $image_name = str_replace('=', '', base64_encode(md5($image->getClientOriginalName().uniqid())));
        $format = 'webp';
        
        $compressed_image = Image::make($image)->encode($format, 90);

        if (!file_exists(public_path($destination_path))) {
            mkdir(public_path($destination_path), 0777, true);
        }

        $compressed_image->save(public_path($destination_path . $image_name . '.' . $format));

        return $image_name . '.' . $format;
    }

    public static function delete($image, $destination_path) {
        @unlink(public_path($destination_path . $image));
    }
}