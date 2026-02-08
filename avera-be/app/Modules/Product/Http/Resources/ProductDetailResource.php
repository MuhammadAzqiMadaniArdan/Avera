<?php

namespace App\Modules\Product\Http\Resources;

use App\Modules\Category\Http\Resources\CategoryResource;
use App\Modules\Order\Http\Resources\ReviewResource;
use App\Modules\Store\Http\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->id,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,

            'price' => (float) $this->price,
            'stock' => $this->stock,
            'age_rating' => $this->age_rating,
            'sold' => $this->sales_count,
            'rating' => $this->reviews_avg_rating
                ? number_format($this->reviews_avg_rating, 2)
                : '0.00',
            'reviews' => $this->reviews_count,

            /* ======================
             | Relations
             ====================== */
            'store' => new StoreResource(
                $this->whenLoaded('store')
            ),

            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn($image) => [
                    'id' => $image->id,
                    'url' => $image->url,
                    'moderation_status' => $image->moderation_status,
                    'is_primary' => (bool) $image->pivot?->is_primary,
                    'position' => $image->pivot?->position,
                ]);
            }),

            'created_at' => $this->created_at,

            'category_path' => $this->whenLoaded('category', function () {
                return $this->category->getBreadcrumbPath();
            }),

        ];
    }
}
