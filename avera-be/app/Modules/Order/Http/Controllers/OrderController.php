<?php

namespace App\Modules\Order\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Order\Http\Requests\CallbackPaymentRequest;
use App\Modules\Order\Http\Requests\StoreOrderCartRequest;
use App\Modules\Order\Http\Requests\StoreOrderRequest;
use App\Modules\Order\Http\Resources\OrderCreatedResource;
use App\Modules\Order\Http\Resources\OrderItemResource;
use App\Modules\Order\Http\Resources\OrderResource;
use App\Modules\Order\Services\OrderService;
use App\Modules\Order\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentService $paymentService
    ) {}
    /**
     * Display a listing of the app order resource.
     */
    public function index()
    {
        $filters = [
            'status' => request()->query('status'),
            'sort_direction' => request()->query('sort', 'desc'),
            'per_page' => request()->query('per_page', 24),
        ];
        $order = $this->orderService->get($filters);
        return ApiResponse::successResponse(
            OrderResource::collection($order),
            "Get order data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $order->currentPage(),
                "per_page" => $order->perPage(),
                "total" => $order->total(),
                "last_page" => $order->lastPage(),
            ]
        );
    }
    /**
     * Display a listing of the app order resource.
     */
    public function indexPurchase()
    {
        $filters = [
            'status' => request()->query('status'),
        ];
        $user = auth()->user();
        $order = $this->orderService->getUserOrder($filters, $user->id);
        return ApiResponse::successResponse(
            OrderResource::collection($order),
            "Get order data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $order->currentPage(),
                "per_page" => $order->perPage(),
                "total" => $order->total(),
                "last_page" => $order->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(string $checkoutId)
    {
        $result = $this->orderService->store($checkoutId);
        return ApiResponse::successResponse(new OrderCreatedResource($result), "Created order data successfully", Response::HTTP_CREATED);
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function paymentCallback(CallbackPaymentRequest $request)
    {
        $result = $this->paymentService->callback($request->validated());
        return ApiResponse::successResponse(null, "Payment successfully", Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->orderService->find($id);
        return ApiResponse::successResponse(new OrderResource($order), "Get order data with id $id successfully");
    }
    /**
     * Display the specified resource.
     */
    public function showItem(string $id)
    {
        $order = $this->orderService->findItem($id);
        return ApiResponse::successResponse(new OrderItemResource($order), "Get order data with id $id successfully");
    }
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $order = $this->orderService->get($perPage);
        return ApiResponse::successResponse(
            OrderResource::collection($order),
            "Get order data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $order->currentPage(),
                "per_page" => $order->perPage(),
                "total" => $order->total(),
                "last_page" => $order->lastPage(),
            ]
        );
    }
}
