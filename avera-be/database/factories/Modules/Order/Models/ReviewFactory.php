<?php

namespace Database\Factories\Modules\Order\Models;

use App\Modules\Order\Models\Review;
use App\Modules\Order\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        // Ambil order item random supaya valid order_id & product_id
        $orderItem = OrderItem::inRandomOrder()->first() ?? OrderItem::factory()->create();

        return [
            'id' => Str::uuid(),
            'user_id' => $orderItem->order->user_id,
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating' => $this->faker->numberBetween(1,5),
            'comment' => $this->faker->optional(0.7)->sentence(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
