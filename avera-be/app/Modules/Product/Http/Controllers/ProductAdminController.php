<?php

namespace App\Modules\Product\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\Admin\StoreAdminProductRequest;
use App\Modules\Product\Http\Requests\Admin\UpdateAdminProductRequest;
use App\Modules\Product\Http\Requests\PublishedProductRequest;
use App\Modules\Product\Http\Requests\StoreProductRequest;
use App\Modules\Product\Http\Requests\UpdateProductRequest;
use App\Modules\Product\Http\Resources\ProductResource;
use App\Modules\Product\Services\ProductCommandService;
use App\Modules\Product\Services\ProductQueryService;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class ProductAdminController extends Controller
{
 public function __construct(
        private ProductQueryService $query,
        private ProductCommandService $command
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $filters = [
            'keyword' => request('q'),
            'sort'    => request('sort', 'popular'),
            'order' => request('order', 'desc'),
            'condition' => request('cond'),
            'selected_condition' => request('select-cond')
        ];

        $products = $this->query->filter($filters);
        return ApiResponse::successResponse(
            ProductResource::collection($products),
            'Get data product successfully',
            Response::HTTP_OK,
            [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ]
        );
    }
    /**
     * Display a Trashed listing of the resource.
     */
    public function indexByTrashed()
    {
        $filters = [
            'keyword' => request('q'),
            'sort'    => request('sort', 'popular'),
            'order' => request('order', 'desc'),
            'condition' => request('cond'),
            'selected_condition' => request('select-cond')
        ];

        $products = $this->query->getProductByTrashed($filters);
        return ApiResponse::successResponse(
            ProductResource::collection($products),
            'Get trashed data product successfully',
            Response::HTTP_OK,
            [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminProductRequest $request)
    {
        $product = $this->command->store($request->validated());
        return ApiResponse::successResponse(new ProductResource($product), 'Store Product data successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->query->findById($id);
        return ApiResponse::successResponse(new ProductResource($product), "Show product data successfully");
    }
    /**
     * Display the specified resource.
     */
    public function showByTrashed(string $id)
    {
        $product = $this->query->findByTrashed($id);
        return ApiResponse::successResponse(new ProductResource($product), "Get product data trashed with id $id successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminProductRequest $request, string $id)
    {
        $validated = $request->validated();
        $product = $this->command->update($id, Arr::except($validated, ['images']), $validated['images'] ?? []);
        return ApiResponse::successResponse(new ProductResource($product), 'Updated product successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->command->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
    /**
     * Remove Permanently the specified resource from storage.
     */
    public function destroyPermanent(string $id)
    {
        $this->command->deletePermanent($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $product = $this->command->restore($id);
        return ApiResponse::successResponse($product, "Restore product data with id $id successfully");
    }
}
