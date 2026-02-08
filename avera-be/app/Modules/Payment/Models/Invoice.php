<?php

namespace App\Modules\Payment\Models;

use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuids,HasFactory;

    protected  $fillable = [
        'order_id',
        'invoice_number',
        'issued_at',
        'due_date',
        'status',
        'total_amount'
    ];
    
    protected $casts = [
        'issued_at' => 'datetime',
        'due_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
