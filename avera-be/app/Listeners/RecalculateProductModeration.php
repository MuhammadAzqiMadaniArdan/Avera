<?php

namespace App\Listeners;

use App\Events\ImageModerated;
use App\Modules\Product\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecalculateProductModeration implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private ProductService $productService) {}

    /**
     * Handle the event.
     */
    public function handle(ImageModerated $event): void
    {
        if($event->ownerType !== 'product') {
            return;
        }
        $this->productService->recalculateByImageId($event->imageId);
    }
}
