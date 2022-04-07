<?php

namespace App\Models\Order;

use App\Helpers\File;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;
    protected $hidden = ['images', 'created_at', 'updated_at'];
    protected $appends = ['images_urls'];
    protected $casts = ['images' => 'array'];
    protected $with = ['product'];
    public function cartItem()
    {
        return $this->belongsTo(CartItem::class,'cart_item_id');
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
                if (Str::contains($image,'*facebook*'))
                {
                    array_push($urlImages, Str::replaceLast('"','',Str::replaceFirst('*facebook*"','',$image)));
                }else{
                    array_push($urlImages, File::getUrl($image));
                }
            }
        }
        return $urlImages;
    }
}
