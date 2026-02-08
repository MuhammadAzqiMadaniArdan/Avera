<?php

namespace App\Modules\Image\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Image\Contracts\ImageRepositoryInterface;
use App\Modules\Image\Models\Image;
use App\Traits\CacheVersionable;
use Illuminate\Support\Facades\Cache;

class ImageRepository implements ImageRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private Image $model) {}
    public function store(array $data): Image
    {
        return $this->model->create($data);
    }
    public function find(string $id): ?Image
    {
        $version = $this->versionKey('images');
        $image = Cache::remember("image:show:id:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$image) {
            throw new ResourceNotFoundException('image');
        }
        return $image;
    }
    public function findByHash(string $hash): ?Image
    {
        $version = $this->versionKey('images');
        $image = Cache::remember("image:show:hash:v{$version}", now()->addMinutes(1), fn() => $this->model->where('hash', $hash)->first());
        return $image;
    }
    public function update(Image $image, array $data): Image
    {
        $image->update($data);
        return $image;
    }
    public function delete(Image $image) : bool 
    {
        return $image->delete();
    }
}
