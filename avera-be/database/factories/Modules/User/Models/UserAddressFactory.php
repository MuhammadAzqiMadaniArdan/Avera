<?php

namespace Database\Factories\Modules\User\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Province;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => User::factory(),

            'label' => 'Rumah',
            'recipient_name' => $this->faker->name,
            'recipient_phone' => '08' . $this->faker->numerify('########'),

            // ⚠️ MINIMAL → TIDAK hit logic ongkir
            'province_id' => Province::factory(),
            'city_id' => City::factory(),
            'district_id' => District::factory(),
            'village_id' => 1,

            'province_name' => 'Dummy Province',
            'city_name' => 'Dummy City',
            'district_name' => 'Dummy District',
            'village_name' => 'Dummy Village',

            'postal_code' => '12345',
            'address' => $this->faker->address,
            'other' => null,

            'is_default' => true,
        ];
    }

    /**
     * 🔥 OPTIONAL — kalau test butuh lokasi real (ongkir)
     */
    public function withLocation()
    {
        return $this->state(function () {
            $province = Province::factory()->create();
            $city = City::factory()->create([
                'province_id' => $province->id,
            ]);
            $district = District::factory()->create([
                'city_id' => $city->id,
            ]);

            return [
                'province_id' => $province->id,
                'city_id' => $city->id,
                'district_id' => $district->id,

                'province_name' => $province->name ?? 'Province',
                'city_name' => $city->name ?? 'City',
                'district_name' => $district->name ?? 'District',
            ];
        });
    }
}
