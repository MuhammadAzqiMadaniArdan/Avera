<?php

namespace App\Modules\Product\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\StoreProductImageRequest;
use App\Modules\Product\Http\Requests\UpdateProductImageRequest;
use App\Modules\Product\Http\Resources\ProductImageResource;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Services\ProductImageService;
use Illuminate\Http\Response;

class ProductImageController extends Controller
{
    public function __construct(private ProductImageService $productImageService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request)
    {
        $productImage = $this->productImageService->store($request->validated());
        return ApiResponse::successResponse(new ProductImageResource($productImage),'Created product image successfully !', Response::HTTP_CREATED);
    }

    /**
     * replace the specified resource in storage.
     */
    public function replace(UpdateProductImageRequest $request, string $id)
    {
        $productImage = $this->productImageService->replace($id, $request->file('image'));
        return ApiResponse::successResponse(new ProductImageResource($productImage),'Replaced product image successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->productImageService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }
}
