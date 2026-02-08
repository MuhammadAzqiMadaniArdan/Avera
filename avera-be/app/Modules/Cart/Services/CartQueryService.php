<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Contracts\CartQueryServiceInterface;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Support\Facades\Log;

class CartQueryService implements CartQueryServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private CartRepository $cartRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {}
    public function get(string $userId)
    {
        $user = $this->userRepository->findByIdentityCoreId($userId);
        $cartItems = $this->cartRepository->get($user->id);
        $cartGroup = $cartItems->groupBy(
            fn($item) => $item->product->store_id
        );
        return $cartGroup;
    }
    public function find(string $id): ?CartItem
    {
        return $this->cartRepository->find($id);
    }
}
