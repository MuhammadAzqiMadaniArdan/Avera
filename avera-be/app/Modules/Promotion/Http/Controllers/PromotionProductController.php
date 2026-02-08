<?php

namespace App\Modules\Promotion\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePromotionProductRequest;
use App\Modules\Promotion\Http\Resources\PromotionProductResource;
use App\Modules\Promotion\Services\PromotionProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PromotionProductController extends Controller
{
    public function __construct(private PromotionProductService $promotionProductService) {}
    /**
     * Display a listing of the app promotion resource.
     */
    public function index()
    {
        $perPage = request()->query('per_page');
        $promotion = $this->promotionProductService->get($perPage);
        return ApiResponse::successResponse(
            PromotionProductResource::collection($promotion),
            "Get promotion data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $promotion->currentPage(),
                "per_page" => $promotion->perPage(),
                "total" => $promotion->total(),
                "last_page" => $promotion->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(Request $request)
    {
        $userId = AuthHelper::uuid($request);
        $promotion = $this->promotionProductService->store($request->validated(),$userId);
        return ApiResponse::successResponse(new PromotionProductResource($promotion), "Created promotion data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $promotion = $this->promotionProductService->find($slug);
        return ApiResponse::successResponse(new PromotionProductResource($promotion), "Get promotion $slug data successfully");
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromotionProductRequest $request, string $id)
    {
        $promotion = $this->promotionProductService->update($id, $request->validated());
        return ApiResponse::successResponse(new PromotionProductResource($promotion), "Updated promotion data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->promotionProductService->delete($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }

}
