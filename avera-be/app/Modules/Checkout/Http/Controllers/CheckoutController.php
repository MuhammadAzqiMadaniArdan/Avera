<?php

namespace App\Modules\Checkout\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Checkout\Http\Requests\StoreCheckoutRequest;
use App\Modules\Checkout\Http\Requests\UpdateCheckoutRequest;
use App\Modules\Checkout\Http\Resources\CheckoutResource;
use App\Modules\Checkout\Services\CheckoutCartService;
use App\Modules\Checkout\Services\CheckoutOrderService;
use App\Modules\Checkout\Services\CheckoutService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService,
        private CheckoutCartService $checkoutCartService,
    ) {}

    /**
     * Display a listing of the app checkout resource.
     */
    public function index()
    {
        $user = auth()->user();
        $checkout = $this->checkoutService->get($user->id);
        return ApiResponse::successResponse(
            new CheckoutResource($checkout),
            "Get checkout data successfully"
        );
    }

    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreCheckoutRequest $request)
    {
        $user = auth()->user();
        $checkout = $this->checkoutCartService->store($request->validated(), $user->id);
        return ApiResponse::successResponse(new CheckoutResource($checkout), "Created checkout data successfully", Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckoutRequest $request,string $id)
    {
        $checkout = $this->checkoutService->update($request->validated(),$id);
        return ApiResponse::successResponse(new CheckoutResource($checkout), "Updated checkout data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkoutService->delete($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }
}
