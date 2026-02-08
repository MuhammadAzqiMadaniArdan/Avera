<?php

namespace App\Modules\Order\Http\Resources;

use App\Modules\User\Http\Resources\UserBaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user' => new UserBaseResource(
                $this->whenLoaded('user')
            ),
            'updated_at' => $this->updated_at
        ];
    }
}
