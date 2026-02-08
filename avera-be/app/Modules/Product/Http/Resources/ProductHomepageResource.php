<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductHomepageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'store_id'    => $this->store_id,
            'name'  => $this->name,
            'slug'  => $this->slug,
            'price' => (float) $this->price,
            'sold'  => $this->sales_count,
            'status'  => $this->moderation_visibilty,

            // hanya URL primary image
            'primaryImage' => $this->primaryImage
                ? $this->primaryImage->cloudinaryUrl()
                : null,
        ];
    }
}
