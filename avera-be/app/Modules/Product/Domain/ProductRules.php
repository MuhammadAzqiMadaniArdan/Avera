<?php

namespace App\Modules\Product\Domain;

use App\Modules\Product\Models\Product;
use DomainException;
use Illuminate\Support\Collection;

class ProductRules
{
    public function canActivate(Product $product): void
    {
        if ($product->status === ProductStatus::ACTIVE->value) {
            throw new DomainException('Product already active');
        }
    }

    public function validateProductImage(
        Collection $images,
        array $imageIds
    ): void {
        if ($images->count() !== count($imageIds)) {
            throw new DomainException('Invalid image selection');
        }

        foreach ($images as $image) {
            match ($image->image->moderation_status) {
                'rejected' => throw new DomainException(
                    "Image {$image->position} violates policy"
                ),
                'pending' => throw new DomainException(
                    'Image is still under moderation'
                ),
                default => null
            };
        }
    }

    public function resolvePrimaryImage(Collection $images)
    {
        $primary = $images->firstWhere('is_primary', true);

        if (!$primary) {
            throw new DomainException('Primary image required');
        }

        return $primary;
    }
}
