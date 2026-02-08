<?php

namespace App\Modules\Order\Services;

use App\Helpers\UserContext;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Services\CheckoutOrderService;
use App\Modules\Checkout\Services\CheckoutService;
use App\Modules\Order\Contracts\OrderServiceInterface;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Order\Repositories\OrderItemRepository;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Payment\Repositories\PaymentRepository;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Voucher\Repositories\UserVoucherRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{

    use CacheVersionable;

    public function __construct(
        private OrderRepository $orderRepository,
        private OrderItemRepository $orderItemRepository,
        private CartRepository $cartRepository,
        private UserVoucherRepository $userVoucherRepository,
        private CheckoutService $checkoutService,
        private CheckoutOrderService $checkoutOrder,
        private PaymentService $paymentService,
        private PaymentRepository $paymentRepository,
        private ProductRepository $productRepository,
    ) {}

    public function filter(array $filters): LengthAwarePaginator
    {
        return $this->orderRepository->filter($filters, UserContext::current());
    }
    public function get(array $filters): ?LengthAwarePaginator
    {
        return $this->orderRepository->get($filters);
    }
    public function getUserOrder(array $filters, string $userId): ?LengthAwarePaginator
    {
        $filters['user_id'] = $userId;
        return $this->filter($filters);
    }
    public function find(string $id): ?Order
    {
        return $this->orderRepository->findOrFail($id);
    }
    public function findItem(string $id): ?OrderItem
    {
        return $this->orderItemRepository->findOrFail($id);
    }
    public function store(string $checkoutId): array
    {
        return DB::transaction(function () use ($checkoutId) {

            $checkout = $this->checkoutService->find($checkoutId);
            $order = $this->checkoutOrder->convertToOrder($checkout);
            if ($checkout->payment_method === 'cod') {
                $this->orderRepository->update($order, [
                    'status' => 'awaiting_payment'
                ]);
                $this->paymentRepository->store([
                    'order_id' => $order->id,
                    'payment_method' => 'cod',
                    'gross_amount' => $order->total_price
                ]);
                $this->markCodProcessed($order);
                return  ['order' => $order, 'snap_token' => null, 'client_key' => null];
            }

            $snapToken = $this->paymentService->createSnapToken($order);
            $result = ['order' => $order, 'snap_token' => $snapToken, 'client_key' => config('midtrans.client_key')];
            return $result;
        });
    }
    private function markCodProcessed(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = $this->productRepository->find($item->product_id);
                $this->productRepository->deductStock($product, $item->quantity);
            }
        });
    }
}
