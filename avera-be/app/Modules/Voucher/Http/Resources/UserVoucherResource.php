<?php

namespace App\Modules\Voucher\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVoucherResource extends JsonResource
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
            'status' => $this->status,
            'voucherable_id' => $this->voucherable_id,
            'voucherable_type' => $this->voucherable_type,
            'claimed_at' => $this->claimed_at,

            'voucherable' => $this->whenLoaded(
                $this->voucherable
            )
        ];
    }
}
