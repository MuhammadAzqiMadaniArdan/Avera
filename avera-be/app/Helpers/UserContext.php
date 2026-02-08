<?php

namespace App\Helpers;

use App\Modules\User\Models\User;

final class UserContext
{
    public function __construct(
        public readonly ?User $user
    ) {}

    public static function current(): self
    {
        return new self(auth()->user());
    }

    public function isGuest(): bool
    {
        return $this->user === null;
    }

    public function isAdmin(): bool
    {
        return $this->user?->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->user?->role === 'seller';
    }
}
