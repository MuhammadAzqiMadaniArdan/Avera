<?php

namespace Database\Factories\Modules\Checkout\Models;

use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Models\CheckoutItem;
use App\Modules\Product\Models\Product;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CheckoutItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CheckoutItem::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'checkout_id' => Checkout::factory(),
            'product_id' => Product::factory(),
            'store_id' => Store::factory(),

            'price' => 100000,
            'quantity' => 1,
            'subtotal' => 100000,
            'weight' => 1000,

            'discount' => 0,
            'user_voucher_id' => null,
            'promotion_id' => null,
        ];
    }
}
