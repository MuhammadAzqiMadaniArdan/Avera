<?php

namespace App\Modules\Voucher\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'voucherable_id',
        'voucherable_type',
        'status',
        'claimed_at',
        'used_at'
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function voucherable()
    {
        return $this->morphTo();
    }
    public function isUsed()
    {
        return $this->status === 'used';
    }
    public function isExpired()
    {
        return $this->status === 'expired';
    }
    
}
