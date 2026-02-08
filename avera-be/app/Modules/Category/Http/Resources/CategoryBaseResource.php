<?php

namespace App\Modules\Category\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryBaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,

            'image' => $this->image
                ? $this->image->cloudinaryUrl()
                : null,

            "parent" => new CategoryResource(
                $this->whenLoaded('parent')
            ),

            "children" => CategoryResource::collection(
                $this->whenLoaded('children')
            )
        ];
    }
}
