<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\Province;
use App\Services\Shipment\RajaOngkirService;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $service = app(RajaOngkirService::class);

        Province::chunk(5, function ($provinces) use ($service) {
            foreach ($provinces as $province) {

                $cities = $service->getCitiesByProvince($province->rajaongkir_id);

                foreach ($cities as $city) {
                    City::updateOrCreate(
                        [
                            'rajaongkir_id' => $city['id'],
                        ],
                        [
                            'province_id' => $province->id,
                            'name' => $city['name'],
                        ]
                    );
                }
            }
        });
    }
}

