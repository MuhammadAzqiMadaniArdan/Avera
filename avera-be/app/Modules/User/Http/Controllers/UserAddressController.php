<?php

namespace App\Modules\User\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Requests\StoreUserAddressRequest;
use App\Modules\User\Http\Requests\UpdateUserAddressRequest;
use App\Modules\User\Http\Resources\UserAddressResource;
use App\Modules\User\Services\UserAddressService;
use Illuminate\Http\Response;

class UserAddressController extends Controller
{
    public function __construct(
        private UserAddressService $userAddressService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $userAddress = $this->userAddressService->get($user->id);
        return ApiResponse::successResponse(
            UserAddressResource::collection($userAddress),
            "Get User Address data successfully"
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreUserAddressRequest $request)
    {
        $user = auth()->user();
        $userAddress = $this->userAddressService->store($request->validated(),$user->id);
        return ApiResponse::successResponse(new UserAddressResource($userAddress), "Created user address data successfully", Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userAddress = $this->userAddressService->find($id);
        return ApiResponse::successResponse(new UserAddressResource($userAddress), "Get userAddress data successfully");
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserAddressRequest $request, string $id)
    {
        $userAddress = $this->userAddressService->update($id, $request->validated());
        return ApiResponse::successResponse(new UserAddressResource($userAddress), "Updated userAddress data successfully");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userAddressService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
