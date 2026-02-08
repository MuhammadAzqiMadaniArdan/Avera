<?php

namespace App\Modules\Promotion\Http\Resources;

use App\Modules\Product\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionProductResource extends JsonResource
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
            'promotion_id' => $this->promotion_id,
            'product_id' => $this->product_id,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,

            'promotion' => new PromotionResource(
                $this->whenLoaded('promotion')
            ),
            'product' => new ProductResource(
                $this->whenLoaded('product')
            )
        ];
    }
}
