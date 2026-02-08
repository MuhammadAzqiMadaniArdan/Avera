<?php

namespace App\Modules\Category\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'status' => $this->status,

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
