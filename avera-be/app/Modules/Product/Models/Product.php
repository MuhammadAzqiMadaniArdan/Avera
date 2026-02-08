<?php

namespace App\Modules\Product\Models;

use App\Modules\Category\Models\Category;
use App\Modules\Image\Models\Image;
use App\Modules\Order\Models\Review;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'category_id',
        'primary_image_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'weight',
        'status',
        'age_rating',
        'moderation_visibility',
        'moderated_at'
    ];

    /* ======================
     | Relations
     ====================== */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'product_images')
            ->withPivot(['is_primary', 'position'])
            ->orderByDesc('product_images.is_primary')
            ->orderBy('product_images.position')
            ->withTimestamps();
    }

    public function primaryImage()
    {
        return $this->belongsTo(Image::class, 'primary_image_id');
    }

    /* ======================
     | Moderation Logic
     ====================== */

    public function hasBlockedImages(): bool
    {
        return $this->images()
            ->whereIn('moderation_status', ['pending', 'warning', 'rejected'])
            ->exists();
    }

    public function resolveVisibility(): string
    {
        if ($this->hasBlockedImages()) {
            return 'hidden';
        }

        return $this->moderation_visibility;
    }

}
