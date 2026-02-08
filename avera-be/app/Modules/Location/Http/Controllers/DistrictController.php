<?php

namespace App\Modules\Location\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Location\Http\Resources\CityResource;
use App\Modules\Location\Services\DistrictService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DistrictController extends Controller
{
     public function __construct(
        private DistrictService $districtService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $districts = $this->districtService->get();
        return ApiResponse::successResponse(
            CityResource::collection($districts),
            "Get district data successfully",
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexByCity(int $cityId)
    {
        $districts = $this->districtService->getByCity($cityId);
        return ApiResponse::successResponse(
            CityResource::collection($districts),
            "Get district data from city $cityId successfully",
        );
    }

    /**
     * Store a newly created resource in storage by user.
     */
    public function store(Request $request)
    {
        $district = $this->districtService->store($request->validated());
        return ApiResponse::successResponse(new CityResource($district), "Created district data successfully", Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $district = $this->districtService->update($request->validated(),$id);
        return ApiResponse::successResponse(new CityResource($district), "Updated city item data successfully");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->districtService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
