<?php

namespace Database\Factories\Modules\Order\Models;

use App\Modules\Order\Models\OrderItem;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = $this->faker->numberBetween(1,5);

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
            'discount' => $this->faker->numberBetween(0, 5000),
            'weight' => $product->weight,
            'user_voucher_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
