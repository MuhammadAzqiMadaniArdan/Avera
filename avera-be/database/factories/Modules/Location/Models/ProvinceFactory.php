<?php

namespace Database\Factories\Modules\Location\Models;

use App\Modules\Location\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProvinceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Province::class;

    public function definition(): array
    {
        return [
            'rajaongkir_id' => $this->faker->unique()->numberBetween(1, 100),
            'name' => $this->faker->state,
        ];
    }
}
