<?php

namespace App\Modules\Order\Repositories;

use App\Modules\Order\Models\CourierSla;
use App\Modules\Order\Models\Shipment;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ShipmentRepository
{
    use CacheVersionable;

    public function __construct(
        private Shipment $model,
        private CourierSla $courier
    ) {}

    public function getCourier(): Collection
    {
        $version = $this->versionKey('courier_slas');
        return Cache::remember(
            "shipment:list:courier:slas:v{$version}",
            now()->addMinutes(5),
            fn() => $this->courier->get()
        );
    }
    
    public function getUserOrder(string $id): LengthAwarePaginator
    {
        $version = $this->versionKey('shipments');
        return Cache::remember(
            "shipment:list:userId:{$id}:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('user_id', $id)->orderBy('updated_at', 'DESC')->paginate(5)
        );
    }

    public function find(int $id): ?Shipment
    {
        $version = $this->versionKey('shipments');
        return Cache::remember(
            "shipment:list:id:{$id}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->find($id)
        );
    }
    public function store(array $data): Shipment
    {
        return $this->model->create($data);
    }
}
