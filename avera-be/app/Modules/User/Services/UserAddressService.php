<?php

namespace App\Modules\User\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Location\Models\City;
use App\Modules\Location\Repositories\CityRepository;
use App\Modules\Location\Repositories\ProvinceRepository;
use App\Modules\User\Contracts\UserAddressServiceInterface;
use App\Modules\User\Models\UserAddress;
use App\Modules\User\Repositories\UserAddressRepository;
use App\Services\Shipment\RajaOngkirService;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserAddressService implements UserAddressServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private UserAddressRepository $userAddress,
        private CityRepository $cityRepository,
        private ProvinceRepository $provinceRepository,
        private RajaOngkirService $rajaOngkirService
    ) {}

    public function get(string $userId): Collection
    {
        return $this->userAddress->get($userId);
    }

    public function find(string $id): ?UserAddress
    {
        return $this->userAddress->find($id);
    }

    public function store(array $data, string $userId): UserAddress
    {
        return DB::transaction(function () use ($data, $userId) {

            $city = $this->cityRepository->findOrFail($data['city_id']);
            $this->validateCityOnProvince($city, $data['province_id']);
            $hasAddress = $this->userAddress->existsByUser($userId);

            if (!$hasAddress) {
                $data['is_default'] = true;
            }

            if (!empty($data['is_default']) && $data['is_default'] === true) {
                $this->userAddress->unsetDefaultByUser($userId);
            }

            $userAddressData = [
                'user_id' => $userId,
                'label' => $data['label'],

                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],

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
                'other' => $data['other'] ?? null,

                'is_default' => $data['is_default'] ?? false,
            ];
            $result = $this->userAddress->store($userAddressData);
            $this->invalidateCache('user_addresses');
            return $result;
        });
    }

    public function update(string $id, array $data): UserAddress
    {
        $user = auth()->user();
        $userAddress = $this->userAddress->find($id);
        if (!$userAddress) {
            throw new ResourceNotFoundException("User Address");
        }
        Gate::authorize('update', $userAddress);
        $userAddressCount = $this->userAddress->countDataUser($user->id);
        if (
            $userAddressCount === 1 &&
            array_key_exists('is_default', $data) &&
            $data['is_default'] === false
        ) {
            throw ValidationException::withMessages([
                'is_default' => 'Tidak bisa menghapus default jika hanya satu alamat'
            ]);
        }
        $city = null;
        if (array_key_exists('city_id', $data)) {
            $city = $this->cityRepository->find($data['city_id']);
            $this->validateCityOnProvince($city, $data['province_id']);
        }
        $userAddressData = array_filter([
            'label' => $data['label'] ?? $userAddress->label,

            'recipient_name' => $data['recipient_name'] ?? $userAddress->recipient_name,
            'recipient_phone' => $data['recipient_phone'] ?? $userAddress->recipient_phone,

            'province_id' => $city['province_id'] ?? $userAddress->province_id,
            'province_name' => $data['province_name'] ?? $userAddress->province_name,

            'city_id' => $city['id'] ?? $userAddress->city_id,
            'city_name' => $city['name'] ?? $userAddress->city_name,

            'district_id' => $data['district_id'] ?? $userAddress->district_id,
            'district_name' => $data['district_name'] ?? $userAddress->district_name,

            'village_id' => $data['village_id'] ?? $userAddress->village_id,
            'village_name' => $data['village_name'] ?? $userAddress->village_name,

            'postal_code' => $data['postal_code'] ?? $userAddress->postal_code,
            'address' => $data['address'] ?? $userAddress->address,
            'other' => $data['other'] ?? $userAddress->other,

            'is_default' => $data['is_default'] ?? $userAddress->is_default,
        ], fn($v) => !is_null($v));

        $result = $this->userAddress->update($userAddress, $userAddressData);
        $this->invalidateCache('user_address');
        return $result;
    }

    public function storeRajaOngkir(array $data, string $userId): UserAddress
    {
        return DB::transaction(function () use ($data, $userId) {
            $city = $this->rajaOngkirService->getCity($data['shipping_city_id']);
            if ((int) $city['province_id'] !== (int) $data['shipping_province_id']) {
                throw ValidationException::withMessages([
                    'shipping_city_id' => 'City does not belong to selected province'
                ]);
            }
            $hasAddress = $this->userAddress->existsByUser($userId);

            if (!$hasAddress) {
                $data['is_default'] = true;
            }

            if (!empty($data['is_default']) && $data['is_default'] === true) {
                $this->userAddress->unsetDefaultByUser($userId);
            }

            $userAddressData = [
                'user_id' => $userId,
                'label' => $data['label'],

                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],

                'shipping_province_id' => $city['province_id'],
                'shipping_province' => $city['province'],

                'shipping_city_id' => $city['city_id'],
                'shipping_city' => $city['city_name'],

                'district_id' => $data['district_id'],
                'district_name' => $data['district_name'],

                'village_id' => $data['village_id'],
                'village_name' => $data['village_name'],

                'postal_code' => $data['postal_code'] ?? null,
                'address' => $data['address'],
                'other' => $data['other'] ?? null,

                'is_default' => $data['is_default'] ?? false,
            ];
            $result = $this->userAddress->store($userAddressData);
            $this->invalidateCache('user_address');
            return $result;
        });
    }
    public function updateRajaOngkir(string $id, array $data): UserAddress
    {
        $user = auth()->user();
        $userAddress = $this->userAddress->find($id);
        if (!$userAddress) {
            throw new ResourceNotFoundException("User Address");
        }
        Gate::authorize('update', $userAddress);
        $userAddressCount = $this->userAddress->countDataUser($user->id);
        if (
            $userAddressCount === 1 &&
            array_key_exists('is_default', $data) &&
            $data['is_default'] === false
        ) {
            throw ValidationException::withMessages([
                'is_default' => 'Tidak bisa menghapus default jika hanya satu alamat'
            ]);
        }
        $city = null;
        if (array_key_exists('shipping_city_id', $data)) {
            $city = $this->rajaOngkirService->getCity($data['shipping_city_id']);

            if ((int) $city['province_id'] !== (int) $data['shipping_province_id']) {
                throw ValidationException::withMessages([
                    'shipping_city_id' => 'City does not belong to selected province'
                ]);
            }
        }
        $userAddressData = array_filter([
            'label' => $data['label'] ?? $userAddress->label,

            'recipient_name' => $data['recipient_name'] ?? $userAddress->recipient_name,
            'recipient_phone' => $data['recipient_phone'] ?? $userAddress->recipient_phone,

            'shipping_province_id' => $city['province_id'] ?? $userAddress->shipping_province_id,
            'shipping_province' => $city['province'] ?? $userAddress->shipping_province,

            'shipping_city_id' => $city['city_id'] ?? $userAddress->shipping_city_id,
            'shipping_city' => $city['city_name'] ?? $userAddress->shipping_city,

            'district' => $data['district'] ?? $userAddress->district,
            'postal_code' => $data['postal_code'] ?? $userAddress->postal_code,
            'address' => $data['address'] ?? $userAddress->address,

            'is_default' => $data['is_default'] ?? $userAddress->is_default,
        ], fn($v) => !is_null($v));

        $result = $this->userAddress->update($userAddress, $userAddressData);
        $this->invalidateCache('user_address');
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
        $userAddress = $this->userAddress->find($id);
        Gate::authorize('delete', $userAddress);
        return $this->userAddress->delete($userAddress);
    }
}
