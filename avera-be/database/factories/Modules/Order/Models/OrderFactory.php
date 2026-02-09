<?php

namespace Database\Factories\Modules\Order\Models;

use App\Modules\Order\Models\Order;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'subtotal' => $this->faker->numberBetween(10000, 500000),
            'shipping_cost' => $this->faker->numberBetween(5000, 20000),
            'total_price' => function(array $attrs) {
                return $attrs['subtotal'] + $attrs['shipping_cost'];
            },
            'status' => $this->faker->randomElement(['pending','awaiting_payment','paid','processing','shipped','completed','cancelled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
