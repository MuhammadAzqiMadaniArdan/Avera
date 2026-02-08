<?php

namespace App\Modules\Image\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'hash' => $this->hash,
            'moderation_status' => $this->moderation_status,
            'moderation_reason' => $this->moderation_reason,
            'moderation_result' => $this->moderation_result
        ];
    }
}
