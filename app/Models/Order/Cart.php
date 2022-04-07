<?php

namespace App\Models\Order;

use App\Models\Data\Setting;
use App\Models\User\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['items','items.product'];
    protected $appends = ['total_amount','total_amount_with_fees'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    public function getTotalAmountAttribute()
    {
        $items = $this->items;
        $totalAmount = 0;
        foreach ($items as $item){
            $itemAmount = $item->price * $item->amount;
            $totalAmount += ($itemAmount);
        }
        return $totalAmount;
    }
    public function getTotalAmountWithFeesAttribute()
    {
        $items = $this->items;
        $totalAmount = 0;
        foreach ($items as $item){
            $itemAmount = $item->price * $item->amount;
            $totalAmount += ($itemAmount);
        }
        $orderFees = Setting::where('key', 'order_fees')->first() ?? 0;
        $vatValue = ((intval($orderFees->value))/100) * $totalAmount;
        return $totalAmount + $vatValue;
    }
}
