<?php

namespace App\Modules\Checkout\Policies;

use App\Modules\Checkout\Models\Checkout;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\Response;

class CheckoutPolicy
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
    public function view(User $user, Checkout $checkout): bool
    {
        return false;
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
    public function update(User $user, Checkout $checkout): bool
    {
        return $user->id === $checkout->user_id;
    }
    /**
     * Determine whether the user can order from checkout the model.
     */
    public function checkoutToOrder(User $user, Checkout $checkout): bool
    {
    //     dd([
    //     'policy_user' => $user->id,
    //     'auth_user'   => auth()->id(),
    //     'checkout'    => $checkout->user_id,
    // ]);
        return (string) $user->id === (string) $checkout->user_id;;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Checkout $checkout): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Checkout $checkout): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Checkout $checkout): bool
    {
        return false;
    }
}
