<?php

namespace App\Modules\Checkout\Http\Resources;

use App\Modules\Checkout\Models\CheckoutShipment;
use App\Modules\Store\Http\Resources\StoreResource;
use App\Modules\User\Http\Resources\UserAddressResource;
use App\Modules\User\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this) {
            return [];
        }
        $addressId = $this->user_address_id;

        $grouped = $this->items ? $this->items
            ->groupBy('store_id')
            ->map(function ($items, $storeId) use ($addressId) {
                $store = $items->first()->store;

                $shipments = $this->shipments ? $this->shipments->where('store_id', $storeId)->where('user_address_id', $addressId)->values() : null;

                $selectedShipment = $shipments ? $shipments->firstWhere('is_selected', true) : null;

                return [
                    'store' => new StoreResource($store),
                    'items' => CheckoutItemResource::collection($items),
                    'subtotal' => $items->sum('subtotal'),

                    'shipments' => $shipments ?  CheckoutShipmentResource::collection($shipments) : null,
                    'selected_shipment' => $selectedShipment
                        ? new CheckoutShipmentResource($selectedShipment)
                        : null,

                    'shipping_cost' => $selectedShipment?->cost ?? 0,
                ];
            })
            ->values() : null;
        return [
            'id' => $this->id,
            'user' => new UserResource(
                $this->whenLoaded('user')
            ),
            'user_address_id' => $this->user_address_id,
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'total_price' => $this->total_price,
            'payment_method' => $this->payment_method,
            'stores' => $grouped,
            'user_address' => new UserAddressResource(
                $this->whenLoaded('userAddress')
            )
        ];
    }
}
