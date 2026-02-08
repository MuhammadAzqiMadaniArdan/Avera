<?php

namespace App\Modules\Location\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Location\Http\Resources\ProvinceResource;
use App\Modules\Location\Services\ProvinceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProvinceController extends Controller
{
    public function __construct(
        private ProvinceService $provinceService,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $provinces = $this->provinceService->get();
        return ApiResponse::successResponse(
            ProvinceResource::collection($provinces),
            "Get province data successfully",
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(Request $request)
    {
        $province = $this->provinceService->store($request->validated());
        return ApiResponse::successResponse(new ProvinceResource($province), "Created province data successfully", Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $province = $this->provinceService->update($request->validated(),$id);
        return ApiResponse::successResponse(new ProvinceResource($province), "Updated province item data successfully");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->provinceService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
