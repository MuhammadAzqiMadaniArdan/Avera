<?php

namespace App\Modules\Voucher\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStoreVoucherRequest;
use App\Modules\Voucher\Http\Requests\StoreSellerVoucherRequest;
use App\Modules\Voucher\Http\Resources\StoreVoucherResource;
use App\Modules\Voucher\Services\StoreVoucherService;
use Illuminate\Http\Response;

class StoreVoucherController extends Controller
{
    public function __construct(private StoreVoucherService $storeVoucherService) {}
    /**
     * Display a listing of the app storeVoucher resource.
     */
    public function index()
    {
        $perPage = request()->query('per_page');
        $storeVoucher = $this->storeVoucherService->get($perPage);
        return ApiResponse::successResponse(
            StoreVoucherResource::collection($storeVoucher),
            "Get storeVoucher data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $storeVoucher->currentPage(),
                "per_page" => $storeVoucher->perPage(),
                "total" => $storeVoucher->total(),
                "last_page" => $storeVoucher->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreSellerVoucherRequest $request)
    {
        $storeVoucher = $this->storeVoucherService->store($request->validated());
        return ApiResponse::successResponse(new StoreVoucherResource($storeVoucher), "Created storeVoucher data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $storeVoucher = $this->storeVoucherService->find($id);
        return ApiResponse::successResponse(new StoreVoucherResource($storeVoucher), "Get storeVoucher $id data successfully");
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreVoucherRequest $request, string $id)
    {
        $storeVoucher = $this->storeVoucherService->update($id, $request->validated());
        return ApiResponse::successResponse(new StoreVoucherResource($storeVoucher), "Updated storeVoucher data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->storeVoucherService->delete($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }
}
