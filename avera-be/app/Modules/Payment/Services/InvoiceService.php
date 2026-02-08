<?php

namespace App\Modules\Payment\Services;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Contracts\InvoiceServiceInterface;
use App\Modules\Payment\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService implements InvoiceServiceInterface {
    public function generate(Order $order) : Invoice
    {
        return DB::transaction(function () use ($order)
        {
            return Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => $this->generateNumber(),
                'issued_at' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'paid',
                'total_amount' => $order->total_price,
            ]);
        });
    }

    private function generateNumber() : string{
        return 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }
}