<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ===== Parent Categories =====
        $electronicsId = Str::uuid();
        $fashionId     = Str::uuid();
        $homeId        = Str::uuid();

        DB::table('categories')->insert([
            [
                'id' => $electronicsId,
                'image_id' => null,
                'parent_id' => null,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'allows_adult_content' => false,
                'description' => 'Electronic devices and gadgets',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $fashionId,
                'image_id' => null,
                'parent_id' => null,
                'name' => 'Fashion',
                'slug' => 'fashion',
                'allows_adult_content' => false,
                'description' => 'Clothing, shoes, and accessories',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $homeId,
                'image_id' => null,
                'parent_id' => null,
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'allows_adult_content' => false,
                'description' => 'Furniture and home essentials',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ===== Child Categories =====
        DB::table('categories')->insert([
            // Electronics
            [
                'id' => Str::uuid(),
                'image_id' => null,
                'parent_id' => $electronicsId,
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'allows_adult_content' => false,
                'description' => 'Mobile phones and accessories',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'image_id' => null,
                'parent_id' => $electronicsId,
                'name' => 'Laptops',
                'slug' => 'laptops',
                'allows_adult_content' => false,
                'description' => 'Laptops and notebooks',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fashion
            [
                'id' => Str::uuid(),
                'image_id' => null,
                'parent_id' => $fashionId,
                'name' => 'Men Fashion',
                'slug' => 'men-fashion',
                'allows_adult_content' => false,
                'description' => 'Mens clothing and accessories',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'image_id' => null,
                'parent_id' => $fashionId,
                'name' => 'Women Fashion',
                'slug' => 'women-fashion',
                'allows_adult_content' => false,
                'description' => 'Womens clothing and accessories',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Home
            [
                'id' => Str::uuid(),
                'image_id' => null,
                'parent_id' => $homeId,
                'name' => 'Furniture',
                'slug' => 'furniture',
                'allows_adult_content' => false,
                'description' => 'Tables, chairs, sofas',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
