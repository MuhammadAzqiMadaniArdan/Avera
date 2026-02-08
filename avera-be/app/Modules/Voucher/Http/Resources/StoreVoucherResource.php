<?php

namespace App\Modules\Voucher\Http\Resources;

use App\Modules\Store\Http\Resources\StoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreVoucherResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'min_order_amount' => $this->min_order_amount,
            'max_discount' => $this->max_discount,
            'total_quota' => $this->total_quota,
            'claimed_count' => $this->claimed_count,
            'usage_limit_per_user' => $this->usage_limit_per_user,
            'is_claimable' => $this->is_claimable,

            'store' => new StoreResource(
                $this->whenLoaded('store')
            )
        ];
    }
}
