<?php

namespace App\Modules\Order\Repositories;

use App\Modules\Payment\Models\Payment;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PaymentRepository 
{
    use CacheVersionable;

    public function __construct(private Payment $model) {}
    public function getUserPayment(string $id): ?LengthAwarePaginator
    {
        $version = $this->versionKey('orders');
        return Cache::remember(
            "order:list:userId:{$id}:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('user_id', $id)->orderBy('updated_at', 'DESC')->paginate(5)
        );
    }

    public function find(int $id): ?Payment
    {
        $version = $this->versionKey('orders');
        return Cache::remember(
            "order:list:id:{$id}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->find($id)
        );
    }
    public function store(array $data): Payment
    {
        return $this->model->create($data);
    }
    public function update(Payment $promotion, array $data): Payment
    {
        $promotion->update($data);
        return $promotion;
    }
}
