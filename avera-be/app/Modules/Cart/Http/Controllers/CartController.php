<?php

namespace App\Modules\Cart\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCartRequest;
use App\Modules\Cart\Http\Requests\StoreCartRequest;
use App\Modules\Cart\Http\Resources\CartItemResource;
use App\Modules\Cart\Http\Resources\CartStoreGroupResource;
use App\Modules\Cart\Services\CartCommandService;
use App\Modules\Cart\Services\CartQueryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct(
        private CartQueryService $query,
        private CartCommandService $command
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = AuthHelper::uuid($request);
        $cartGrouped = $this->query->get($userId)->values();
        return ApiResponse::successResponse(
            CartStoreGroupResource::collection($cartGrouped),
            "Get cart group data successfully",
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreCartRequest $request)
    {
        $userId = AuthHelper::uuid($request);
        $cartItem = $this->command->store($request->validated(), $userId);
        return ApiResponse::successResponse(new CartItemResource($cartItem), "Created cartItem data successfully", Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, string $id)
    {
        $cartItem = $this->command->update($id, $request->validated());
        return ApiResponse::successResponse(new CartItemResource($cartItem), "Updated cart item data successfully");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->command->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
    /**
     * Flush the specified resource from storage.
     */
    public function flush(Request $request)
    {
        $userId = AuthHelper::uuid($request);
        $this->command->flush($userId);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
