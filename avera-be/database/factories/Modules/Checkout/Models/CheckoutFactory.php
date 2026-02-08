<?php

namespace Database\Factories\Modules\Checkout\Models;

use App\Modules\Checkout\Models\Checkout;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CheckoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
      protected $model = Checkout::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => User::factory(),
            'user_address_id' => UserAddress::factory(),

            'subtotal' => 0,
            'shipping_cost' => 0,
            'total_price' => 0,

            'status' => 'pending',
            'payment_method' => 'cod',
        ];
    }

    /**
     * Checkout sudah terkunci (optional)
     */
    public function locked()
    {
        return $this->state(fn () => [
            'status' => 'locked',
        ]);
    }
}
