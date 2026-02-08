<?php

namespace App\Modules\Store\Services;

use App\Exceptions\UserAlreadyHasStoreException;
use App\Exceptions\VerificationAlreadySubmittedException;
use App\Exceptions\VerificationStoreException;
use App\Modules\Image\Services\ImageService;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Repositories\StoreRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class StoreService
{
    use CacheVersionable;
    public function __construct(private StoreRepository $storeRepository,private ImageService $imageService) {}
    
    public function storeBanner(array $data): Store
    {
        $store = $this->storeRepository->find($data['owner_id']);
        $image = $this->imageService->upload($data['file']);

        $data['owner_type'] = 'store';
        $data['type'] = 'store';
        $result = $this->storeRepository->store($data);
        $this->invalidateCache('stores');
        return $result;
    }
}
