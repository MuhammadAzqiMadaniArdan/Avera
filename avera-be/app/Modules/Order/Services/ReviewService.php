<?php

namespace App\Modules\Order\Services;

use App\Events\ReviewCreated;
use App\Modules\Order\Contracts\ReviewServiceInterface;
use App\Modules\Order\Models\Review;
use App\Modules\Order\Repositories\OrderItemRepository;
use App\Modules\Order\Repositories\ReviewRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewService implements ReviewServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private ReviewRepository $reviewRepository,
        private UserRepository $userRepository,
        private OrderItemRepository $orderItemRepository
    ) {}
    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->reviewRepository->get($perPage);
    }
    public function filter(array $filters): LengthAwarePaginator
    {
        return $this->reviewRepository->filter($filters);
    }
    public function getByProduct(array $filters,string $productId): LengthAwarePaginator
    {
        $filters['product_id'] = $productId;
        return $this->filter($filters);
    }
    public function find(string $id): ?Review
    {
        return $this->reviewRepository->find($id);
    }
    public function getAdmin(int $perPage): LengthAwarePaginator
    {
        return $this->reviewRepository->getAdmin($perPage);
    }
    public function store(array $data, string $userId): Review
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['user_id'] = $userId;
            $orderItem = $this->orderItemRepository->find($data['order_item_id']);
            $data['product_id'] = $orderItem->product_id;

            $review = $this->reviewRepository->store($data);

            event(new ReviewCreated($review));
            $this->invalidateCache('reviews');
            return $review;
        });
    }
    public function storeAdmin(array $data): Review
    {
        $review = $this->reviewRepository->store($data);
        $this->invalidateCache('reviews');
        return $review;
    }
    public function storeUser(array $data): Review
    {
        $data['status'] = 'pending';
        $data['slug'] = Str::slug($data['name']) . '-' . substr(Str::uuid(), 0, 6);
        $result = $this->reviewRepository->store($data);
        $this->invalidateCache('reviews');
        return $result;
    }
    public function update(string $id, array $data): Review
    {
        $review = $this->reviewRepository->find($id);
        $result = $this->reviewRepository->update($review, $data);
        $this->invalidateCache('reviews');
        return $result;
    }
    public function delete(string $id): bool
    {
        $review = $this->reviewRepository->find($id);
        return $this->reviewRepository->delete($review);
    }
    public function deletePermanent(string $id): bool
    {
        $review = $this->reviewRepository->findByTrashed($id);
        return $this->reviewRepository->deletePermanent($review);
    }
    public function restore(string $id): bool
    {
        $review = $this->reviewRepository->findByTrashed($id);
        return $this->reviewRepository->restore($review);
    }
    public function findBySlug(string $slug): ?Review
    {
        return $this->reviewRepository->findBySlug($slug);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->reviewRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?Review
    {
        return $this->reviewRepository->findByTrashed($id);
    }
}
