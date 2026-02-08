<?php

namespace App\Modules\Checkout\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Checkout\Contracts\CheckoutRepositoryInterface;
use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Models\CheckoutShipment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CheckoutRepository implements CheckoutRepositoryInterface
{
    public function __construct(
        private Checkout $model,
        private CheckoutShipment $shipment,
    ) {}

    public function get(string $userId): ?Checkout
    {
        $query = $this->model->where('user_id', $userId);
        if (empty($query)) {
            return null;
        }
        return $query->with(['items.store','items.product','userAddress','user.userAddresses','shipments'])->where('status', 'pending')->first();
    }
    public function getUserCheckout(string $userId)
    {
        $user = $this->model->where('user_id', $userId)->get();
    }
    public function find(string $id): ?Checkout
    {
        $checkout = $this->model->find($id);
        if (!$checkout) {
            throw new ResourceNotFoundException("Checkout");
        }
        $checkout->with(['items.store','items.product','userAddress','user.userAddresses','shipments']);
        return $checkout;
    }
    public function findByShipment(string $checkoutId,string $shipmentId,string $storeId): ?Checkout
    {
        $shipment = $this->shipment->where('checkout_id',$checkoutId)->where('id',$shipmentId)->where('store_id',$storeId)->first();
        if (!$shipment) {
            throw new ResourceNotFoundException("Checkout Shipment");
        }
        return $shipment;
    }
    public function findSelectedShipment(string $checkoutId,string $storeId): ?Checkout
    {
        $shipment = $this->shipment->where('checkout_id',$checkoutId)->where('store_id',$storeId)->where('is_selected',true)->first();
        if (!$shipment) {
            throw new ResourceNotFoundException("Checkout Shipment");
        }
        return $shipment;
    }
   
    public function store(array $data): Checkout
    {
        return $this->model->create($data);
    }
    public function shipmentStore(array $data): CheckoutShipment
    {
        return $this->shipment->create($data);
    }
    public function update(Checkout $checkout, array $data): Checkout
    {
        $checkout->update($data);
        return $checkout;
    }
    public function delete(Checkout $checkout): bool
    {
        return $checkout->delete();
    }
}
