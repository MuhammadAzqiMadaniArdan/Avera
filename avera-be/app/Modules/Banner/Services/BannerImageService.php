<?php

namespace App\Modules\Banner\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Banner\Models\BannerImage;
use App\Modules\Banner\Repositories\BannerImageRepository;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;

class BannerImageService
{
    use CacheVersionable;
    public function __construct(private BannerImageRepository $bannerImageRepository) {}
    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->bannerImageRepository->get($perPage);
    }
    public function find(string $id): ?BannerImage
    {
        return $this->bannerImageRepository->find($id);
    }
    public function getAdmin(int $perPage): LengthAwarePaginator
    {
        return $this->bannerImageRepository->getAdmin($perPage);
    }
    public function store(array $data): BannerImage
    {
        $bannerImage = $this->bannerImageRepository->store($data);
        $this->invalidateCache('banners');
        return $bannerImage;
    }
    public function storeAdmin(array $data): BannerImage
    {
        $bannerImage = $this->bannerImageRepository->store($data);
        $this->invalidateCache('banners');
        return $bannerImage;
    }
    public function storeUser(array $data): BannerImage
    {
        $result = $this->bannerImageRepository->store($data);
        $this->invalidateCache('banners');
        return $result;
    }
    public function update(string $id, array $data): BannerImage
    {
        $bannerImage = $this->bannerImageRepository->find($id);
        $result = $this->bannerImageRepository->update($bannerImage, $data);
        $this->invalidateCache('banners');
        return $result;
    }
    public function delete(string $id): bool
    {
        $bannerImage = $this->bannerImageRepository->find($id);
        return $this->bannerImageRepository->delete($bannerImage);
    }
    public function deletePermanent(string $id): bool
    {
        $bannerImage = $this->bannerImageRepository->findByTrashed($id);
        return $this->bannerImageRepository->deletePermanent($bannerImage);
    }
    public function restore(string $id): bool
    {
        $bannerImage = $this->bannerImageRepository->findByTrashed($id);
        return $this->bannerImageRepository->restore($bannerImage);
    }
    public function findBySlug(string $slug): ?BannerImage
    {
        return $this->bannerImageRepository->findBySlug($slug);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->bannerImageRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?BannerImage
    {
        return $this->bannerImageRepository->findByTrashed($id);
    }
    public function recalculateByImageId(string $imageId)
    {
        $bannerImage = $this->bannerImageRepository->findByImageId($imageId);
        if (empty($bannerImage)) {
            return;
        }
        $banner = $bannerImage->banner;
        $hasRejected = $banner->images()
            ->where('moderation_status', 'rejected')->exist();
        $hasPending = $banner->images()
            ->where('moderation_status', 'pending')->exist();

        if ($hasRejected) {
            $banner->update([
                'moderation_visibility' => 'hidden',
                'moderated_at' => now()
            ]);
            return;
        }
        if ($hasPending) {
            return;
        }
        $bannerImage->banner->update([
            'moderation_visibility' => 'public',
            'moderated_at' => now()
        ]);
        return;
    }
}
