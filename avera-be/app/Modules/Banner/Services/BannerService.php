<?php

namespace App\Modules\Banner\Services;

use App\Jobs\ModerateImageJob;
use App\Modules\Banner\Models\Banner;
use App\Modules\Banner\Repositories\BannerRepository;
use App\Modules\Image\Services\ImageService;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;

class BannerService implements BannerServiceInterface
{
    use CacheVersionable;
    public function __construct(private BannerRepository $bannerRepository, private ImageService $imageService, private BannerImageService $bannerImageService) {}
    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->bannerRepository->get($perPage);
    }
    public function find(string $id): ?Banner
    {
        return $this->bannerRepository->find($id);
    }
    public function getAdmin(int $perPage): LengthAwarePaginator
    {
        return $this->bannerRepository->getAdmin($perPage);
    }
    public function store(array $data): Banner
    {
        $banner = $this->bannerRepository->store($data);
        foreach ($data['images'] as $key => $image) {
            $result = $this->imageService->upload($image);
            $data = [
                'banner_id' => $banner->id,
                'image_id' => $result->id,
                'position' => $key,
            ];
            $this->bannerImageService->store($data);
            if ($key === 0) $this->bannerRepository->update($banner, ['primary_image_id' => $result->id]);
            ModerateImageJob::dispatch($result->id);
        }
        $this->invalidateCache('banners');
        return $banner;
    }
    
    public function storeAdmin(array $data): Banner
    {
        $banner = $this->bannerRepository->store($data);
        $this->invalidateCache('banners');
        return $banner;
    }
    public function storeUser(array $data): Banner
    {
        $result = $this->bannerRepository->store($data);
        $this->invalidateCache('banners');
        return $result;
    }
    public function update(string $id, array $data): Banner
    {
        $banner = $this->bannerRepository->find($id);
        $result = $this->bannerRepository->update($banner, $data);
        $this->invalidateCache('banners');
        return $result;
    }
    public function delete(string $id): bool
    {
        $banner = $this->bannerRepository->find($id);
        return $this->bannerRepository->delete($banner);
    }
    public function deletePermanent(string $id): bool
    {
        $banner = $this->bannerRepository->findByTrashed($id);
        return $this->bannerRepository->deletePermanent($banner);
    }
    public function restore(string $id): bool
    {
        $banner = $this->bannerRepository->findByTrashed($id);
        return $this->bannerRepository->restore($banner);
    }
    public function findBySlug(string $slug): ?Banner
    {
        return $this->bannerRepository->findBySlug($slug);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->bannerRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?Banner
    {
        return $this->bannerRepository->findByTrashed($id);
    }
}
