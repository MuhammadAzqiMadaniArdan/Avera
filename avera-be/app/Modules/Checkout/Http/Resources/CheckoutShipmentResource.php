<?php

namespace App\Modules\Checkout\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutShipmentResource extends JsonResource
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
            'store_id' => $this->store_id,
            'user_address_id' => $this->user_address_id,
            'courier_code' => $this->courier_code,
            'courier_name' => $this->courier_name,
            'service' => $this->service,
            'description' => $this->description,
            'etd' => $this->etd,
            'min_days' => $this->min_days,
            'max_days' => $this->max_days,
            'cost' => $this->cost,
            'is_selected' => $this->is_selected
        ];
    }
}
