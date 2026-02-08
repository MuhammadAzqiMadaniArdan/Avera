<?php

namespace App\Modules\User\Contracts;

use App\Modules\User\Models\User;

interface UserRepositoryInterface {
    public function getProfile(string $id) : User;
    public function storeProfile(string $identityId,string $email) : User;
    public function update(User $user,array $data) : User;
    public function find(string $id) : ?User;
}