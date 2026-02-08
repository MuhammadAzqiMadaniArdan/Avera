<?php

namespace Database\Factories\Modules\Location\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'rajaongkir_id' => $this->faker->unique()->numberBetween(100, 999),
            'province_id' => Province::factory(),
            'name' => $this->faker->city,
        ];
    }
}
