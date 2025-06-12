<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService {
    public static function create($title, $model, $separator = '-') {
        return $model ? $model->slug . $separator . substr(md5(microtime()), rand(0, 26), 5) : Str::slug($title, $separator);
    }
}   