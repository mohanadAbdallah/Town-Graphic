<?php

namespace App\Models\User;

use App\Helpers\File;

use App\Models\Order\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'points'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'name_ar',
        'name_en',
        'email',
        'mobile',
        'address',
        'role',
        'is_active',
        'gender',
        'age',
        'gender_name',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $appends = [
        'is_active',
        'gender_name',
        'age'
    ];

    /* Start Relations */
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    /* End Relations */

    /* Start Appends */
    public function getIsActiveAttribute()
    {
        return $this->active == 0 ? 'Active' : 'Blocked';
    }

    public function getGenderNameAttribute()
    {
        return ($this->gender == 'm' ? 'Male' : ($this->gender == 'f' ? 'Female' : ''));
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function getBirthDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getImageAttribute($value)
    {
        return File::getUrl($value);
    }

    /* End Appends */
}
