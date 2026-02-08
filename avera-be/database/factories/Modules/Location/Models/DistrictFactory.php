<?php

namespace Database\Factories\Modules\Location\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DistrictFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = District::class;

    public function definition(): array
    {
        return [
            'rajaongkir_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'city_id' => City::factory(),
            'name' => $this->faker->streetName,
        ];
    }
}
