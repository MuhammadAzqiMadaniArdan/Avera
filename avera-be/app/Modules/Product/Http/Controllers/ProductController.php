<?php

namespace App\Modules\Product\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\PublishedProductRequest;
use App\Modules\Product\Http\Requests\StoreProductRequest;
use App\Modules\Product\Http\Requests\UpdateProductRequest;
use App\Modules\Product\Http\Resources\ProductDetailResource;
use App\Modules\Product\Http\Resources\ProductHomepageResource;
use App\Modules\Product\Http\Resources\ProductResource;
use App\Modules\Product\Services\ProductCommandService;
use App\Modules\Product\Services\ProductQueryService;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
 public function __construct(
        private ProductQueryService $query,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = [
            'keyword' => request('keyword'),
            'sort'    => request('sort', 'popular'),
            'order'   => request('order', 'desc'),
        ];

        $products = $this->query->filter($filters);
        return ApiResponse::successResponse(
            ProductHomepageResource::collection($products),
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
    public function indexRandom()
    {
        $products = $this->query->getRandomProduct();
        return ApiResponse::successResponse(
            ProductHomepageResource::collection($products),
            'Get data product successfully'
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexTop()
    {
        $products = $this->query->getTopProduct();
        return ApiResponse::successResponse(
            ProductHomepageResource::collection($products),
            'Get data product successfully'
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexRandomPaginate()
    {
        $products = $this->query->getRandomDiscovery();
        return ApiResponse::successResponse(
            ProductHomepageResource::collection($products),
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
    public function indexByCategory(string $slug)
    {
        $filters = [
            'keyword' => request('q'),
            'sort'  => request('sort', 'popular'),
            'order' => request('order', 'desc'),
        ];
        $products = $this->query->getProductByCategory($slug, $filters);
        return ApiResponse::successResponse(
            ProductHomepageResource::collection($products),
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
    public function indexByStore(string $slug)
    {
        $filters = [
            'keyword' => request('q'),
            'sort'    => request('sort', 'popular'),
            'order' => request('order', 'desc')
        ];

        $products = $this->query->getProductByStoreSlug($slug, $filters);
        return ApiResponse::successResponse(
            ProductHomepageResource::collection($products),
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
     * Display the specified resource.
     */
    public function show(string $compound)
    {
        $product = $this->query->findDetail($compound);
        return ApiResponse::successResponse(new ProductDetailResource($product), "Show product data successfully");
    }
}
