<?php

namespace App\Modules\Checkout\Models;

use App\Modules\Product\Models\Product;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutItem extends Model
{
    use HasUuids,HasFactory;
     protected $fillable = [
        'checkout_id',
        'store_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'weight',
        'discount'
    ];

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
