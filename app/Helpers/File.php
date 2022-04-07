<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 21/2/2019
 * Time: 3:14 م
 */

namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

class File
{
    public function isFileExists($file): bool
    {
        if (Storage::disk('local')->exists($file)) {
            return true;
        }
        return false;
    }

    public static function getUrl($file)
    {
        return Storage::disk(config('driver'))->url($file);
    }
}
