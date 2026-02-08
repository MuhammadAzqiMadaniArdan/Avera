<?php

namespace App\Modules\Voucher\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignVoucher extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'promotion_id','code','type','discount_type','discount_value',
        'min_order_amount','max_discount','total_quota','claimed_count',
        'usage_limit_per_user','start_at','end_at','is_active'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function userVouchers()
    {
        return $this->morphMany(UserVoucher::class, 'voucherable');
    }
}
