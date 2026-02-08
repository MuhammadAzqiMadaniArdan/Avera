<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $store = DB::table('stores')->first();
        $category = DB::table('categories')->first();
        $primaryImage = DB::table('images')->first();

        DB::table('products')->insert([
            [
                'id' => Str::uuid(),
                'store_id' => $store->id,
                'category_id' => $category->id,
                'primary_image_id' => $primaryImage->id,
                'name' => 'Avera Wireless Headphone',
                'slug' => 'avera-wireless-headphone',
                'description' => 'High quality wireless headphone',
                'price' => 1299000,
                'stock' => 50,
                'status' => 'active',
                'age_rating' => 'all',
                'moderation_visibility' => 'public',
                'views_count' => 235,
                'sales_count' => 18,
                'published_at' => now(),
                'moderated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
