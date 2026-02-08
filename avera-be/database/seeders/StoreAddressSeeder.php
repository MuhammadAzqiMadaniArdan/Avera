<?php

namespace Database\Seeders;

use App\Modules\Store\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreAddressSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();

        if ($stores->isEmpty()) {
            $this->command->warn('No stores found, skipping StoreAddressSeeder.');
            return;
        }

        // Ambil province & city random dari DB (hasil ProvinceSeeder & CitySeeder)
        $provinces = DB::table('provinces')->get();
        $cities = DB::table('cities')->get();

        foreach ($stores as $store) {

            $province = $provinces->random();
            $city = $cities->where('province_id', $province->id)->random();

            DB::table('store_addresses')->insert([
                'id' => Str::uuid(),
                'store_id' => $store->id,
                'store_name' => $store->name,
                'phone_number' => '08' . rand(1111111111, 9999999999),

                'province_id' => $province->id,
                'city_id' => 83,
                'district_id' => 1,
                'village_id' => rand(10000, 99999),  // placeholder (non FK)

                'province_name' => $province->name,
                'city_name' => $city->name,
                'district_name' => 'District Dummy',
                'village_name' => 'Village Dummy',

                'postal_code' => (string) rand(10000, 99999),
                'address' => 'Jl. Gudang Utama No. ' . rand(1, 200),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
