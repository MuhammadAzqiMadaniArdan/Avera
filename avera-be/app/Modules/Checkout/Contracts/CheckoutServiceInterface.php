<?php

namespace App\Modules\Checkout\Contracts;

use App\Modules\Checkout\Models\Checkout;

interface CheckoutServiceInterface {
    public function get(string $checkoutId) : ?Checkout; 
    public function update(array $data,string $id) : Checkout; 
    public function delete(string $id) : bool; 
}