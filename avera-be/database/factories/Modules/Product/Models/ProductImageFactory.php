<?php

namespace Database\Factories\Modules\Product\Models;

use App\Modules\Product\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'is_primary' => false,
            'position' => 0,
            'replace_count' => 0,
            'last_replaced_at' => null,
        ];
    }
}
