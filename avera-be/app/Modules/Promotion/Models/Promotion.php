<?php

namespace App\Modules\Promotion\Models;

use App\Modules\Store\Models\Store;
use App\Modules\Voucher\Models\Voucher;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
     use HasUuids,HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',       
        'name',
        'type',  
        'start_at',
        'end_at',
        'is_active'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
