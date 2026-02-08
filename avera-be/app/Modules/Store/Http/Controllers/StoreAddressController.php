<?php

namespace App\Modules\Store\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Store\Http\Requests\Address\StoreStoreAddressRequest;
use App\Modules\Store\Http\Requests\Address\UpdateStoreAddressRequest;
use App\Modules\Store\Http\Resources\StoreAddressResource;
use App\Modules\Store\Services\StoreAddressService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreAddressController extends Controller
{
    public function __construct(
        private StoreAddressService $storeAddressService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $storeAddress = $this->storeAddressService->get($user->id);
        return ApiResponse::successResponse(
            new StoreAddressResource($storeAddress),
            "Get Store Address data successfully"
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreStoreAddressRequest $request)
    {
        $user = auth()->user();
        $storeAddress = $this->storeAddressService->store($request->validated(),$user->id);
        return ApiResponse::successResponse(new StoreAddressResource($storeAddress), "Created store address data successfully", Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $storeAddress = $this->storeAddressService->find($id);
        return ApiResponse::successResponse(new StoreAddressResource($storeAddress), "Get storeAddress data successfully");
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreAddressRequest $request, string $id)
    {
        $storeAddress = $this->storeAddressService->update($id, $request->validated());
        return ApiResponse::successResponse(new StoreAddressResource($storeAddress), "Updated storeAddress data successfully");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->storeAddressService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
