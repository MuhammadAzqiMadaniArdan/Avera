<?php

namespace App\Modules\Voucher\Models;

use App\Modules\Promotion\Models\Promotion;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'promotion_id',
        'store_id',
        'voucher_type',
        'name',
        'total_quota',
        'claimed_count',
        'usage_limit_per_user',
        'start_at',
        'end_at',
        'is_claimable',
        'is_active'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function userVouchers()
    {
        return $this->morphMany(UserVoucher::class, 'voucherable');
    }
}
