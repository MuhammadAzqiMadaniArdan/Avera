<?php

namespace Database\Factories\Modules\Checkout\Models;

use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Models\CheckoutShipment;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CheckoutShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CheckoutShipment::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'checkout_id' => Checkout::factory(),
            'store_id' => Store::factory(),
            'user_address_id' => UserAddress::factory(),

            'courier_code' => 'cod',
            'courier_name' => 'COD',
            'service' => 'COD',
            'description' => 'Cash on Delivery',

            'etd' => null,
            'min_days' => null,
            'max_days' => null,

            'cost' => 0,
            'is_selected' => true,
        ];
    }
}
