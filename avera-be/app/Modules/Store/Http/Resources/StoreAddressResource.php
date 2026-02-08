<?php

namespace App\Modules\Store\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreAddressResource extends JsonResource
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
            'store_name' => $this->store_name,
            'phone_number' => $this->phone_number,
            
            'province_name' => $this->province_name,
            'city_name' => $this->city_name,
            'district_name' => $this->district_name,
            'village_name' => $this->village_name,
            
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            
            'store' => new StoreResource(
                $this->whenLoaded('store')
            ),
        ];
    }
}
