<?php

namespace App\Modules\Store\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Modules\Banner\Http\Requests\StoreBannerRequest;
use App\Modules\Store\Http\Requests\StoreAdminStoreRequest;
use App\Modules\Store\Http\Requests\StoreUserStoreRequest;
use App\Modules\Store\Http\Requests\UpdateAdminStoreRequest;
use App\Modules\Store\Http\Resources\StoreResource;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    public function __construct(private StoreService $storeService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $filters = [
            'keyword' => request('q'),
            'sort'    => request('sort', 'name'),
            'order' => request('order', 'desc'),
        ];
        $store = $this->storeService->get($filters);
        return ApiResponse::successResponse(
            StoreResource::collection($store),
            "Get store data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $store->currentPage(),
                "per_page" => $store->perPage(),
                "total" => $store->total(),
                "last_page" => $store->lastPage(),
            ]
        );
    }
    
    /**
     * Display a listing of the resource.
     */
    public function indexBySeller()
    {
        $store = $this->storeService->getSellerStore();
        return ApiResponse::successResponse(
            new StoreResource($store),
            "Get store data successfully"
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function indexByAdmin()
    {
        $filters = [
            'keyword' => request('q'),
            'sort'    => request('sort', 'name'),
            'order' => request('order', 'desc'),
            'condition' => request('cond'),
            'selected_condition' => request('select-cond')
        ];
        $store = $this->storeService->get($filters);
        return ApiResponse::successResponse(
            StoreResource::collection($store),
            "Get store data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $store->currentPage(),
                "per_page" => $store->perPage(),
                "total" => $store->total(),
                "last_page" => $store->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by user.
     */
    public function store(StoreUserStoreRequest $request)
    {
        $store = $this->storeService->storeUser($request->validated());
        return ApiResponse::successResponse(new StoreResource($store), "Created store data successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $store = $this->storeService->findBySlug($slug);
        return ApiResponse::successResponse(new StoreResource($store), "Get store $slug data successfully");
    }
    /**
     * Display the specified resource.
     */
    public function showById(string $id)
    {
        $store = $this->storeService->find($id);
        return ApiResponse::successResponse(new StoreResource($store), "Get store data with id $id successfully");
    }
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $page = request()->query('page');
        $perPage = request()->query('per_page');
        $store = $this->storeService->get($perPage);
        return ApiResponse::successResponse(
            StoreResource::collection($store),
            "Get store data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $store->currentPage(),
                "per_page" => $store->perPage(),
                "total" => $store->total(),
                "last_page" => $store->lastPage(),
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
        $store = $this->storeService->getByTrashed($perPage);
        return ApiResponse::successResponse(
            StoreResource::collection($store),
            "Get store data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $store->currentPage(),
                "per_page" => $store->perPage(),
                "total" => $store->total(),
                "last_page" => $store->lastPage(),
            ]
        );
    }
    /**
     * Store a newly created resource in storage by admin.
     */
    public function storeAdmin(StoreAdminStoreRequest $request)
    {
        $store = $this->storeService->storeAdmin($request->validated());
        return ApiResponse::successResponse(new StoreResource($store), "Created store data successfully", Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage by User.
     */
    public function storeUser(StoreUserStoreRequest $request)
    {
        $store = $this->storeService->storeUser($request->validated());
        return ApiResponse::successResponse(new StoreResource($store), "Created store successfully", Response::HTTP_CREATED);
    }
    /**
     * Store a newly Banner resource in storage by User.
     */
    public function storeBanner(StoreBannerRequest $request)
    {
        $store = $this->storeService->storeBanner($request->validated());
        return ApiResponse::successResponse(new StoreResource($store), "Created store successfully", Response::HTTP_CREATED);
    }
    /**
     * Send Verification a store resource in storage by User.
     */
    public function sendVerificationStore(string $id)
    {
        $store = $this->storeService->verification($id);
        return ApiResponse::successResponse(new StoreResource($store), "Send Verification store successfully", Response::HTTP_CREATED);
    }
    /**
     * Verified a store resource in storage by Admin.
     */
    public function verifiedStoreAdmin(string $id)
    {
        $store = $this->storeService->verified($id);
        return ApiResponse::successResponse(new StoreResource($store), "Verified store successfully", Response::HTTP_CREATED);
    }
    /**
     * Rejected a store resource in storage by Admin.
     */
    public function rejectedStoreAdmin(string $id)
    {
        $store = $this->storeService->rejected($id);
        return ApiResponse::successResponse(new StoreResource($store), "Rejected store successfully", Response::HTTP_CREATED);
    }

    /**
     * Rejected a store resource in storage by Admin.
     */
    public function suspendedStoreAdmin(string $id)
    {
        $store = $this->storeService->suspended($id);
        return ApiResponse::successResponse(new StoreResource($store), "Rejected store successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function showAdmin(string $id)
    {
        $store = $this->storeService->find($id);
        return ApiResponse::successResponse(new StoreResource($store), "Get store $id data successfully");
    }
    /**
     * Display the specified Trashed resource.
     */
    public function showByTrashed(string $id)
    {
        $store = $this->storeService->findByTrashed($id);
        return ApiResponse::successResponse(new StoreResource($store), "Get store $id data successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAdmin(UpdateAdminStoreRequest $request, string $id)
    {
        $store = $this->storeService->update($id, $request->validated());
        return ApiResponse::successResponse(new StoreResource($store), "Updated store data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAdmin(string $id)
    {
        $this->storeService->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function destroyPermanent(string $id)
    {
        $this->storeService->deletePermanent($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $store = $this->storeService->restore($id);
        return ApiResponse::successResponse($store, "Restored store data with id $id successfully");
    }
}
