<?php

namespace App\Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'label' => $this->label,
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            
            'province_name' => $this->province_name,
            'city_name' => $this->city_name,
            'district_name' => $this->district_name,
            'village_name' => $this->village_name,
            
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'other' => $this->other,
                
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            
            'is_default' => $this->is_default,
            
            'user' => new UserResource(
                $this->whenLoaded('user')
            ),
        ];
    }
}
