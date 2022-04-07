<?php

namespace App\Models\Product;

use App\Helpers\File;
use App\Models\Category\Category;
use App\Models\Category\SubCategory;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'json'
    ];
    protected $hidden = ['title_ar', 'title_en', 'description_en', 'description_ar', 'material_type_ar', 'material_type_en', 'images_count_ar', 'images_count_en', 'images', 'user_id', 'created_at', 'updated_at'];
    protected $appends = ['title', 'description', 'material_type', 'images_count', 'images_urls', 'agent_id', 'agent_name'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgentIdAttribute()
    {
        return $this->user_id;
    }

    public function getImagesUrlsAttribute()
    {
        $urlImages = [];
        if ($this->images != null) {
            foreach ($this->images as $image) {
                array_push($urlImages, File::getUrl($image));
            }
        }
        return $urlImages;
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

    public function getMaterialTypeAttribute()
    {
        if (app()->getLocale() == 'en')
            return $this->material_type_en;
        else
            return $this->material_type_ar;
    }

    public function getImagesCountAttribute()
    {
        if (app()->getLocale() == 'en')
            return $this->images_count_en;
        else
            return $this->images_count_ar;
    }
    public function getAgentNameAttribute()
    {
        if (app()->getLocale() == 'en')
            return $this->user->name_en ?? '';
        else
            return $this->user->name_ar ?? '';
    }
}
