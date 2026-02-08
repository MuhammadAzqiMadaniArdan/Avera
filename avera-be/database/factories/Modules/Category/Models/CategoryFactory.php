<?php

namespace Database\Factories\Modules\Category\Models;

use App\Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => ucfirst($this->faker->unique()->word()),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'allows_adult_content' => false,
            'status' => 'active',
        ];
    }
}
