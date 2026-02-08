<?php

namespace App\Modules\Banner\Models;

use App\Modules\Image\Models\Image;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasUuids,HasFactory,SoftDeletes;
    
    protected $fillable = [
        'title',
        'primary_image_id',
        'owner_type',
        'owner_id',
        'link_type',
        'link_id',
        'link_url',
        'type',
        'audience',
        'priority',
        'is_active',
        'start_at',
        'end_at',
        'moderation_visibility',
        'moderated_at',
        'created_by',
        'approved_by'
    ];

    public function primaryImage()
    {
        return $this->belongsTo(Image::class,'primary_image_id');
    }
    public function images()
    {
        return $this->belongsToMany(Image::class,'banner_images')
                    ->withPivot(['position','is_active'])
                    ->withTimestamps()
                    ->orderBy('pivot_position');
    }
    public function hasCarousel() : bool
    {
        return $this->images()->exists();
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function approved_by()
    {
        return $this->belongsTo(User::class,'approved_by');
    }
}
