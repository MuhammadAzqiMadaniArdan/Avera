<?php

namespace App\Modules\User\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Modules\User\Http\Requests\UpdateUserRequest;
use App\Modules\User\Http\Requests\UserUpdateRequest;
use App\Modules\User\Http\Resources\UserResource;
use App\Modules\User\Services\UserCommandService;
use App\Modules\User\Services\UserQueryService;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserAdminController extends Controller
{
    public function __construct(
        private UserQueryService $query,
        private UserCommandService $command
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $filters = [
            'order_direction' => request()->query('order', 'desc'),
            'sort' => request()->query('sort'),
            'per_page' => request()->query('per-page', 24),
            'status' => request()->query('status'),
            'gender' => request()->query('gender'),
        ];
        $user = $this->query->getAdmin($filters);
        return ApiResponse::successResponse(
            UserResource::collection($user),
            "Get user data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $user->currentPage(),
                "per_page" => $user->perPage(),
                "total" => $user->total(),
                "last_page" => $user->lastPage(),
            ]
        );
    }
    /**
     * Display a listing Trashed of the resource.
     */
    public function indexByTrashed()
    {
        $filters = [
            'order_direction' => request()->query('order', 'desc'),
            'sort' => request()->query('sort'),
            'per_page' => request()->query('per-page', 24),
            'status' => request()->query('status'),
            'gender' => request()->query('gender'),
        ];
        $user = $this->query->getByTrashed($filters);
        return ApiResponse::successResponse(
            UserResource::collection($user),
            "Get user trashed data successfully",
            Response::HTTP_OK,
            [
                "current_page" => $user->currentPage(),
                "per_page" => $user->perPage(),
                "total" => $user->total(),
                "last_page" => $user->lastPage(),
            ]
        );
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->query->find($id);
        return ApiResponse::successResponse(new UserResource($user), "Get user $id data successfully");
    }
    /**
     * Display the specified Trashed resource.
     */
    public function showByTrashed(string $id)
    {
        $user = $this->query->findByTrashed($id);
        return ApiResponse::successResponse(new UserResource($user), "Get user $id data successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAdmin(UpdateAdminUserRequest $request, string $id)
    {
        $user = $this->command->update($id, $request->validated());
        return ApiResponse::successResponse(new UserResource($user), "Updated user data successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAdmin(string $id)
    {
        $this->command->delete($id);
        return ApiResponse::successResponse(null, '', Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource permanently from storage.
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
        $user = $this->command->restore($id);
        return ApiResponse::successResponse($user, "Restored user data with id $id successfully", Response::HTTP_NO_CONTENT);
    }
}
