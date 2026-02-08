<?php

namespace Database\Factories\Modules\Store\Models;

use App\Modules\Image\Models\Image;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->unique()->company(),
            'slug' => $this->faker->unique()->slug(),
            'status' => 'active',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'rating_avg' => $this->faker->randomFloat(1, 3, 5),
            'rating_count' => $this->faker->numberBetween(1, 500),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Store $store) {

            // primary image
            $logoImage = Image::factory()->create([
                'owner_type' => 'store_logo',
                'owner_id' => $store->id,
                'path' => 'samples/upscale-face-1.jpg'
            ]);

            $store->update([
                'logo_image_id' => $logoImage->id,
            ]);

        });
    }
}
