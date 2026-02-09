<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Modules\Category\Models\Category;
use App\Modules\Image\Models\Image;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Order\Models\Review;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * 1️⃣ CATEGORY DULU (WAJIB)
         */
        // $mainCategories = Category::factory()
        //     ->count(24)
        //     ->create()
        //     ->each(function (Category $category) {

        //         $image = Image::factory()->create([
        //             'owner_type' => 'category',
        //             'owner_id' => $category->id,
        //         ]);

        //         $category->update([
        //             'image_id' => $image->id,
        //         ]);
        //     });

        // $subCategories = $mainCategories->flatMap(
        //     fn($cat) =>
        //     Category::factory()->count(2)->create([
        //         'parent_id' => $cat->id,
        //         'image_id' => null, // eksplisit biar jelas
        //     ])
        // );

        // $allCategories = $mainCategories->merge($subCategories);

        $this->call([
            CategorySeeder::class
        ]);

        $allCategories = Category::all();

        /**
         * 2️⃣ USERS
         */
        $sellers = User::factory()->count(10)->seller()->create();
        User::factory()->count(20)->create();

        /**
         * 3️⃣ STORES + PRODUCTS
         */
        $sellers->each(function ($seller) use ($allCategories) {

            $store = Store::factory()->create([
                'user_id' => $seller->id,
            ]);

            Product::factory()
                ->count(rand(3, 6))
                ->create([
                    'store_id' => $store->id,
                    'category_id' => $allCategories->random()->id,
                ]);
        });

        Order::factory()->count(20)->create()->each(function ($order) {
            OrderItem::factory()->count(rand(1, 5))->create(['order_id' => $order->id]);
        });
        Review::factory()->count(200)->create();
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // CategorySeeder::class,
            // StoreSeeder::class,
            // ImageSeeder::class,
            // ProductSeeder::class,
            // ProductImageSeeder::class,
            ProvinceSeeder::class,
            // CitySeeder::class,
            CityExcelSeeder::class,
            DistrictExcelSeeder::class,
            StoreAddressSeeder::class,
            CourierSlaSeeder::class,
        ]);
    }
}
