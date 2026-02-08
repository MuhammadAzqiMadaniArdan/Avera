<?php

namespace App\Modules\Image\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'disk',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'hash',
        'moderation_status',
        'moderation_reason',
        'moderation_result'
    ];

    protected $casts = [
        'moderation_result' => 'array'
    ];
    protected $appends = ['url'];
    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        return match ($this->disk) {
            'cloudinary' => cloudinary()
                ->image($this->path)
                ->secure()
                ->toUrl(),

            'public', 'local' => asset('storage/' . $this->path),

            default => null,
        };
    }
    public function cloudinaryUrl()
    {
        if ($this->disk !== 'cloudinary') {
            throw new \Exception('Image is 
            not stored in Cloudinary');
        }
        return cloudinary()->image($this->path)->secure()->toUrl();
    }
    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    public function isBlocked(): bool
    {
        return in_array($this->moderation_status, [
            'pending',
            'warning',
            'rejected'
        ]);
    }
}
