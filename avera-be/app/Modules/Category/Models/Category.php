<?php

namespace App\Modules\Category\Models;

use App\Modules\Image\Models\Image;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'image_id',
        'name',
        'slug',
        'default_age_rating',
        'description',
        'status',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
        // catatan : ambil di model category ini cari seluruh row category yang memiliki parent_id sama dengan id model category ini 
    }
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
    public function getBreadcrumbPath(): array
    {
        $path = [];
        $category = $this;

        // Naik sampai parent = null
        while ($category) {
            array_unshift($path, [
                'name' => $category->name,
                'slug' => $category->slug
            ]);
            $category = $category->parent;
        }

        return $path;
    }
}
