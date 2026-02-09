<?php

namespace App\Modules\Checkout\Services;

use App\Exceptions\CheckoutAlreadyProcessedException;
use App\Exceptions\CheckoutItemEmptyException;
use App\Exceptions\CheckoutShipmentMissingException;
use App\Exceptions\OrderStockIsNotEnough;
use App\Exceptions\OrderWithEmptyCartException;
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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutOrderService
{
    use CacheVersionable;
    public function __construct(
        private UserVoucherRepository $userVoucherRepository,
        private OrderRepository $orderRepository,
        private OrderItemRepository $orderItemRepository,
        private ShipmentRepository $shipmentRepository,
        private CartRepository $cartRepository
    ) {}
    public function convertToOrder(Checkout $checkout)
    {
        Gate::authorize('checkoutToOrder', $checkout);

        if ($checkout->status !== 'pending') {
            throw new CheckoutAlreadyProcessedException();
        }

        if (!$checkout->shipments) {
            throw new CheckoutShipmentMissingException();
        }

        if (!$checkout->items) {
            throw new CheckoutItemEmptyException();
        }


        return DB::transaction(function () use ($checkout) {

            $order = $this->orderRepository->store([
                'user_id' => $checkout->user_id,
                'subtotal' => $checkout->subtotal,
                'shipping_cost' => $checkout->shipping_cost,
                'total_price' => $checkout->total_price,
                'status' => $checkout->status,
            ]);

            foreach ($checkout->items as $item) {

                if ($item->quantity > $item->product->stock) {
                    throw new OrderStockIsNotEnough($item->product->stock);
                }

                if ($item->user_voucher_id) {
                    $userVoucher = $this->userVoucherRepository->find($item->user_voucher_id);
                    $userVoucher->update(['status' => 'used']);
                }

                $this->orderItemRepository->store([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'discount' => $item->discount,
                    'weight' => $item->weight,
                    'user_voucher_id' => $item->user_voucher_id ?? null,
                ]);

                $cartItem = $this->cartRepository->findByUserAndProduct($checkout->user_id, $item->product_id);

                if (!$cartItem) {
                    throw new OrderWithEmptyCartException();
                }

                $this->cartRepository->delete($cartItem);
            }

            $selectedShipments = $checkout->shipments
                ->where('user_address_id', $checkout->user_address_id)
                ->where('is_selected', true)
                ->groupBy('store_id');

            if ($selectedShipments->isEmpty()) {
                throw new Exception('Shipment belum dipilih');
            }

            foreach ($selectedShipments as $storeId => $shipments) {
                $shipment = $shipments->first();

                $this->shipmentRepository->store([
                    'order_id' => $order->id,
                    'store_id' => $storeId,
                    'user_address_id' => $checkout->user_address_id,

                    'courier_code' => $shipment->courier_code,
                    'courier_name' => $shipment->courier_name,
                    'service' => $shipment->service,
                    'description' => $shipment->description,

                    'min_days' => $shipment->min_days,
                    'max_days' => $shipment->max_days,
                    'tracking_number' => Str::random(10),
                    'shipping_cost' => $shipment->cost,

                    'recipient_name' => $checkout->userAddress->recipient_name,
                    'recipient_phone' => $checkout->userAddress->recipient_phone,
                    'recipient_address' => $checkout->userAddress->address,
                ]);
            }

            $checkout->update([
                'status' => 'locked'
            ]);

            return $order;
        });
    }
}
