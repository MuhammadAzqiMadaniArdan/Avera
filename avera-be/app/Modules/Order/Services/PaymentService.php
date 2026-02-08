<?php

namespace App\Modules\Order\Services;

use App\Events\PaymentSettled;
use App\Exceptions\ResourceNotFoundException;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Checkout\Services\CheckoutService;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Repositories\OrderItemRepository;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Order\Repositories\PaymentRepository;
use App\Modules\Payment\Models\Payment;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Voucher\Repositories\UserVoucherRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;

class PaymentService
{

    use CacheVersionable;

    public function __construct(
        private PaymentRepository $paymentRepository,
        private CartRepository $cartRepository,
        private UserVoucherRepository $userVoucherRepository,
        private CheckoutService $checkoutService,
        private OrderRepository $orderRepository,
        private OrderItemRepository $orderItemRepository,
        private ProductRepository $productRepository
    ) {}

    public function getUserOrder(string $id): ?LengthAwarePaginator
    {
        return $this->orderRepository->get([]);
    }
    public function find(string $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function createSnapToken(Order $order): string
    {
        $params = [
            'transaction_detail' =>
            [
                'order_number' => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' =>
            [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'enabled_payments' =>
            [
                'credit_card',
                'gopay',
                'shoppepay',
                'bank_transfer',
            ],
        ];

        $this->paymentRepository->store([
            'order_id' => $order->id,
            'payment_gateway' => 'midtrans',
            'transaction_id' => $order->id,
            'gross_amount' => $order->total_price,
            'status' => 'pending',
        ]);

        return Snap::getSnapToken($params);
    }

    public function callback(array $data): void
    {
        $signatureKey = hash(
            'sha512',
            $data['order_id'] . $data['status_code'] . $data['gross_amount'] . config('midtrans.server_key')
        );

        if (!hash_equals($signatureKey, $data['signature_key'])) {
            abort(403, 'Invalid signature');
        }

        $order = $this->orderRepository->find($data['order_id']);
        if (!$order) throw new ResourceNotFoundException('Order');
        $payment = $order->payment;
        if (!$payment) throw new Exception('Unknown Payment');

        if (in_array($payment->status, ['settlement', 'capture'])) {
            return;
        }
        $transactionStatus = $data['transaction_status'];
        match ($transactionStatus) {
            'settlement' => $this->markPaid($order, $payment, $transactionStatus),
            'capture' => $this->markPaid($order, $payment, $transactionStatus),
            'expire' => $this->markFailed($order, $payment, 'expired'),
            'cancel' => $this->markFailed($order, $payment, 'failed'),
            'deny' => $this->markFailed($order, $payment, 'failed'),
            'pending' => $this->markFailed($order, $payment, $transactionStatus),
            default => null
        };
    }

    private function markPaid(Order $order, Payment $payment, string $status)
    {
        return DB::transaction(function () use ($order, $payment, $status) {
            $this->orderRepository->update($order, ['status' => 'paid']);
            $this->paymentRepository->update($payment, ['status' => 'paid']);
            foreach ($order->items as $item) {
                $product = $this->productRepository->find($item->product_id);
                $this->productRepository->deductStock($product, $item->quantity);
            }
            event(new PaymentSettled($order));
        });
    }

    private function markFailed(Order $order, Payment $payment, string $status)
    {
        return DB::transaction(function () use ($order, $payment, $status) {
            $this->orderRepository->update($order, ['status' => 'cancelled']);
            $this->paymentRepository->update($payment, ['status' => $status]);
        });
    }
}
