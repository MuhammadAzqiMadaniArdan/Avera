<?php

namespace Database\Factories\Modules\Product\Models;

use App\Modules\Category\Models\Category;
use App\Modules\Image\Models\Image;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'store_id' => Store::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(10000, 5000000),
            'stock' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->numberBetween(100, 3000),
            'sales_count' => $this->faker->numberBetween(10, 10000),
            'status' => 'active',
            'age_rating' => 'all',
            'min_purchase' => $this->faker->numberBetween(1, 10),
            'hazard_type' => 'none',
            'condition' => 'new',
            'moderation_visibility' => 'public',
            'published_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {

            // primary image
            $primaryImage = Image::factory()->create([
                'owner_type' => 'product',
                'owner_id' => $product->id,
            ]);

            $product->update([
                'primary_image_id' => $primaryImage->id,
            ]);

            ProductImage::factory()->create([
                'product_id' => $product->id,
                'image_id' => $primaryImage->id,
                'is_primary' => true,
                'position' => 0,
            ]);

            // additional images
            Image::factory()
                ->count(rand(1, 4))
                ->create([
                    'owner_type' => 'product', // ✅ WAJIB
                    'owner_id' => $product->id,
                ])->each(function ($image, $index) use ($product) {
                    ProductImage::factory()->create([
                        'product_id' => $product->id,
                        'image_id' => $image->id,
                        'position' => $index + 1,
                    ]);
                });
        });
    }
}
