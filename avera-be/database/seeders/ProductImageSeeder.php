<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $product = DB::table('products')->first();
        $images = DB::table('images')->get();

        foreach ($images as $index => $image) {
            DB::table('product_images')->insert([
                'id' => Str::uuid(),
                'product_id' => $product->id,
                'image_id' => $image->id,
                'is_primary' => $index === 0,
                'position' => $index,
                'replace_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
