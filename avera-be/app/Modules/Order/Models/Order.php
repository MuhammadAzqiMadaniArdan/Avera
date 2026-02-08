<?php

namespace App\Modules\Order\Models;

use App\Modules\Payment\Models\Invoice;
use App\Modules\Payment\Models\Payment;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids,HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'shipping_cost',
        'total_price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
     public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
