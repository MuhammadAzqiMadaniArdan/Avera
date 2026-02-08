<?php

namespace App\Modules\Product\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\PublishedProductRequest;
use App\Modules\Product\Http\Requests\StoreProductRequest;
use App\Modules\Product\Http\Requests\UpdateProductRequest;
use App\Modules\Product\Http\Resources\ProductHomepageResource;
use App\Modules\Product\Http\Resources\ProductResource;
use App\Modules\Product\Services\ProductCommandService;
use App\Modules\Product\Services\ProductQueryService;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class ProductSellerController extends Controller
{
    public function __construct(
        private ProductQueryService $query,
        private ProductCommandService $command
    ) {}


    public function index()
    {
        $filters = [
            'keyword' => request('q'),
            'sort'    => request('sort', 'popular'),
            'order' => request('order', 'desc'),
            'condition' => request('cond'),
            'selected_condition' => request('select-cond')
        ];

        $products = $this->query->getProductSeller($filters);
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
     * Display a listing of the resource.
     */

    public function indexByCategory(string $slug)
    {
        $filters = [
            'keyword' => request('q'),
            'sort'  => request('sort', 'popular'),
            'order' => request('order', 'desc'),
        ];
        $products = $this->query->getProductByCategory($slug, $filters);
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
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->command->storeSeller($request->validated());
        return ApiResponse::successResponse(new ProductHomepageResource($product), 'Store Product data successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $compound)
    {
        $product = $this->query->find($compound);
        return ApiResponse::successResponse(new ProductResource($product), "Show product data successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function draft(UpdateProductRequest $request, string $id)
    {
        $validated = $request->validated();
        $product = $this->command->update($id, Arr::except($validated, ['images']), $validated['images'] ?? []);
        return ApiResponse::successResponse(new ProductResource($product), 'Updated product successfully!');
    }

    /**
     * Published the specified resource in storage.
     */
    public function publish(PublishedProductRequest $request, string $id)
    {
        $validated = $request->validated();
        $product = $this->command->publish($id, Arr::except($validated, ['images']), $validated['images'] ?? []);
        return ApiResponse::successResponse(new ProductResource($product), 'Published product successfully!');
    }

    /**
     * Inactive the specified resource in storage.
     */
    public function inactive(string $id)
    {
        $product = $this->command->inactive($id);
        return ApiResponse::successResponse(new ProductResource($product), 'Inactive product successfully!');
    }

    /**
     * Archived the specified resource in storage.
     */
    public function archive(string $id)
    {
        $product = $this->command->archive($id);
        return ApiResponse::successResponse(new ProductResource($product), 'Archived product successfully!');
    }

    /**
     * Deleted the specified resource in storage.
     */
    public function destroy(string $id)
    {
        $product = $this->command->delete($id);
        return ApiResponse::successResponse(new ProductResource($product), 'Deleted product successfully!');
    }

}
