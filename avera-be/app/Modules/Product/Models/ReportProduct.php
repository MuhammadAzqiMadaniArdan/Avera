<?php

namespace App\Modules\Product\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportProduct extends Model
{
    protected $fillable = [
        'product_id',
        'reported_user_id',
        'reason',
        'description',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class);
    }
}
