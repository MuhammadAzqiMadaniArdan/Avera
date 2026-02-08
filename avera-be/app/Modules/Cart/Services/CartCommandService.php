<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Contracts\CartCommandServiceInterface;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Support\Facades\Gate;

class CartCommandService implements CartCommandServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private CartRepository $cartRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {}
    
    public function store(array $data, string $userId): CartItem
    {
        $user = $this->userRepository->findByIdentityCoreId($userId);
        $data['user_id'] = $user->id;
        $cartItem = $this->cartRepository->findByUserAndProduct($user->id, $data['product_id']);
        if ($cartItem) {
            $newQty = $cartItem->quantity + $data['quantity'];
            $this->checkedStockProduct($data['product_id'], $newQty);
            $this->cartRepository->update($cartItem, ['quantity' => $newQty]);
            $this->invalidateCache('carts');
            return $cartItem;
        }
        $this->checkedStockProduct($data['product_id'], $data['quantity']);
        $result = $this->cartRepository->store($data);
        $this->invalidateCache('carts');
        return $result;
    }
    private function checkedStockProduct(string $productId, int $qty)
    {
        $product = $this->productRepository->find($productId);
        $stock = $product->stock;
        if ($stock < $qty) {
            throw new \Exception("Produk Habis");
        }
        if ($qty > $stock) {
            throw new \Exception("Maksimal beli $stock");
        }
    }
    public function update(string $id, array $data): CartItem
    {
        $cartItem = $this->cartRepository->find($id);
        Gate::authorize('update', $cartItem);
        $stock = $cartItem->product->stock;
        if ($data['quantity'] > $stock) throw new Exception("Hanya ada tersisa $stock kuantitas dari produk ini");
        $result = $this->cartRepository->update($cartItem, $data);
        $this->invalidateCache('carts');
        return $result;
    }
    public function delete(string $id): bool
    {
        $cartItem = $this->cartRepository->find($id);
        Gate::authorize('delete', $cartItem);
        return $this->cartRepository->delete($cartItem);
    }
    public function flush(string $userId): bool
    {
        $user = $this->userRepository->findByIdentityCoreId($userId);
        return $this->cartRepository->flush($user->id);
    }
}
