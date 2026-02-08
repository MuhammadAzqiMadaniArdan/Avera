<?php
namespace Database\Seeders;

use App\Modules\Location\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['rajaongkir_id' => 1, 'name' => 'NUSA TENGGARA BARAT (NTB)'],
            ['rajaongkir_id' => 2, 'name' => 'MALUKU'],
            ['rajaongkir_id' => 3, 'name' => 'KALIMANTAN SELATAN'],
            ['rajaongkir_id' => 4, 'name' => 'KALIMANTAN TENGAH'],
            ['rajaongkir_id' => 5, 'name' => 'JAWA BARAT'],
            ['rajaongkir_id' => 6, 'name' => 'BENGKULU'],
            ['rajaongkir_id' => 7, 'name' => 'KALIMANTAN TIMUR'],
            ['rajaongkir_id' => 8, 'name' => 'KEPULAUAN RIAU'],
            ['rajaongkir_id' => 9, 'name' => 'NANGGROE ACEH DARUSSALAM (NAD)'],
            ['rajaongkir_id' => 10, 'name' => 'DKI JAKARTA'],
            ['rajaongkir_id' => 11, 'name' => 'BANTEN'],
            ['rajaongkir_id' => 12, 'name' => 'JAWA TENGAH'],
            ['rajaongkir_id' => 13, 'name' => 'JAMBI'],
            ['rajaongkir_id' => 14, 'name' => 'PAPUA'],
            ['rajaongkir_id' => 15, 'name' => 'BALI'],
            ['rajaongkir_id' => 16, 'name' => 'SUMATERA UTARA'],
            ['rajaongkir_id' => 17, 'name' => 'GORONTALO'],
            ['rajaongkir_id' => 18, 'name' => 'JAWA TIMUR'],
            ['rajaongkir_id' => 19, 'name' => 'DI YOGYAKARTA'],
            ['rajaongkir_id' => 20, 'name' => 'SULAWESI TENGGARA'],
            ['rajaongkir_id' => 21, 'name' => 'NUSA TENGGARA TIMUR (NTT)'],
            ['rajaongkir_id' => 22, 'name' => 'SULAWESI UTARA'],
            ['rajaongkir_id' => 23, 'name' => 'SUMATERA BARAT'],
            ['rajaongkir_id' => 24, 'name' => 'BANGKA BELITUNG'],
            ['rajaongkir_id' => 25, 'name' => 'RIAU'],
            ['rajaongkir_id' => 26, 'name' => 'SUMATERA SELATAN'],
            ['rajaongkir_id' => 27, 'name' => 'SULAWESI TENGAH'],
            ['rajaongkir_id' => 28, 'name' => 'KALIMANTAN BARAT'],
            ['rajaongkir_id' => 29, 'name' => 'PAPUA BARAT'],
            ['rajaongkir_id' => 30, 'name' => 'LAMPUNG'],
            ['rajaongkir_id' => 31, 'name' => 'KALIMANTAN UTARA'],
            ['rajaongkir_id' => 32, 'name' => 'MALUKU UTARA'],
            ['rajaongkir_id' => 33, 'name' => 'SULAWESI SELATAN'],
            ['rajaongkir_id' => 34, 'name' => 'SULAWESI BARAT'],
        ];

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                ['rajaongkir_id' => $province['rajaongkir_id']],
                ['name' => $province['name']]
            );
        }
    }
}
