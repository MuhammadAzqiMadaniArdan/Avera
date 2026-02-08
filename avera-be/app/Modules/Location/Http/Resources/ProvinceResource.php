<?php

namespace App\Modules\Location\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
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
            'name' => $this->name,
            'cities' => CityResource::collection(
                $this->whenLoaded('cities')
            )
        ];
    }
}
