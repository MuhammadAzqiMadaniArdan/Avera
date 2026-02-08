<?php

namespace App\Modules\Location\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Location\Http\Resources\CityResource;
use App\Modules\Location\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function __construct(
        private CityService $cityService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = $this->cityService->get();
        return ApiResponse::successResponse(
            CityResource::collection($cities),
            "Get city data successfully",
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexByProvince(int $provinceId)
    {
        $cities = $this->cityService->getByProvince($provinceId);
        return ApiResponse::successResponse(
            CityResource::collection($cities),
            "Get city data from province $provinceId successfully",
        );
    }

    /**
     * Store a newly created resource in storage by user.
     */
    public function store(Request $request)
    {
        $city = $this->cityService->store($request->validated());
        return ApiResponse::successResponse(new CityResource($city), "Created city data successfully", Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $city = $this->cityService->update($request->validated(),$id);
        return ApiResponse::successResponse(new CityResource($city), "Updated province item data successfully");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->cityService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
