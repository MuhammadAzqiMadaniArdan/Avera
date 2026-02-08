<?php

namespace App\Listeners;

use App\Events\ImageModerated;
use App\Modules\Banner\Services\BannerImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecalculateStoreBannerModeration implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private BannerImageService $bannerImageService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImageModerated $event): void
    {
        if ($event->ownerType !== 'store_banner') {
            return;
        }
        $this->bannerImageService->recalculateByImageId($event->imageId);
    }
}
