<?php

namespace App\Modules\Order\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
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
            'order_id' => $this->order_id,
            'courier' => $this->courier,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'shipping_cost' => $this->shipping_cost,
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            'recipient_address' => $this->recipient_address,
            'order' => new OrderResource(
                $this->whenLoaded('order')
            )
        ];
    }
}
