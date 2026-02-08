<?php

namespace App\Modules\Product\Http\Resources;

use App\Modules\Image\Http\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'product_id' => $this->product_id,
            'image_id' => $this->image_id,
            'is_primary' => $this->is_primary,
            'position' => $this->position,
            'status' => $this->status,
            'replace_count' => $this->replace_count,
            'last_replaced_at' => $this->last_replaced_at,

            'product' => new ProductResource(
                $this->whenLoaded('product')
            ),
            'image' => new ImageResource(
                $this->whenLoaded('image')
            )
        ];
    }
}
