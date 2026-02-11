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
            'Automotive' => ['Cars', 'Motorcycles', 'Car Accessories', 'Motorcycle Accessories', 'Tools & Garage'],
            'Baby & Kids Fashion' => ['Baby Clothes', 'Kids Clothes', 'Shoes (Baby & Kids)', 'Accessories (Baby & Kids)', 'Toys'],
            'Beauty & Care' => ['Skincare', 'Makeup', 'Hair Care', 'Perfumes', 'Beauty Tools'],
            'Computer & Accessories' => ['Laptops', 'Desktops', 'Monitors', 'Keyboards & Mouse', 'Components'],
            'Electronics' => ['TV & Home Audio', 'Cameras', 'Kitchen Appliances', 'Gaming', 'Smart Devices'],
            'Fashion Accessories' => ['Bags (Fashion)', 'Watches (Fashion)', 'Jewelry', 'Belts', 'Hats & Caps', 'Eyewear'],
            'Food & Beverages' => ['Snacks', 'Beverages', 'Frozen Food', 'Fresh Food', 'Bakery'],
            'Health' => ['Medicine', 'Supplements', 'Medical Supplies', 'Fitness Equipment', 'First Aid'],
            'Hobby & Collection' => ['Collectibles', 'Model Kits', 'Board Games', 'Musical Instruments'],
            'Home & Living' => ['Furniture', 'Home Decor', 'Kitchenware', 'Bedding', 'Gardening'],
            'Men Bags' => ['Backpacks', 'Messenger Bags', 'Wallets', 'Briefcases'],
            'Men Clothes' => ['Shirts', 'Trousers', 'Jackets', 'Casual Wear', 'Formal Wear'],
            'Men Shoes' => ['Sneakers', 'Formal Shoes', 'Boots', 'Sandals'],
            'Mobile & Accessories' => ['Smartphones', 'Chargers & Cables', 'Cases & Covers', 'Power Banks'],
            'Mom & Baby' => ['Maternity Clothes', 'Baby Care', 'Feeding', 'Strollers'],
            'Photography' => ['DSLR Cameras', 'Lenses', 'Tripods', 'Lighting', 'Camera Bags'],
            'Souvenir & Party Supplies' => ['Party Decorations', 'Gift Wrapping', 'Souvenirs', 'Balloons'],
            'Sports & Outdoor' => ['Outdoor Gear', 'Sportswear', 'Shoes (Sports & Outdoor)'],
            'Stationery & Books' => ['Notebooks', 'Pens & Pencils', 'Books', 'Art Supplies'],
            'Watches' => ['Smart Watches', 'Analog Watches', 'Luxury Watches', 'Sports Watches'],
        ];

        foreach ($categories as $name => $subcategories) {
            $slug = Str::slug($name);
            $path = $slug."-icon";
            $categoryId = Str::uuid();
            $imageId = Str::uuid();

            // Insert image placeholder untuk kategori utama
            DB::table('images')->insert([
                'id' => $imageId,
                'owner_type' => 'category',
                'owner_id' => $categoryId,
                'disk' => 'cloudinary',
                'path' => "{$path}.png",
                'mime_type' => 'image/png',
                'size' => 0,
                'width' => 200,
                'height' => 200,
                'hash' => hash('sha256', $path),
                'moderation_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert kategori utama
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

            // Insert subchild untuk kategori
            foreach ($subcategories as $subName) {
                DB::table('categories')->insert([
                    'id' => Str::uuid(),
                    'image_id' => null,
                    'parent_id' => $categoryId,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            echo "Category: {$name} -> Subcategories: ".count($subcategories)."\n";
        }
    }
}
