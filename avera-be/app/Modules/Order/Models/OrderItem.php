<?php

namespace App\Modules\Order\Models;

use App\Modules\Product\Models\Product;
use App\Modules\Voucher\Models\UserVoucher;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasUuids,HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'weight',
        'user_voucher_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function userVoucher()
    {
        return $this->belongsTo(UserVoucher::class);
    }
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
