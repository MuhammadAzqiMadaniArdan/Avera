<?php

namespace App\Modules\Order\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Modules\Order\Http\Requests\StoreReviewRequest;
use App\Modules\Order\Http\Resources\ReviewResource;
use App\Modules\Order\Models\Review;
use App\Modules\Order\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $review = $this->reviewService->get($perPage);
        return ApiResponse::successResponse(
            ReviewResource::collection($review),
            "Get review data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $review->currentPage(),
                "per_page" => $review->perPage(),
                "total" => $review->total(),
                "last_page" => $review->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreReviewRequest $request)
    {
        $user = auth()->user();
        $review = $this->reviewService->store($request->validated(), $user->id);
        return ApiResponse::successResponse(new ReviewResource($review), "Created review data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function showBySlug(string $slug)
    {
        $review = $this->reviewService->findBySlug($slug);
        return ApiResponse::successResponse(new ReviewResource($review), "Get review $slug data successfully");
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = $this->reviewService->find($id);
        return ApiResponse::successResponse(new ReviewResource($review), "Get review data with id $id successfully");
    }
    /**
     * Display the specified resource.
     */
    public function getByProduct(string $productId)
    {
        $filters = [
            'per_page' => 6,
            'page' => request()->query('page'),
            'rating' => request()->query('rating'),
        ];
        $review = $this->reviewService->getByProduct($filters, $productId);
        return ApiResponse::successResponse(
            ReviewResource::collection($review),
            "Get review data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $review->currentPage(),
                "per_page" => $review->perPage(),
                "total" => $review->total(),
                "last_page" => $review->lastPage(),
            ]
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $review = $this->reviewService->get($perPage);
        return ApiResponse::successResponse(
            ReviewResource::collection($review),
            "Get review data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $review->currentPage(),
                "per_page" => $review->perPage(),
                "total" => $review->total(),
                "last_page" => $review->lastPage(),
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
        $review = $this->reviewService->getByTrashed($perPage);
        return ApiResponse::successResponse(
            ReviewResource::collection($review),
            "Get review data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $review->currentPage(),
                "per_page" => $review->perPage(),
                "total" => $review->total(),
                "last_page" => $review->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by admin.
     */
    public function storeAdmin(StoreReviewRequest $request)
    {
        $userId = AuthHelper::uuid($request);
        $review = $this->reviewService->storeAdmin($request->validated(), $userId);
        return ApiResponse::successResponse(new ReviewResource($review), "Created review data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function showAdmin(string $id)
    {
        $review = $this->reviewService->find($id);
        return ApiResponse::successResponse(new ReviewResource($review), "Get review $id data successfully");
    }
    /**
     * Display the specified Trashed resource.
     */
    public function showByTrashed(string $id)
    {
        $review = $this->reviewService->findByTrashed($id);
        return ApiResponse::successResponse(new ReviewResource($review), "Get review $id data successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAdmin(Request $request, string $id)
    {
        $review = $this->reviewService->update($id, $request->validated());
        return ApiResponse::successResponse(new ReviewResource($review), "Updated review data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAdmin(string $id)
    {
        $this->reviewService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function destroyPermanent(string $id)
    {
        $this->reviewService->deletePermanent($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $review = $this->reviewService->restore($id);
        return ApiResponse::successResponse($review, "Restored review data with id $id successfully", Response::HTTP_NO_CONTENT);
    }
}
