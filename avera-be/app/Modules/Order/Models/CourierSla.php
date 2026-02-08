<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierSla extends Model
{
    use HasFactory;
    protected $fillable = [
        'courier_code',
        'courier_ncouame',
        'min_days',
        'max_days',
        'is_active'
    ];
}
