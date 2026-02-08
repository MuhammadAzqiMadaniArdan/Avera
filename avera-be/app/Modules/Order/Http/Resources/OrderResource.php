<?php

namespace App\Modules\Order\Http\Resources;

use App\Modules\Payment\Http\Resources\PaymentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'subtotal' => $this->subtotal, 
            'shipping_cost' => $this->shipping_cost, 
            'total_price' => $this->total_price, 
            'status' => $this->status,
            'order_items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ), 
            'shipment' => new ShipmentResource(
                $this->whenLoaded('shipment')
            ), 
            'payment' => new PaymentResource(
                $this->whenLoaded('payment')
            )
        ];
    }
}
