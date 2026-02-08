<?php

namespace App\Modules\Cart\Http\Resources;

use App\Modules\Store\Http\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartStoreGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $firstItem = $this->first();

        return [
            'store' => new StoreResource($firstItem->product->store),
            'items' => CartItemResource::collection($this),
            'subtotal' => $this->sum(fn ($i) => $i->quantity * $i->price),
        ];
    }
}
