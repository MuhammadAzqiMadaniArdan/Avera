<?php

namespace App\Modules\Banner\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Banner\Models\BannerImage;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BannerImageRepository {
use CacheVersionable;
    public function __construct(private BannerImage $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('bannerImages');
        return Cache::remember(
            "bannerImages:list:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('status', 'active')->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function find(string $id): ?BannerImage
    {
        $version = $this->versionKey('bannerImages');
        $bannerImage = Cache::remember("bannerImages:list:bannerId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$bannerImage) {
            throw new ResourceNotFoundException("BannerImage");
        }
        return $bannerImage;
    }

    public function findByImageId(string $imageId): ?BannerImage
    {
        $version = $this->versionKey('bannerImages');
        $bannerImage = Cache::remember(
            "bannerImages:list:imageId:{$imageId}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('image_id', $imageId)->with('image')->first()
        );
        if (!$bannerImage) {
            throw new ResourceNotFoundException("BannerImage");
        }
        return $bannerImage;
    }
    public function findBySlug(string $slug): ?BannerImage
    {
        $version = $this->versionKey('bannerImages');
        $bannerImage = Cache::remember(
            "bannerImages:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->first()
        );
        if (!$bannerImage) {
            throw new ResourceNotFoundException("BannerImage");
        }
        return $bannerImage;
    }
    public function store(array $data): BannerImage
    {
        return $this->model->create($data);
    }
    public function update(BannerImage $bannerImage, array $data): BannerImage
    {
        $bannerImage->update($data);
        return $bannerImage;
    }
    public function delete(BannerImage $bannerImage): bool
    {
        return $bannerImage->delete();
    }
    public function deletePermanent(BannerImage $bannerImage): bool
    {
        return $bannerImage->deletePermanent();
    }
    public function restore(BannerImage $bannerImage): bool
    {
        return $bannerImage->restore();
    }
    public function getAdmin($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('bannerImages');
        return Cache::remember(
            "bannerImages:list:admin:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('bannerImages');
        return Cache::remember(
            "bannerImages:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?BannerImage
    {
        $version = $this->versionKey('bannerImages');
        $bannerImage = Cache::remember("bannerImages:list:bannerId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$bannerImage) {
            throw new ResourceNotFoundException("BannerImage");
        }
        return $bannerImage;
    }
}