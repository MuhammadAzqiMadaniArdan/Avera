<?php

namespace App\Modules\Store\Policies;

use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;

class StorePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Store $store): bool
    {
        return $user === 'seller' && $store->id === $user->store->id;
    }
    /**
     * Determine whether the user can view List Product the model.
     */
    public function viewSellerProduct(User $user, Store $store): bool
    {
        return $user->role === 'seller' && $store->id === $user->store->id || $user->role === 'adminx';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): bool
    {
        return $user->role = "seller" && $user->id === $store->user_id || $user->role = "admin";
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Store $store): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Store $store): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Store $store): bool
    {
        return false;
    }
}
