<?php

namespace App\Modules\Banner\Models;

use App\Modules\Image\Models\Image;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BannerImage extends Pivot
{
    use HasUuids,HasFactory;

    protected $fillable = [
        'banner_id',
        'image_id',
        'position'
    ];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
