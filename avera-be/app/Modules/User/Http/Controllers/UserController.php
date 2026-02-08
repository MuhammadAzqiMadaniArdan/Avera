<?php

namespace App\Modules\User\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Requests\UpdateUserRequest;
use App\Modules\User\Http\Requests\UploadAvatarRequest;
use App\Modules\User\Http\Requests\UserUpdateRequest;
use App\Modules\User\Http\Resources\UserProfileResource;
use App\Modules\User\Http\Resources\UserResource;
use App\Modules\User\Services\UserCommandService;
use App\Modules\User\Services\UserQueryService;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        private UserQueryService $query,
        private UserCommandService $command,
    ) {}
    public function show(string $id)
    {
        $user = $this->query->find($id);
        return ApiResponse::successResponse($user, "Get User Data Successfully");
    }
    public function getProfile(Request $request)
    {
        $identityId = AuthHelper::uuid($request);
        $user = $this->query->getProfile($identityId);
        return ApiResponse::successResponse(new UserProfileResource($user), "Get Data Successfully");
    }
    public function storeProfile(Request $request)
    {
        $uuid = AuthHelper::uuid($request);
        $email = AuthHelper::email($request);
        $user = $this->command->storeProfile($uuid, $email);
        return ApiResponse::successResponse(new UserProfileResource($user), "Created Successfully", Response::HTTP_CREATED);
    }
    public function updateProfile(UpdateUserRequest $request)
    {
        $uuid = AuthHelper::uuid($request);
        $data = $request->validated();
        $user = $this->command->updateProfile($uuid, $data);
        return ApiResponse::successResponse(new UserProfileResource($user), "Updated Successfully");
    }
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $uuid = AuthHelper::uuid($request);
        $user = $this->command->uploadAvatar($uuid, $request->validated());
        return ApiResponse::successResponse(new UserProfileResource($user), "Updated Successfully");
    }
}
