<?php

namespace App\Listeners;

use App\Events\PaymentSettled;
use App\Jobs\GenerateInvoiceJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateInvoiceListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentSettled $event): void
    {
        GenerateInvoiceJob::dispatch($event->order->id);
    }
}
