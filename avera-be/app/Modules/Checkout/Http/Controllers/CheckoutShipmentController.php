<?php

namespace App\Modules\Checkout\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Checkout\Http\Requests\StoreCheckoutShipment;
use App\Modules\Checkout\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckoutShipmentController extends Controller
{
    public function __construct(private CheckoutService $checkoutService) {}
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreCheckoutShipment $request,string $checkoutId)
    {
        $checkout = $this->checkoutService->storeCheckoutShipment($request->validated(),$checkoutId);
        return ApiResponse::successResponse($checkout, "Created checkout shipment data successfully", Response::HTTP_CREATED);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckoutRequest $request)
    {
        $checkout = $this->checkoutService->update($request->validated());
        return ApiResponse::successResponse(new CheckoutResource($checkout), "Updated checkout data successfully");
    }
}
