<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value'];
    public $timestamps = false;

    static function getValue($key){
        $setting = self::where('key',$key)->first();
        if($setting){
            return $setting->value;
        }
    }
}
