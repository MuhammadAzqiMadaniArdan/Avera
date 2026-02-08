<?php

namespace App\Modules\Checkout\Http\Resources;

use App\Modules\Product\Http\Resources\ProductHomepageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutItemResource extends JsonResource
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
            'price' => $this->price,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
            'weight' => $this->weight,
            'discount' => $this->discount,
            'product' => new ProductHomepageResource(
                $this->whenLoaded('product')
            )
        ];
    }
}
