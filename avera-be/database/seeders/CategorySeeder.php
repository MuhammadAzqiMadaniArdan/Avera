<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
 public function run(): void
    {
        $categories = [
            'Automotive',
            'Baby & Kids Fashion',
            'Beauty & Care',
            'Computer & Accessories',
            // 'Deals Nearby',
            'Electronics',
            'Fashion Accessories',
            'Food & Beverages',
            'Health',
            'Hobby & Collection',
            'Home & Living',
            'Men Bags',
            'Men Clothes',
            'Men Shoes',
            'Mobile & Accessories',
            'Mom & Baby',
            // 'Muslim Fashion',
            'Photography',
            'Souvenir & Party Supplies',
            'Sports & Outdoor',
            'Stationery & Books',
            // 'Vouchers',
            'Watches',
            // 'Women Bags',
            // 'Women Clothes',
            // 'Women Shoes',
        ];

        foreach ($categories as $name) {
            $slug = Str::slug($name)."-icon";
            $categoryId = Str::uuid();
            $imageId = Str::uuid();

            // Insert image placeholder
            DB::table('images')->insert([
                'id' => $imageId,
                'owner_type' => 'category',
                'owner_id' => $categoryId,
                'disk' => 'cloudinary', // atau 'cloudinary'
                'path' => "{$slug}.png", // nama file sesuai slug
                'mime_type' => 'image/png',
                'size' => 0,
                'width' => 200,
                'height' => 200,
                'hash' => hash('sha256', $slug),
                'moderation_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert category
            DB::table('categories')->insert([
                'id' => $categoryId,
                'image_id' => $imageId,
                'parent_id' => null,
                'name' => $name,
                'slug' => $slug,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "Category: {$name} -> Slug: {$slug}\n"; // info slug untuk catatan
        }
    }
}
