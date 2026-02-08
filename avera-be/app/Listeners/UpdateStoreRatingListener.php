<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Modules\Order\Models\Review;
use App\Modules\Store\Models\Store;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStoreRatingListener implements ShouldQueue
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
    public function handle(ReviewCreated $event): void
    {
        $storeId = $event->review->product->store_id;

        $stats = Review::query()
        ->join('products','products.id','=','reviews.product_id')
        ->where('products.store_id',$storeId)
        ->selectRaw('COUNT(*) as count,SUM(rating) as sum')
        ->first();

        Store::where('id',$storeId)->update([
            'rating_count' => $stats->count,
            'rating_avg' => $stats->sum / max(1,$stats->count),
        ]);
    }
}
