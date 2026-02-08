<?php

namespace App\Modules\Promotion\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Modules\Promotion\Http\Requests\StoreProductPromotionRequest;
use App\Modules\Promotion\Http\Resources\PromotionResource;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Promotion\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends Controller
{
   public function __construct(private PromotionService $promotionService) {}
    /**
     * Display a listing of the app promotion resource.
     */
    public function index()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $promotion = $this->promotionService->get($perPage);
        return ApiResponse::successResponse(
            PromotionResource::collection($promotion),
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
     * Display a listing of the all store resource.
     */
    public function indexByAllStore()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $promotion = $this->promotionService->getAllStore($perPage);
        return ApiResponse::successResponse(
            PromotionResource::collection($promotion),
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
     * Display a listing of the store resource.
     */
    public function indexByStore(string $storeId)
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $promotion = $this->promotionService->getStore($storeId);
        return ApiResponse::successResponse(
            PromotionResource::collection($promotion),
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
    public function store(StoreProductPromotionRequest $request)
    {
        $userId = AuthHelper::uuid($request);
        $promotion = $this->promotionService->store($request->validated(),$userId);
        return ApiResponse::successResponse(new PromotionResource($promotion), "Created promotion data successfully", Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $promotion = $this->promotionService->find($slug);
        return ApiResponse::successResponse(new PromotionResource($promotion), "Get promotion $slug data successfully");
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromotionRequest $request, string $id)
    {
        $promotion = $this->promotionService->update($id, $request->validated());
        return ApiResponse::successResponse(new PromotionResource($promotion), "Updated promotion data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->promotionService->delete($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     */
    public function showById(string $id)
    {
        $promotion = $this->promotionService->find($id);
        return ApiResponse::successResponse(new PromotionResource($promotion), "Get promotion data with id $id successfully");
    }
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $promotion = $this->promotionService->get($perPage);
        return ApiResponse::successResponse(
            PromotionResource::collection($promotion),
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
     * Display a listing Trashed of the resource.
     */
    public function indexByTrashed()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $promotion = $this->promotionService->getByTrashed($perPage);
        return ApiResponse::successResponse(
            PromotionResource::collection($promotion),
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
     * Display the specified resource.
     */
    public function showAdmin(string $id)
    {
        $promotion = $this->promotionService->find($id);
        return ApiResponse::successResponse(new PromotionResource($promotion), "Get promotion $id data successfully");
    }
    /**
     * Display the specified Trashed resource.
     */
    public function showByTrashed(string $id)
    {
        $promotion = $this->promotionService->findByTrashed($id);
        return ApiResponse::successResponse(new PromotionResource($promotion), "Get promotion $id data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAdmin(string $id)
    {
        $this->promotionService->deleteAdmin($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function destroyPermanent(string $id)
    {
        $this->promotionService->deletePermanent($id);
        return ApiResponse::successResponse(null, "", Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $promotion = $this->promotionService->restore($id);
        return ApiResponse::successResponse($promotion, "Restored promotion data with id $id successfully");
    }
}
