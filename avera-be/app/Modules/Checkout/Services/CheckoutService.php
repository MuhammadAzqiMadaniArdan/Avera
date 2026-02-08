<?php

namespace App\Modules\Checkout\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Checkout\Contracts\CheckoutServiceInterface;
use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Models\CheckoutShipment;
use App\Modules\Checkout\Repositories\CheckoutRepository;
use App\Modules\Checkout\Repositories\CheckoutShipmentRepository;
use App\Modules\Order\Repositories\OrderItemRepository;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Order\Repositories\ShipmentRepository;
use App\Modules\Order\Services\ShipmentService;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Voucher\Repositories\UserVoucherRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService implements CheckoutServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private CheckoutRepository $checkoutRepository,
        private CartRepository $cartRepository,
        private UserVoucherRepository $userVoucherRepository,
        private OrderRepository $orderRepository,
        private OrderItemRepository $orderItemRepository,
        private CheckoutOrderService $checkoutOrder,
        private CheckoutCartService $checkoutCart,
        private ShipmentRepository $shipmentRepository,
        private ShipmentService $shipmentService,
        private UserRepository $userRepository,
        private CheckoutShipmentRepository $checkoutShipmentRepository,
    ) {}
    public function get(string $userId): ?Checkout
    {
        return $this->checkoutRepository->get($userId);
    }
    public function find(string $id): ?Checkout
    {
        return $this->checkoutRepository->find($id);
    }

    public function storeUserAddress(array $data, string $checkoutId)
    {

        $checkout = $this->checkoutRepository->find($checkoutId);
        if (!$checkout) throw new ResourceNotFoundException('checkout');

        $checkout->shipment->createOrUpdate([
            'checkout_id' => $checkout->id,
        ], [
            'courier' => $data['courier'],
            'service' => $data['service'],
            'etd' => $data['etd'],
            'cost' => "",
        ]);
    }
    public function storeCheckoutShipment(array $data, string $checkoutId)
    {
        $checkout = $this->checkoutRepository->find($checkoutId);
        if (!$checkout) throw new ResourceNotFoundException('checkout');
        Gate::authorize('update', $checkout);
        if ($checkout->status !== 'draft') {
            throw ValidationException::withMessages([
                'checkout' => 'Checkout sudah dikunci'
            ]);
        }
        $weight = $checkout->items->sum('weight');
        $cost = $this->shipmentService->calculate(
            config('rajaongkir.default_origin_city_id'),
            $checkout->userAddress->shipping_city_id,
            $weight,
            $data['courier']
        );
        $shipment = $checkout->shipment()->updateOrCreate(
            ['checkout_id' => $checkout->id],
            [
                'courier' => $data['courier'],
                'service' => $data['service'],
                'etd' => $data['etd'] ?? null,
                'cost' => $cost,
            ]
        );
        return $shipment;
    }
    public function update(array $data, string $id): Checkout
    {
        $checkout = $this->checkoutRepository->find($id);
        Gate::authorize('update', $checkout);

        DB::transaction(function () use ($checkout, $data) {

            // 1️⃣ alamat berubah → recalculate shipment (ONLY CASE)
            if (!empty($data['user_address_id'])) {

                if ($data['user_address_id'] === $checkout->user_address_id || $data['user_address_id'] === $checkout->shipments?->user_address_id) {
                    return;
                }

                $this->checkoutRepository->update($checkout, ['user_address_id' => $data['user_address_id']]);

                $this->checkoutCart->checkoutShipment($checkout);

                $this->checkoutCart->refreshCheckoutShipping($checkout);

                return;
            }

            // multi-store  
            if (!empty($data['shipment_id']) && !empty($data['store_id'])) {
                $this->selectShipmentPerStore(
                    $checkout,
                    $data['store_id'],
                    $data['shipment_id']
                );
            }

            if (!empty($data['payment_method'])) {
                $this->checkoutRepository->update($checkout, ['payment_method' => $data['payment_method']]);
            }
        });

        return $checkout->fresh(['items.store', 'items.product', 'userAddress', 'user.userAddresses',  'shipments']);
    }

    public function delete(string $id): bool
    {
        $checkout = $this->checkoutRepository->find($id);
        return $this->checkoutRepository->delete($checkout);
    }

    private function selectShipmentPerStore(
        Checkout $checkout,
        string $storeId,
        string $shipmentId
    ): void {

        $selectedShipment = $this->checkoutShipmentRepository->findSelectedShipment($checkout->id, $storeId);

        if ($selectedShipment && $selectedShipment->id === $shipmentId) {
            return;
        }

        $shipment = $this->checkoutShipmentRepository->findByCheckoutAndStore($checkout->id, $shipmentId, $storeId);

        // select new shipment
        $this->checkoutShipmentRepository->update($selectedShipment, ['is_selected' => false]);
        $shipment = $this->checkoutShipmentRepository->update($shipment, ['is_selected' => true]);

        $data = [
            'shipping_cost' => $shipment->cost,
            'total_price' => $checkout->subtotal + $shipment->cost,
        ];

        $this->checkoutRepository->update($checkout, $data);
    }
}
