<?php

namespace App\Models\Order;

use App\Helpers\File;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CartItem extends Model
{
    use HasFactory;

    protected $hidden = ['images', 'updated_at'];
    protected $appends = ['images_urls'];
    protected $casts = ['images' => 'array'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImagesUrlsAttribute()
    {
        $urlImages = [];
        if ($this->images != null) {
            foreach ($this->images as $image) {
                if (Str::contains($image, '*facebook*')) {
                    array_push($urlImages, Str::replaceLast('"','',Str::replaceFirst('*facebook*"','',$image)));
                } else {
                    array_push($urlImages, File::getUrl($image));
                }
            }
        }
        return $urlImages;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d');
    }
}
