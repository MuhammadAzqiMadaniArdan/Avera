<?php

namespace App\Modules\Product\Domain;

use App\Modules\Product\Models\Product;

class ProductModeration
{
    public function resolve(Product $product): array
    {
        $statuses = $product->images()->pluck('images.moderation_status');

        return match (true) {
            $statuses->contains('rejected') => [
                'moderation_visibility' => 'hidden',
            ],
            $statuses->contains('warning') => [
                'moderation_visibility' => 'limited',
                'age_rating' => '18+',
            ],
            default => [
                'moderation_visibility' => 'public',
            ],
        };
    }
}
