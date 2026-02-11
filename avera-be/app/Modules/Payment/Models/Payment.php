<?php

namespace App\Modules\Payment\Models;

use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasUuids,HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'snap_token',
        'payment_gateway',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'status',
        'gateway_status',
        'paid_at',
        'payment_url',
        'signature_key'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
