<?php

namespace App\Modules\Store\Models;

use App\Modules\Banner\Models\Banner;
use App\Modules\Image\Models\Image;
use App\Modules\Product\Models\Product;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasUuids,HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'logo_image_id',
        'name',
        'slug',
        'status',
        'description',
        'banner_url',
        'rating',
        'verification_status',
        'verified_at',
        'verified_by',
        'rating_avg',
        'rating_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function logo()
    {
        return $this->belongsTo(Image::class,'logo_image_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class,'store_followers')->withTimestamps();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
    public function banner()
    {
        return $this->hasMany(Banner::class);
    }
    public function storeAddress()
    {
        return $this->hasOne(StoreAddress::class);
    }
}
