<?php

namespace App\Modules\Payment\Http\Resources;

use App\Modules\Order\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_gateway' => $this->payment_gateway,
            'transaction_id' => $this->transaction_id,
            'payment_type' => $this->payment_type,
            'gross_amount' => $this->gross_amount,
            'status' => $this->status,
            'payment_url' => $this->payment_url,
            'signature_key' => $this->signature_key,
            'paid_at' => $this->paid_at,
            'order' => new OrderResource(
                $this->whenLoaded('order')
            ),
        ];
    }
}
