<?php

namespace App\Modules\User\Contracts;

use App\Modules\User\Models\User;

interface UserQueryServiceInterface {
    public function find(string $id) : User;
    public function getProfile(string $identityId) : User;
}