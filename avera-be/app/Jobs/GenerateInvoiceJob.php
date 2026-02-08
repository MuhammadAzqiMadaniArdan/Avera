<?php

namespace App\Jobs;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Services\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly int $orderId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(InvoiceService $invoiceService): void
    {
        $order = Order::with('invoice')->findOrFail($this->orderId);
        if ($order->invoice) {
            return;
        }
        $invoiceService->generate($order);
    }
}
