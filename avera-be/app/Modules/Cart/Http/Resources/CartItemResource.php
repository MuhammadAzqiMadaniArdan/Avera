<?php

namespace App\Modules\Cart\Http\Resources;

use App\Modules\Product\Http\Resources\ProductHomepageResource;
use App\Modules\Product\Http\Resources\ProductResource;
use App\Modules\User\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_item_id' => $this->id,
            'quantity' => $this->quantity,
            'product' => new ProductHomepageResource($this->whenLoaded('product')),
        ];
    }
}
