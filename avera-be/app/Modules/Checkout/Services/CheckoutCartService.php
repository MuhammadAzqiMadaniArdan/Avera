<?php

namespace App\Modules\Checkout\Services;

use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Repositories\CheckoutRepository;
use App\Modules\Order\Repositories\OrderItemRepository;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Order\Repositories\ShipmentRepository;
use App\Modules\Order\Services\ShipmentService;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Voucher\Repositories\UserVoucherRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutCartService
{
    use CacheVersionable;
    protected array $couriers;
    public function __construct(
        private CheckoutRepository $checkoutRepository,
        private CartRepository $cartRepository,
        private UserVoucherRepository $userVoucherRepository,
        private ShipmentRepository $shipmentRepository,
        private ShipmentService $shipmentService,
        private UserRepository $userRepository
    ) {
        $this->couriers = config('rajaongkir.couriers');
    }
    public function store(array $data, string $userId): Checkout
    {
        return DB::transaction(function () use ($data, $userId) {

            $user = $this->userRepository->findWithAddress($userId);
            $checkout = $this->getOrResetCheckout($user);

            [$items, $subtotal] = $this->buildCheckoutItems($data['carts']);

            $checkout->update([
                'subtotal' => $subtotal,
            ]);


            $checkout->items()->createMany($items);

            $this->checkoutShipment(
                $checkout->load('items'),
                false
            );

            $this->refreshCheckoutShipping($checkout);

            return $checkout->load(['items.product', 'items.store', 'shipments']);
        });
    }


    private function getOrResetCheckout(User $user): Checkout
    {
        $checkout = $this->checkoutRepository->get($user->id);

        if ($checkout) {
            $checkout->items()->delete();
            $checkout->shipments()->delete();
            $checkout->update(['total_price' => 0]);
            return $checkout;
        }

        $userAddressId = null;
        if (!empty($user->userAddressDefault)) {
            $userAddressId = $user->userAddressDefault->id;
        }
        return $this->checkoutRepository->store([
            'user_id' => $user->id,
            'user_address_id' => $userAddressId,
            'total_price' => 0,
            'status' => 'pending'
        ]);
    }

    private function buildCheckoutItems(array $carts): array
    {
        $subtotal = 0;
        $items = [];

        foreach ($carts as $cartItem) {
            $cart = $this->cartRepository->findOrFail($cartItem['id']);

            $productPrice = $cart->product->price * $cart->quantity;
            $discount = $this->calculateDiscount($cartItem, $productPrice);

            $subtotal += ($productPrice - $discount);

            $items[] = [
                'product_id' => $cart->product_id,
                'store_id' => $cart->product->store_id,
                'product_name' => $cart->product->name,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
                'subtotal' => $productPrice,
                'weight' => $cart->product->weight,
                'discount' => $discount,
                'user_voucher_id' => $cartItem['user_voucher_id'] ?? null
            ];
        }

        return [$items, $subtotal];
    }


    private function calculateDiscount(array $cartItem, float $productPrice): float
    {
        if (empty($cartItem['user_voucher_id'])) {
            return 0;
        }

        $voucher = $this->userVoucherRepository->find($cartItem['user_voucher_id']);

        $this->validateVoucher($voucher);

        return min(
            $voucher->voucherable->discount_type === 'percentage'
                ? $productPrice * ($voucher->voucherable->discount_value / 100)
                : $voucher->voucherable->discount_value,
            $productPrice
        );
    }

    private function validateVoucher($voucher): void
    {
        if (!$voucher || in_array($voucher->status, ['used', 'expired'])) {
            throw new Exception('Voucher is invalid');
        }
    }

    public function checkoutShipment(Checkout $checkout): void
    {
        $address = $checkout->userAddress;
        if (!$address) return;

        $destinationDistrictId = $address->district->rajaongkir_id;

        $itemsByStore = $checkout->items->groupBy('store_id');

        foreach ($itemsByStore as $storeId => $items) {

            $weight = $items->sum(fn($item) => $item->weight * $item->quantity);

            $storeAddress = $items->first()->store->storeAddress;
            if (!$storeAddress) continue;

            $originDistrictId = $storeAddress->district->rajaongkir_id;

            foreach ($this->couriers as $index => $courier) {

                $services = $this->shipmentService->calculate(
                    $originDistrictId,
                    $destinationDistrictId,
                    $weight,
                    $courier['code']
                );

                if (empty($services)) {
                    continue;
                }

                foreach ($services as $serviceKey => $service) {
                    $this->checkoutRepository->shipmentStore([
                        'checkout_id'      => $checkout->id,
                        'store_id'         => $storeId,
                        'user_address_id'  => $address->id,
                        'courier_code'     => $service['courier_code'],
                        'courier_name'     => $service['courier_name'],
                        'service'          => $service['service'],
                        'description'      => $service['description'],
                        'etd'              => $service['etd_raw'],
                        'min_days'         => $service['min_days'],
                        'max_days'         => $service['max_days'],
                        'cost'             => $service['cost'],
                        'is_selected'      => $index === 0 && $serviceKey === 0,
                    ]);
                }
            }
        }
    }


    public function refreshCheckoutShipping(Checkout $checkout): void
    {
        $addressId = $checkout->user_address_id;
        if (!$addressId) return;

        $shippingTotal = $checkout->shipments()
            ->where('user_address_id', $addressId)
            ->where('is_selected', true)
            ->sum('cost');

        $checkout->update([
            'shipping_cost' => $shippingTotal,
            'total_price' => $checkout->subtotal + $shippingTotal,
        ]);
    }
}
