<?php

namespace App\Modules\Location\Services;

use App\Modules\Location\Contracts\ProvinceServiceInterface;
use App\Modules\Location\Models\Province;
use App\Modules\Location\Repositories\ProvinceRepository;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;

class ProvinceService implements ProvinceServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private ProvinceRepository $provinceRepository,
    ) {}
    public function get(): Collection
    {
        return $this->provinceRepository->get();
    }
    public function find(int $id): ?Province
    {
        return $this->provinceRepository->find($id);
    }
    public function store(array $data): Province
    {
        $result = $this->provinceRepository->store($data);
        $this->invalidateCache('provinces');
        return $result;
    }
    public function update(array $data, int $id): Province
    {
        $city = $this->provinceRepository->find($id);
        $result = $this->provinceRepository->update($city, $data);
        $this->invalidateCache('provinces');
        return $result;
    }
    public function delete(int $id): bool
    {
        $city = $this->provinceRepository->find($id);
        $this->invalidateCache('provinces');

        return $this->provinceRepository->delete($city);
    }
}
