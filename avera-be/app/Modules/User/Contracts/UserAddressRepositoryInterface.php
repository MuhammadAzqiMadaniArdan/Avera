<?php

namespace App\Modules\User\Contracts;

use App\Modules\User\Models\UserAddress;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserAddressRepositoryInterface {
    public function get(string $userId) : Collection; 
    public function find(string $id) : ?UserAddress; 
    public function store(array $data) : UserAddress; 
    public function update(UserAddress $category,array $data) : UserAddress; 
    public function delete(UserAddress $category) : bool; 
}