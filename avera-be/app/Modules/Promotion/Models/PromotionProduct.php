<?php

namespace App\Modules\Promotion\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionProduct extends Model
{
    use HasUuids,HasFactory;

    protected $fillable = [
        'promotion_id',
        'product_id',
        'discount_type',
        'discount_value'
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
