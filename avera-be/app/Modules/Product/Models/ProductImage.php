<?php

namespace App\Modules\Product\Models;

use App\Modules\Image\Models\Image;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasUuids,HasFactory,SoftDeletes;

    protected $fillable = [
        'product_id',
        'image_id',
        'is_primary',
        'position',
        'status',
        'replace_count',
        'last_replaced_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
