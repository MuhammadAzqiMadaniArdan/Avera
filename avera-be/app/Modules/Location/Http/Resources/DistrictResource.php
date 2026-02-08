<?php

namespace App\Modules\Location\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
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
            'rajaongkir_id' => $this->rajaongkir_id,
            'city_id' => $this->city_id,
            'name' => $this->name,
            'zip_code' => $this->zip_code,
            'city' => CityResource::collection(
                $this->whenLoaded('city')
            )
        ];
    }
}
