<?php

namespace Database\Factories\Modules\Image\Models;

use App\Modules\Image\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        $images = [
            'indomie_ayam_bawang.jpg',
            'cld-sample-4.jpg',
        ];

        $path = $this->faker->randomElement($images);

        return [
            'id' => Str::uuid(),
            'owner_type' => null,
            'owner_id' => null,
            'disk' => 'cloudinary',
            'path' => $path,
            'mime_type' => 'image/jpeg',
            'size' => 200000,
            'width' => 1000,
            'height' => 1000,
            'hash' => hash('sha256', Str::random()),
            'moderation_status' => 'approved',
        ];
    }
}
