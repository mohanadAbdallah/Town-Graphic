<?php

namespace App\Models\Category;

use App\Helpers\File;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $hidden = ['title_ar','title_en', 'description_en',  'description_ar','image', 'user_id', 'status', 'created_at', 'updated_at'];
    protected $appends = ['title','description','image_url', 'agent_id'];

    public function products()
    {
        return $this->hasMany(Product::class,'sub_category_id');
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
