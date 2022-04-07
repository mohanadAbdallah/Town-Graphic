<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $hidden = ['content_en', 'content_ar', 'created_at', 'updated_at'];
    protected $appends = ['content'];

    public function getContentAttribute()
    {
        if (app()->getLocale() == 'en')
            return $this->content_en;
        else
            return $this->content_ar;
    }

}
