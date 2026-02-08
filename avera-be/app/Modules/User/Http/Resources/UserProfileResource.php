<?php

namespace App\Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            "username" => $this->username,
            "name" => $this->name,
            "email" => $this->email,
            "gender" => $this->gender,
            "phone_number" => $this->phone_number,
            "avatar" => $this->whenLoaded('avatar', fn() => $this->avatar?->url)
        ];
    }
}
