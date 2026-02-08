<?php

namespace App\Modules\User\Models;

use App\Modules\Checkout\Models\Checkout;
use App\Modules\Image\Models\Image;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuids, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar_image_id',
        'identity_core_id',
        'email',
        'username',
        'name',
        'gender',
        'role',
        'phone_number'
    ];

    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function store()
    {
        return $this->hasOne(Store::class);
    }
    public function checkout()
    {
        return $this->hasOne(Checkout::class);
    }
    
    public function followedStores()
    {
        return $this->belongsToMany(Store::class,'store_followers')->withTimestamps();
    }
    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    public function userAddressDefault()
    {
        return $this->hasOne(UserAddress::class)->where('is_default',true);
    }
    public function avatar()
    {
        return $this->belongsTo(Image::class,'avatar_image_id','id');
    }
}
