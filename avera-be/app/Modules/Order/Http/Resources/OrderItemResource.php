<?php

namespace App\Modules\Order\Http\Resources;

use App\Modules\Product\Http\Resources\ProductHomepageResource;
use App\Modules\Voucher\Http\Resources\UserVoucherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'weight' => $this->weight,
            'product' => new ProductHomepageResource(
                $this->whenLoaded('product')
            ),
            'user_voucher' => new UserVoucherResource(
                $this->whenLoaded('userVoucher')
            ),
            'review' => new ReviewResource(
                $this->whenLoaded('review')
            ),
        ];
    }
}
