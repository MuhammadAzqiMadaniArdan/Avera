<?php

namespace App\Modules\Payment\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Order\Services\PaymentService;
use App\Modules\Payment\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function getSnapToken(string $orderId) {
        $payment = $this->paymentService->getSnapToken($orderId);
        return ApiResponse::successResponse(
            ['snap_token' => $payment->snap_token]
            ,"Berhasil Mengambil Snap token");
    }
}
