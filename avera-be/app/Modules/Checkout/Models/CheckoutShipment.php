<?php

namespace App\Modules\Checkout\Models;

use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutShipment extends Model
{
    use HasUuids,HasFactory;
    
    protected $fillable = [
        'checkout_id',
        'store_id',
        'user_address_id',
        'courier_code',
        'courier_name',
        'service',
        'description',
        'etd',
        'cost',
        'min_days',
        'max_days',
        'is_selected'
    ];

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
