<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasUuids,HasFactory,SoftDeletes;

    protected $fillable = [
        'order_id',
        'store_id',
        'user_address_id',
        'courier_code',
        'courier_name',
        'service',
        'description',
        'min_days',
        'max_days',
        'tracking_number',
        'status',
        'shipping_cost',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
