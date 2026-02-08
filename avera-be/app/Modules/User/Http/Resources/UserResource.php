<?php

namespace App\Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "identity_core_id" => $this->identity_core_id,
            "username" => $this->username,
            "name" => $this->name,
            "role" => $this->role,
            "email" => $this->email,
            "gender" => $this->gender,
            "phone_number" => $this->phone_number,
            "image" => $this->image,
            "user_addresses" => UserAddressResource::collection(
                $this->whenLoaded('userAddresses')
            )
        ];
    }
}
