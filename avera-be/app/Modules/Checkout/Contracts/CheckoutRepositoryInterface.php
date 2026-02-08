<?php

namespace App\Modules\Checkout\Contracts;

use App\Modules\Checkout\Models\Checkout;

interface CheckoutRepositoryInterface {
    public function get(string $checkoutId) : ?Checkout; 
    public function find(string $id) : ?Checkout; 
    public function store(array $data) : Checkout; 
    public function update(Checkout $checkout,array $data) : Checkout; 
    public function delete(Checkout $checkout) : bool; 
}