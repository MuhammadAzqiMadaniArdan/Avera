<?php

namespace App\Modules\Banner\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Banner\Models\Banner;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BannerRepository implements BannerRepositoryInterface {
use CacheVersionable;
    public function __construct(private Banner $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('banners');
        return Cache::remember(
            "banners:list:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('status', 'active')->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function find(string $id): ?Banner
    {
        $version = $this->versionKey('banners');
        $banner = Cache::remember("banners:list:bannerId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$banner) {
            throw new ResourceNotFoundException("Banner");
        }
        return $banner;
    }

    public function findBySlug(string $slug): ?Banner
    {
        $version = $this->versionKey('banners');
        $banner = Cache::remember(
            "banners:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->first()
        );
        if (!$banner) {
            throw new ResourceNotFoundException("Banner");
        }
        return $banner;
    }
    public function store(array $data): Banner
    {
        return $this->model->create($data);
    }
    public function update(Banner $banner, array $data): Banner
    {
        $banner->update($data);
        return $banner;
    }
    public function delete(Banner $banner): bool
    {
        return $banner->delete();
    }
    public function deletePermanent(Banner $banner): bool
    {
        return $banner->deletePermanent();
    }
    public function restore(Banner $banner): bool
    {
        return $banner->restore();
    }
    public function getAdmin($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('banners');
        return Cache::remember(
            "banners:list:admin:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('banners');
        return Cache::remember(
            "banners:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?Banner
    {
        $version = $this->versionKey('banners');
        $banner = Cache::remember("banners:list:bannerId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$banner) {
            throw new ResourceNotFoundException("Banner");
        }
        return $banner;
    }
}