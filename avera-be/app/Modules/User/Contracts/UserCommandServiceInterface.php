<?php

namespace App\Modules\User\Contracts;

use App\Modules\User\Models\User;

interface UserCommandServiceInterface {
    public function storeProfile(string $identityId,string $email) : User;
    public function updateProfile(string $identityId,array $data) : User;
}