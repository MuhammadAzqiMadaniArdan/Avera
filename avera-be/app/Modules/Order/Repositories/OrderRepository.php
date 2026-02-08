<?php

namespace App\Modules\Order\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\UserContext;
use App\Modules\Order\Contracts\OrderRepositoryInterface;
use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private Order $model
    ) {}

    public function filter(array $filters, UserContext $ctx): LengthAwarePaginator
    {
        $query = $this->baseQuery($ctx);

        if (
            $ctx->isAdmin() &&
            !empty($filters['trashed'])
        ) {
            $query->onlyTrashed();
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['keyword'])) {
            $this->applySearch($query, $filters['keyword']);
        }

        if (
            $ctx->isAdmin() &&
            !empty($filters['condition'])
        ) {
            $this->applyCondition(
                $query,
                $filters['condition'] ?? null,
                $filters['selected_condition'] ?? null,
            );
        }

        $allowed = $this->allowedSorts();

        $sort = in_array($filters['sort'] ?? null, $allowed)
            ? $filters['sort']
            : 'created_at';

        $this->applySort(
            $query,
            $sort,
            $filters['order'] ?? 'desc'
        );

        return $query->with(['items.product', 'shipment', 'payment'])->paginate(48);
    }

    private function allowedSorts(): array
    {
        return ['total_price', 'created_at'];
    }

    private function baseQuery(): Builder
    {
        return $this->model->query();
    }

    private function applySearch(Builder $q, string $keyboard): void
    {
        $q->where(function ($qq) use ($keyboard) {
            $qq->where('name', 'ILIKE', "%{$keyboard}%")
                ->orWhere('description', 'ILIKE', "%{$keyboard}%");
        });
    }

    private function applySort(Builder $q, string $sort, string $direction): void
    {
        match ($sort) {
            'total_price'     => $q->orderBy('total_price', $direction),
            'created_at'     => $q->orderBy('created_at', $direction),
            default     => $q->orderBy('created_at', 'desc'),
        };
    }

    private function applyCondition(Builder $q, string $condition, string $selected): void
    {
        match ($condition) {
            'status' => $q->where('status', $selected),
            default => null
        };
    }

    public function get(array $filters): LengthAwarePaginator
    {
        $allowedPerPage = [12, 24, 48];
        $perPage = in_array($filters['per_page'] ?? 24, $allowedPerPage) ? $filters['per_page'] : 24;
        return $this->model->query()
            ->where('status', $filters['status'] ?? 'paid')
            ->where('moderation_visibility', 'public')
            ->when(
                $filters['sortBy'] ?? null,
                fn($q, $v) => $q->orderBy('created_at', $v),
                fn($q) => $q->orderByDesc('created_at')
            )->paginate($perPage);
    }

    public function find(int $id): ?Order
    {
        return $this->model->find($id);
    }
    public function findOrFail(string $id): Order
    {
        $order = $this->model
            ->with(['items', 'shipment', 'payment','items.review','items.product'])
            ->find($id);

        if (!$order) {
            throw new ResourceNotFoundException('Order');
        }
        return $order;
    }

    public function store(array $data): Order
    {
        return $this->model->create($data);
    }
    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order;
    }
    public function destroy(Order $order, array $data): Order
    {
        $order->update($data);
        return $order;
    }
}
