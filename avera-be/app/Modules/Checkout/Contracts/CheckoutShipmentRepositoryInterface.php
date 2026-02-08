<?php

namespace App\Modules\Checkout\Contracts;

use App\Modules\Checkout\Models\CheckoutShipment;
use Illuminate\Support\Collection;

interface CheckoutShipmentRepositoryInterface {
    public function get(string $checkoutId,string $storeId,string $userAddressId) : ?Collection; 
    public function find(string $id) : ?CheckoutShipment; 
    public function store(array $data) : CheckoutShipment; 
    public function update(CheckoutShipment $checkoutShipment,array $data) : CheckoutShipment; 
    public function delete(CheckoutShipment $checkoutShipment) : bool; 
}