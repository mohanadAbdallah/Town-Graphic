<?php

namespace App\Models\Order;

use App\Models\Data\Setting;
use App\Models\User\AppUser;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $appends = ['status_name', 'status_color', 'total_amount_with_fees', 'order_date', 'order_delivery_date', 'vat_number', 'payment_method'];
    protected $hidden = ['created_at', 'updated_at', 'transaction_id'];

    public function appUser()
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function getTotalAmountAttribute($value)
    {
        $items = $this->items;
        $totalAmount = 0;
        foreach ($items as $item) {
            $itemAmount = $item->price * $item->amount;
            $totalAmount += ($itemAmount);
        }
        return $totalAmount;
    }

    public function getTotalAmountWithFeesAttribute($value)
    {
        $delivery_amount = intval($this->delivery_amount);
        return $this->total_amount + $this->fees + $delivery_amount;
    }

    public function getStatusNameAttribute()
    {
        $status = $this->status;
        if (app()->getLocale() == 'en') {
            if ($status == 0) {
                return 'Pending';
            } else if ($status == 1) {
                return 'Processing';
            } else if ($status == 2) {
                return 'Approved';
            } else if ($status == 3) {
                return 'Canceled';
            }
        } else {
            if ($status == 0) {
                return 'قيد المعالجة';
            } else if ($status == 1) {
                return 'قيد التنفيذ';
            } else if ($status == 2) {
                return 'تمت الموافقة';
            } else if ($status == 3) {
                return 'تم إلغاءه';
            }
        }

    }


    public function getStatusColorAttribute()
    {
        $status = $this->status;
        if ($status == 0) {
            return 'black';
        } else if ($status == 1) {
            return 'orange';
        } else if ($status == 2) {
            return 'green';
        } else if ($status == 3) {
            return 'red';
        }
    }
    public function getPaymentMethodAttribute()
    {
        $type = $this->type;
        if (app()->getLocale() == 'en') {
            if ($type == 1) {
                return 'Master card';
            } else if ($type == 2) {
                return 'Cash';
            } else if ($type == 3) {
                return 'Paypal';
            }
        }else{
            if ($type == 1) {
                return 'بطاقة ائتمانية';
            } else if ($type == 2) {
                return 'دفع نقدي';
            } else if ($type == 3) {
                return 'باي بال';
            }
        }
    }

    public function getOrderDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

    public function getOrderDeliveryDateAttribute()
    {
        if ($this->delivery_date)
            return Carbon::parse($this->delivery_date)->format('d/m/Y');
        else
            return '--';
    }
    public function getVatNumberAttribute()
    {
       return Setting::where('key','vat_id')->first()->value ?? '';
    }
}
