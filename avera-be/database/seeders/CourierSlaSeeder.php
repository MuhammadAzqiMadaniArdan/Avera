<?php

namespace Database\Seeders;

use App\Modules\Order\Models\CourierSla;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourierSlaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'courier_code' => 'jne',
                'courier_name' => 'JNE',
                'min_days' => 2,
                'max_days' => 4
            ],
            [
                'courier_code' => 'jnt',
                'courier_name' => 'J&T Express',
                'min_days' => 2,
                'max_days' => 4
            ],
            [
                'courier_code' => 'sicepat',
                'courier_name' => 'SiCepat',
                'min_days' => 2,
                'max_days' => 4
            ],
            [
                'courier_code' => 'anteraja',
                'courier_name' => 'AnterAja',
                'min_days' => 2,
                'max_days' => 4
            ],
            [
                'courier_code' => 'pos',
                'courier_name' => 'POS Indonesia',
                'min_days' => 2,
                'max_days' => 4
            ]
        ];

        foreach ($data as $item) {
            CourierSla::updateOrCreate(
                ['courier_code' => $item['courier_code']],
                $item
            );
        }
    }
}
