<?php

namespace App\Models\User;

use App\Helpers\File;

use App\Models\Order\Cart;
use App\Models\Order\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class AppUser extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable, HasFactory;
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at'
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function getImageAttribute($value)
    {
        if (Str::contains($value,'*facebook*'))
        {
            return Str::replaceFirst('*facebook*','',$value);
        }else{
            return File::getUrl($value);
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'app_user_id');
    }
}
