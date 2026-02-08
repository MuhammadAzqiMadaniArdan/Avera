<?php

namespace App\Modules\Store\Http\Resources;

use App\Modules\Image\Http\Resources\ImageResource;
use App\Modules\User\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,

            'user' => new UserResource(
                $this->whenLoaded('user')
            ),

            'product_count' => $this->when(
                isset($this->products_count),
                $this->products_count
            ),
            'rating' => [
                'avg' => $this->rating_avg,
                'count' => $this->rating_count,
            ],
            'logo' => $this->whenLoaded('logo', fn() => $this->logo?->url),
        ];
    }
}
