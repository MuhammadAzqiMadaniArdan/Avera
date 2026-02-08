<?php

namespace App\Modules\User\Contracts;

use App\Modules\User\Models\UserAddress;
use Illuminate\Support\Collection;

interface UserAddressServiceInterface {
      public function get(string $userId) : Collection; 
    public function find(string $id) : ?UserAddress; 
    public function store(array $data,string $userId) : UserAddress; 
    public function update(string $id,array $data) : UserAddress; 
    public function delete(string $id) : bool; 
}