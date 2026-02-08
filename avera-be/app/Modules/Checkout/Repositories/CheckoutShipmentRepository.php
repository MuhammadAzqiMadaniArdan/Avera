<?php

namespace App\Modules\Checkout\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Checkout\Contracts\CheckoutShipmentRepositoryInterface;
use App\Modules\Checkout\Models\CheckoutShipment;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CheckoutShipmentRepository implements CheckoutShipmentRepositoryInterface
{
    use CacheVersionable;
    public function __construct(
        private CheckoutShipment $model,
    ) {}

    public function get(string $checkoutId,string $storeId,string $userAddressId): ?Collection
    {
        $version = $this->versionKey('checkout_shipments');
        $shipment = Cache::remember("checkout:shipment:$checkoutId:$storeId:v{$version}", now()->addMinutes(5), fn () => $this->model->where('checkout_id', $checkoutId)->where('store_id',$storeId)->where('user_address_id',$userAddressId)->get()); 
        return $shipment;
    }
    public function find(string $id): ?CheckoutShipment
    {
        $shipment = $this->model->find($id);
        if (!$shipment) {
            throw new ResourceNotFoundException("Checkout");
        }
        $shipment->with(['items.store','items.product','userAddress','user.userAddresses','shipments']);
        return $shipment;
    }
    public function findByCheckoutAndStore(string $checkoutId,string $shipmentId,string $storeId): ?CheckoutShipment
    {
        $shipment = $this->model->where('checkout_id',$checkoutId)->where('id',$shipmentId)->where('store_id',$storeId)->first();
        if (!$shipment) {
            throw new ResourceNotFoundException("Checkout Shipment");
        }
        return $shipment;
    }
    public function findSelectedShipment(string $checkoutId,string $storeId): ?CheckoutShipment
    {
        $shipment = $this->model->where('checkout_id',$checkoutId)->where('store_id',$storeId)->where('is_selected',true)->first();
        if (!$shipment) {
            throw new ResourceNotFoundException("Checkout Shipment");
        }
        return $shipment;
    }
   
    public function store(array $data): CheckoutShipment
    {
        return $this->model->create($data);
    }
    public function update(CheckoutShipment $checkoutShipment, array $data): CheckoutShipment
    {
        $checkoutShipment->update($data);
        return $checkoutShipment;
    }
    public function delete(CheckoutShipment $checkoutShipment): bool
    {
        return $checkoutShipment->delete();
    }
}
