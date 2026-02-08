<?php

namespace App\Modules\Checkout\Models;

use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAddress;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'user_address_id',
        'subtotal',
        'shipping_cost',
        'total_price',
        'status',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(CheckoutItem::class);
    }
    public function shipments()
    {
        return $this->hasMany(CheckoutShipment::class)->orderBy('created_at');
    }
    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }
}
