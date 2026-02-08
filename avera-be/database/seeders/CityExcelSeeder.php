<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CityExcelSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('backups/cities.csv');

        if (!File::exists($path)) {
            $this->command->error("CSV file not found: {$path}");
            return;
        }

        DB::beginTransaction();

        try {
            $rows = array_map('str_getcsv', file($path));

            $header = array_map('trim', array_shift($rows));

            foreach ($rows as $row) {
                $data = array_combine($header, $row);

                DB::table('cities')->updateOrInsert(
                    ['id' => $data['id']],
                    [
                        'rajaongkir_id' => $data['rajaongkir_id'],
                        'province_id'   => $data['province_id'],
                        'name'          => $data['name'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }

            DB::commit();
            $this->command->info('Cities seeded successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error($e->getMessage());
        }
    }
}
