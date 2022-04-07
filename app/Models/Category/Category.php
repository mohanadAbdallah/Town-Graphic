<?php

namespace App\Models\Category;

use App\Helpers\File;
use App\Models\Product\Product;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $hidden = ['image','title_ar','title_en', 'description_en',  'description_ar', 'user_id', 'status', 'created_at', 'updated_at'];
    protected $appends = ['title','description','image_url', 'agent_id'];

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image)
            return File::getUrl($this->image);
        else
            return null;
    }

    public function getAgentIdAttribute()
    {
        return $this->user_id;
    }

    public function getTitleAttribute()
    {
        if (app()->getLocale() == 'en')
            return $this->title_en;
        else
            return $this->title_ar;
    }
    public function getDescriptionAttribute()
    {
        if (app()->getLocale() == 'en')
            return $this->description_en;
        else
            return $this->description_ar;
    }
}
