<?php

namespace Tests\Feature\Order;

use App\Http\Middleware\AuthenticateWithJwt;
use App\Http\Middleware\EnsureLocalExist;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Models\CheckoutItem;
use App\Modules\Checkout\Models\CheckoutShipment;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Province;
use App\Modules\Product\Models\Product;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreAddress;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAddress;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreatedOrderCodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('rajaongkir.couriers', [
            'cod' => [
                'code' => 'cod',
                'name' => 'Cash on Delivery',
                'services' => ['COD'],
            ],
        ]);
        config()->set('midtrans.client_key', 'test-key');

        $this->mockShipment();
    }

    protected function mockShipment(): void
    {
        $this->mock(\App\Modules\Order\Services\ShipmentService::class, function ($mock) {
            $mock->shouldReceive('createShipment')
                ->andReturn([
                    'tracking_number' => 'TEST123',
                    'status' => 'pending',
                ]);
        });
    }

    public function test_user_can_create_cod_order_with_address(): void
    {
        /**
         * Disable middleware (JWT + locale)
         */
        $this->withoutMiddleware([
            AuthenticateWithJwt::class,
            EnsureLocalExist::class,
            ThrottleRequests::class,
        ]);

        /**
         * Create users
         */
        $seller = User::factory()->create([
            'role' => 'seller'
        ]);
        $buyer  = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($buyer);
        /**
         * Create store
         */
        $store = Store::factory()->create([
            'user_id' => $seller->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'price'    => 100000,
            'stock'    => 10,
        ]);

        CartItem::factory()->create([
            'user_id' => $buyer->id,
            'product_id'    => $product->id,
            'quantity'    => 2,
        ]);
        /**
         * User address
         */
        $userAddress = UserAddress::factory()->create([
            'user_id'     => $buyer->id,
        ]);

        // 4️⃣ Checkout
        $checkout = Checkout::factory()->create([
            'user_id'        => $buyer->id,
            'user_address_id'        => $userAddress->id,
            'status'         => 'pending',
            'payment_method' => 'cod',
        ]);

        // 5️⃣ Checkout item (SNAPSHOT)
        CheckoutItem::factory()->create([
            'checkout_id' => $checkout->id,
            'product_id'  => $product->id,
            'store_id'    => $store->id,
            'price'       => $product->price,
            'quantity'    => 2,
            'subtotal'    => 200000,
            'weight'      => 2000,
        ]);

        // 6️⃣ Checkout shipment (selected)
        CheckoutShipment::factory()->create([
            'checkout_id'    => $checkout->id,
            'user_address_id'    => $checkout->user_address_id,
            'store_id'       => $store->id,
            'courier_code'   => 'cod',
            'courier_name'   => 'COD',
            'service'        => 'COD',
            'description'    => 'Cash on Delivery',
            'cost'           => 0,
            'is_selected'    => true,
        ]);


        // 7️⃣ Create order
        $response = $this->post("/api/v1/checkout/$checkout->id/place-order");

        // 8️⃣ Assert response
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Created order data successfully',
                'code' => 201,
            ]);

        $orderId = DB::table('orders')->latest()->value('id');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'status'  => 'awaiting_payment',
        ]);

        $this->assertDatabaseHas('shipments', [
            'store_id' => $store->id,
            'user_address_id' => $checkout->user_address_id,
            'courier_code' => 'cod',
            'status'       => 'pending',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity'        => 2,
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $orderId,
        ]);

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'stock' => 8,
        ]);
    }

    public function test_guest_cannot_create_cod_order()
    {
        $checkout = Checkout::factory()->create();

        $response = $this->post(
            "/api/v1/checkout/{$checkout->id}/place-order"
        );

        $response->assertStatus(401);
    }

    public function test_user_cannot_chekcout_other_users_data()
    {
        $this->withoutMiddleware([
            AuthenticateWithJwt::class,
            EnsureLocalExist::class,
            ThrottleRequests::class,
        ]);

        $buyer = User::factory()->create();
        $otherUser = User::factory()->create();

        $checkout = Checkout::factory()->for($otherUser)->create();

        $this->actingAs($buyer);

        $response = $this->post(
            "/api/v1/checkout/{$checkout->id}/place-order"
        );

        $response->assertStatus(403);
    }

    public function test_cannot_place_order_twice()
    {
        $this->withoutMiddleware([
            AuthenticateWithJwt::class,
            EnsureLocalExist::class,
            ThrottleRequests::class,
        ]);

        $user = User::factory()->create();
        $checkout = Checkout::factory()
            ->for($user)
            ->state(['status' => 'paid'])
            ->create();

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/v1/checkout/{$checkout->id}/place-order"
        );

        $response->assertStatus(409);
    }

    public function test_cannot_create_order_when_stock_is_not_enough()
    {
        $this->withoutMiddleware([
            AuthenticateWithJwt::class,
            EnsureLocalExist::class,
            ThrottleRequests::class,
        ]);

        $seller = User::factory()->create([
            'role' => 'seller'
        ]);
        $buyer  = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($buyer);
        /**
         * Create store
         */
        $store = Store::factory()->create([
            'user_id' => $seller->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'price'    => 100000,
            'stock'    => 1,
        ]);

        CartItem::factory()->create([
            'user_id' => $buyer->id,
            'product_id'    => $product->id,
            'quantity'    => 2,
        ]);
        /**
         * User address
         */
        $userAddress = UserAddress::factory()->create([
            'user_id'     => $buyer->id,
        ]);

        // 4️⃣ Checkout
        $checkout = Checkout::factory()->create([
            'user_id'        => $buyer->id,
            'user_address_id'        => $userAddress->id,
            'status'         => 'pending',
            'payment_method' => 'cod',
        ]);

        // 5️⃣ Checkout item (SNAPSHOT)
        CheckoutItem::factory()->create([
            'checkout_id' => $checkout->id,
            'product_id'  => $product->id,
            'store_id'    => $store->id,
            'price'       => $product->price,
            'quantity'    => 2,
            'subtotal'    => 200000,
            'weight'      => 2000,
        ]);

        // 6️⃣ Checkout shipment (selected)
        CheckoutShipment::factory()->create([
            'checkout_id'    => $checkout->id,
            'user_address_id'    => $checkout->user_address_id,
            'store_id'       => $store->id,
            'courier_code'   => 'cod',
            'courier_name'   => 'COD',
            'service'        => 'COD',
            'description'    => 'Cash on Delivery',
            'cost'           => 0,
            'is_selected'    => true,
        ]);


        $response = $this->postJson(
            "/api/v1/checkout/{$checkout->id}/place-order"
        );

        $response->assertStatus(422);
    }

    public function test_cannot_checkout_with_empty_cart()
    {
        $this->withoutMiddleware([
            AuthenticateWithJwt::class,
            EnsureLocalExist::class,
            ThrottleRequests::class,
        ]);

        $seller = User::factory()->create([
            'role' => 'seller'
        ]);
        $buyer  = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($buyer);
        /**
         * Create store
         */
        $store = Store::factory()->create([
            'user_id' => $seller->id,
        ]);

        $product = Product::factory()->create([
            'store_id' => $store->id,
            'price'    => 100000,
            'stock'    => 10,
        ]);

        /**
         * User address
         */
        $userAddress = UserAddress::factory()->create([
            'user_id'     => $buyer->id,
        ]);

        // 4️⃣ Checkout
        $checkout = Checkout::factory()->create([
            'user_id'        => $buyer->id,
            'user_address_id'        => $userAddress->id,
            'status'         => 'pending',
            'payment_method' => 'cod',
        ]);

        // 5️⃣ Checkout item (SNAPSHOT)
        CheckoutItem::factory()->create([
            'checkout_id' => $checkout->id,
            'product_id'  => $product->id,
            'store_id'    => $store->id,
            'price'       => $product->price,
            'quantity'    => 2,
            'subtotal'    => 200000,
            'weight'      => 2000,
        ]);

        // 6️⃣ Checkout shipment (selected)
        CheckoutShipment::factory()->create([
            'checkout_id'    => $checkout->id,
            'user_address_id'    => $checkout->user_address_id,
            'store_id'       => $store->id,
            'courier_code'   => 'cod',
            'courier_name'   => 'COD',
            'service'        => 'COD',
            'description'    => 'Cash on Delivery',
            'cost'           => 0,
            'is_selected'    => true,
        ]);



        $response = $this->postJson(
            "/api/v1/checkout/{$checkout->id}/place-order"
        );

        $response->assertStatus(422);
    }
}
