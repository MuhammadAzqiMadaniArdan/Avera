<?php

namespace App\Modules\Voucher\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Voucher\Http\Requests\ClaimCampaignVoucherRequest;
use App\Modules\Voucher\Http\Requests\ClaimStoreVoucherRequest;
use App\Modules\Voucher\Http\Requests\StoreUserVoucherRequest;
use App\Modules\Voucher\Http\Requests\UpdateUserVoucherRequest;
use App\Modules\Voucher\Http\Resources\UserVoucherResource;
use App\Modules\Voucher\Services\UserVoucherService;
use Exception;
use Illuminate\Http\Response;

class UserVoucherController extends Controller
{
    public function __construct(
        private UserVoucherService $userVoucherService
    ) {}
    /**
     * Display a listing of the app storeVoucher resource.
     */
    public function index()
    {
        $perPage = request()->query('per_page');
        $storeVoucher = $this->userVoucherService->get($perPage);
        return ApiResponse::successResponse(
            UserVoucherResource::collection($storeVoucher),
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
    public function claimStore(ClaimStoreVoucherRequest $request)
    {
        $validated = $request->validated();
        $userVoucher = $this->userVoucherService->claim($validated['voucher_id'], 'store');
        return ApiResponse::successResponse(new UserVoucherResource($userVoucher), "Created storeVoucher data successfully", Response::HTTP_CREATED);
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function claimCampaign(ClaimCampaignVoucherRequest $request)
    {
        $validated = $request->validated();
        $userVoucher = $this->userVoucherService->claim($validated['voucher_id'], 'campaign');
        return ApiResponse::successResponse(new UserVoucherResource($userVoucher), "Created storeVoucher data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $storeVoucher = $this->userVoucherService->find($id);
        return ApiResponse::successResponse(new UserVoucherResource($storeVoucher), "Get storeVoucher $id data successfully");
    }

    /**
     * Display the specified resource.
     */
    public function showByStore(string $storeId)
    {
        $user = auth()->user();
        if(!$user) throw new Exception('User Belum Login');
        $storeVoucher = $this->userVoucherService->getByStore($user->id,$storeId);
        return ApiResponse::successResponse(new UserVoucherResource($storeVoucher), "Get storeVoucher data successfully");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserVoucherRequest $request, string $id)
    {
        $storeVoucher = $this->userVoucherService->update($id, $request->validated());
        return ApiResponse::successResponse(new UserVoucherResource($storeVoucher), "Updated storeVoucher data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userVoucherService->delete($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }
}
