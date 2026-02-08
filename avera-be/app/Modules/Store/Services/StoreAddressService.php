<?php

namespace App\Modules\Store\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Location\Models\City;
use App\Modules\Location\Repositories\CityRepository;
use App\Modules\Location\Repositories\ProvinceRepository;
use App\Modules\Store\Models\StoreAddress;
use App\Modules\Store\Repositories\StoreAddressRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Models\UserAddress;
use App\Modules\User\Repositories\UserAddressRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Services\Shipment\RajaOngkirService;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StoreAddressService
{
    use CacheVersionable;
    public function __construct(
        private StoreAddressRepository $storeAdderssRepository,
        private StoreRepository $storeRepository,
        private CityRepository $cityRepository,
        private ProvinceRepository $provinceRepository,
        private RajaOngkirService $rajaOngkirService
    ) {}

    public function get(string $userId): StoreAddress
    {
        $store = $this->storeRepository->findByUser($userId);
        $storeAddress = $this->storeAdderssRepository->get($store->id);
        return $storeAddress;
    }

    public function find(string $id): ?UserAddress
    {
        return $this->storeAdderssRepository->find($id);
    }

    public function store(array $data, string $userId): UserAddress
    {
        return DB::transaction(function () use ($data, $userId) {

            $store = $this->storeRepository->findByUser($userId);
            Gate::authorize('update', $store);
            $city = $this->cityRepository->findOrFail($data['city_id']);
            $this->validateCityOnProvince($city, $data['province_id']);
            $hasAddress = $this->storeAdderssRepository->existByStore($store->id);

            if ($hasAddress) {
                throw new Exception("Store Already Exist");
            }

            $storeAddressData = [
                'store_id' => $store->id,

                'store_name' => $data['store_name'],
                'phone_number' => $data['phone_number'],

                'province_id' => $city['province_id'],
                'province_name' => $data['province_name'],

                'city_id' => $city['id'],
                'city_name' => $city['name'],

                'district_id' => $data['district_id'],
                'district_name' => $data['district_name'],

                'village_id' => $data['village_id'],
                'village_name' => $data['village_name'],

                'postal_code' => $data['postal_code'] ?? null,
                'address' => $data['address'],
            ];
            $result = $this->storeAdderssRepository->store($storeAddressData);
            $this->invalidateCache('store_addresses');
            return $result;
        });
    }

    public function update(string $id, array $data): UserAddress
    {
        $user = auth()->user();
        $store = $this->storeRepository->findByUser($user);
        Gate::authorize('update', $store);
        $storeAddress = $this->storeAdderssRepository->find($id);
        if (!$storeAddress) {
            throw new ResourceNotFoundException("Store Address");
        }

        $city = null;
        if (array_key_exists('city_id', $data)) {
            $city = $this->cityRepository->find($data['city_id']);
            $this->validateCityOnProvince($city, $data['province_id']);
        }
        $storeAddressData = array_filter([

            'store_name' => $data['store_name'] ?? $storeAddress->store_name,
            'phone_number' => $data['phone_number'] ?? $storeAddress->phone_number,

            'province_id' => $city['province_id'] ?? $storeAddress->province_id,
            'province_name' => $data['province_name'] ?? $storeAddress->province_name,

            'city_id' => $city['id'] ?? $storeAddress->city_id,
            'city_name' => $city['name'] ?? $storeAddress->city_name,

            'district_id' => $data['district_id'] ?? $storeAddress->district_id,
            'district_name' => $data['district_name'] ?? $storeAddress->district_name,

            'village_id' => $data['village_id'] ?? $storeAddress->village_id,
            'village_name' => $data['village_name'] ?? $storeAddress->village_name,

            'postal_code' => $data['postal_code'] ?? $storeAddress->postal_code,
            'address' => $data['address'] ?? $storeAddress->address,
        ], fn($v) => !is_null($v));

        $result = $this->storeAdderssRepository->update($storeAddress, $storeAddressData);
        $this->invalidateCache('store_addresses');
        return $result;
    }

    private function validateCityOnProvince(City $city, int $provinceId)
    {
        if ((int) $city['province_id'] !== (int) $provinceId) {
            throw ValidationException::withMessages([
                'city_id' => 'City does not belong to selected province'
            ]);
        }
    }

    public function delete(string $id): bool
    {
        $user = auth()->user();
        $store = $this->storeRepository->findByUser($user);
        Gate::authorize('delete', $store);
        $storeAddress = $this->storeAdderssRepository->find($id);
        return $this->storeAdderssRepository->delete($storeAddress);
    }
}
