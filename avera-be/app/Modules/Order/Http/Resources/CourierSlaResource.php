<?php

namespace App\Modules\Order\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourierSlaResource extends JsonResource
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
            'courier_code' => $this->courier_code,
            'courier_name' => $this->courier_name,
            'min_days' => $this->min_days,
            'max_days' => $this->max_days,
            'is_active' => $this->is_active
        ];
    }
}
