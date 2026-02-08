<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'id' => Str::uuid(),
                'user_id' => DB::table('users')->first()->id,
                'logo_image_id' => null,
                'name' => 'Avera Official Store',
                'slug' => 'avera-official-store',
                'description' => 'Official store for Avera products',
                'status' => 'active',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => null,
                'rating' => 4.8,
                'rating_avg' => 4.8,
                'rating_count' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
