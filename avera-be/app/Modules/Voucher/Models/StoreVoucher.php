<?php

namespace App\Modules\Voucher\Models;

use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreVoucher extends Model
{
    use HasUuids,HasFactory,SoftDeletes;
    protected $fillable = [
        'store_id',
        'name',
        'code',
        'type',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount',
        'total_quota',
        'claimed_count',
        'usage_limit_per_user',
        'is_claimable',
        'start_at',
        'end_at'
    ];
    protected $casts = [
        'start_at' => 'dateTime',
        'end_at' => 'dateTime',
        'is_claimable' => 'boolean',
    ];
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function userVouchers()
    {
        return $this->morphMany(UserVoucher::class,'voucherable');
    }
}
