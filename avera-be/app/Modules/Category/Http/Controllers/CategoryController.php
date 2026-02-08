<?php

namespace App\Modules\Category\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Category\Http\Requests\StoreAdminCategoryRequest;
use App\Modules\Category\Http\Requests\StoreUserCategoryRequest;
use App\Modules\Category\Http\Requests\UpdateCategoryRequest;
use App\Modules\Category\Http\Resources\CategoryBaseResource;
use App\Modules\Category\Http\Resources\CategoryResource;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Services\CategoryService;
use Illuminate\Container\Attributes\Log as AttributesLog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}
    /**
     * Display a listing of the resource.
     */
    public function indexParent()
    {
        $category = $this->categoryService->getParent();
        return ApiResponse::successResponse(
            CategoryBaseResource::collection($category),
            "Get category data successfully",
            Response::HTTP_OK
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexTree()
    {
        $category = $this->categoryService->getTree();
        return ApiResponse::successResponse(
            CategoryResource::collection($category),
            "Get category Tree data successfully",
            Response::HTTP_OK
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreUserCategoryRequest $request)
    {
        $category = $this->categoryService->storeUser($request->validated());
        return ApiResponse::successResponse(new CategoryResource($category), "Created category data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $category = $this->categoryService->findBySlug($slug);
        return ApiResponse::successResponse(new CategoryResource($category), "Get category $slug data successfully");
    }
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $filters = [
            'sort' => request()->query('sort'),
            'order_direction' => request()->query('order','desc'),
            'per_page' => request()->query('per-page', 24),
            'status' => request()->query('status'),
            'allow_adult_content' => request()->query('allow-adult'),
        ];
        $category = $this->categoryService->getAdmin($filters);
        return ApiResponse::successResponse(
            CategoryResource::collection($category),
            "Get category data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $category->currentPage(),
                "per_page" => $category->perPage(),
                "total" => $category->total(),
                "last_page" => $category->lastPage(),
            ]
        );
    }
    /**
     * Display a listing Trashed of the resource.
     */
    public function indexByTrashed()
    {
        $filters = [
            'sort' => request()->query('sort'),
            'order_direction' => request()->query('order','desc'),
            'per_page' => request()->query('per-page', 24),
            'status' => request()->query('status'),
            'allow_adult_content' => request()->query('allow-adult'),
        ];
        $category = $this->categoryService->getByTrashed($filters);
        return ApiResponse::successResponse(
            CategoryResource::collection($category),
            "Get category data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $category->currentPage(),
                "per_page" => $category->perPage(),
                "total" => $category->total(),
                "last_page" => $category->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by admin.
     */
    public function storeAdmin(StoreAdminCategoryRequest $request)
    {
        $category = $this->categoryService->storeAdmin($request->validated());
        return ApiResponse::successResponse(new CategoryResource($category), "Created category data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function showAdmin(string $id)
    {
        $category = $this->categoryService->find($id);
        return ApiResponse::successResponse(new CategoryResource($category), "Get category $id data successfully");
    }
    /**
     * Display the specified Trashed resource.
     */
    public function showByTrashed(string $id)
    {
        $category = $this->categoryService->findByTrashed($id);
        return ApiResponse::successResponse(new CategoryResource($category), "Get category $id data successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAdmin(UpdateCategoryRequest $request, string $id)
    {
        $category = $this->categoryService->update($id, $request->validated());
        return ApiResponse::successResponse(new CategoryResource($category), "Updated category data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAdmin(string $id)
    {
        $this->categoryService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function destroyPermanent(string $id)
    {
        $this->categoryService->deletePermanent($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $category = $this->categoryService->restore($id);
        return ApiResponse::successResponse($category, "Restored category data with id $id successfully", Response::HTTP_NO_CONTENT);
    }
}
