<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DistrictExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('backups/districts.csv');

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

                DB::table('districts')->updateOrInsert(
                    ['rajaongkir_id' => $data['rajaongkir_id']],
                    [
                        'city_id'   => $data['city_id'],
                        'name'          => $data['name'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }

            DB::commit();
            $this->command->info('Districts seeded successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error($e->getMessage());
        }
    }
}
