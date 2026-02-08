<?php

namespace App\Modules\Product\Http\Resources;

use App\Modules\Category\Http\Resources\CategoryResource;
use App\Modules\Store\Http\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->slug,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,

            'price' => (float) $this->price,
            'stock' => $this->stock,
            'status' => $this->status,
            'age_rating' => $this->age_rating,
            'sold' => $this->sales_count,

            /* ======================
             | Moderation
             ====================== */
            'moderation' => [
                'visibility' => $this->resolveVisibility(),
                'moderated_at' => $this->moderated_at,
                'has_blocked_images' => $this->hasBlockedImages(),
            ],

            /* ======================
             | Relations
             ====================== */
            'store' => new StoreResource(
                $this->whenLoaded('store')
            ),

            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),

            'primary_image' => $this->whenLoaded('primaryImage', function () {
                return [
                    'id' => $this->primaryImage->id,
                    'url' => $this->primaryImage->path,
                    'moderation_status' => $this->primaryImage->moderation_status,
                ];
            }),

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn ($image) => [
                    'id' => $image->id,
                    'url' => $image->path,
                    'moderation_status' => $image->moderation_status,
                    'is_primary' => (bool) $image->pivot?->is_primary,
                ]);
            }),

            'created_at' => $this->created_at,
        ];
    }
}
