<?php

namespace App\Modules\Image\Services;

use App\Modules\Image\Contracts\ImageServiceInterface;
use App\Modules\Image\Models\Image;
use App\Modules\Image\Repositories\ImageRepository;
use App\Services\Moderation\SightEngineModeratorService;
use App\Services\Moderation\VisionModeratorService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageService implements ImageServiceInterface
{
    public function __construct(private ImageRepository $imageRepository) {}
    public function upload(UploadedFile $file,string $ownerType,string $ownerId): Image
    {
        return DB::transaction(function () use ($file,$ownerType,$ownerId) {
            $hash = hash_file('sha256', $file->getRealPath());

            $exisiting = $this->imageRepository->findByHash($hash);
            if ($exisiting) {
                return $exisiting;
            }

            $uploaded = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'avera/images',
                    'resource_type' => 'image'
                ]
            );

            $data = [
                'owner_type' => $ownerType,
                'owner_id' => $ownerId,
                'disk' => 'cloudinary',
                'path' => $uploaded['public_id'],
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $uploaded['width'],
                'height' => $uploaded['height'],
                'hash' => $hash,
                'moderation_status' => 'pending'
            ];
            return $this->imageRepository->store($data);
        });
    }

    public function analyze(Image $image): array
    {
        $result = app(SightEngineModeratorService::class)->checkImage($image->cloudinaryUrl());
        $thresholdRejected = 0.7;
        $thresholdWarning = 0.4;

        $criticalCategories = [
            'weapon',
            'drugs',
            'offensive',
            'gore',
            'violence'
        ];
        $warningCategories = [
            'weapon',
            'drugs',
            'offensive',
            'gore',
            'violence',
            'suggestive',
            'mild',
        ];
        if ($result['sexual'] >= $thresholdRejected) {
            return $this->reject($result, 'explicit_sexual_content');
        }
        foreach ($criticalCategories as $key) {
            if (($result[$key] ?? 0) >= $thresholdRejected) {
                return $this->reject($result, $key);
            }
        }
        foreach ($warningCategories as $key) {
            if (($result[$key] ?? 0) >= $thresholdWarning) {
                return $this->warning($result);
            }
        }
        return $this->approve($result);
    }

    private function reject(array $result, string $reason): array
    {
        return [
            'status' => 'rejected',
            'reason' => $reason,
            'result' => $result,
        ];
    }
    private function warning(array $result): array
    {
        return [
            'status' => 'warning',
            'result' => $result,
        ];
    }
    private function approve(array $result): array
    {
        return [
            'status' => 'approved',
            'result' => $result,
        ];
    }

    public function replace(Image $image, UploadedFile $file): Image
    {
        return DB::transaction(function () use ($image, $file) {

            try {
                cloudinary()->uploadApi()->destroy($image->path);
            } catch (\Throwable $e) {
                Log::warning('Failed to delete old image from cloudinary', [
                    'image_id' => $image->id,
                    'path' => $image->path,
                    'error' => $e->getMessage()
                ]);
            }

            $uploaded = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'avera/images',
                    'resource_type' => 'image'
                ]
            );

            $data = [
                'path' => $uploaded['public_id'],
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $uploaded['width'],
                'height' => $uploaded['height'],
                'hash' => hash_file('sha256', $file->getRealPath()),
                'moderation_status' => 'pending',
            ];

            return $this->imageRepository->update($image, $data);
        });
    }

    public function delete(string $imageId)
    {
        $image = $this->imageRepository->find($imageId);
        return $this->imageRepository->delete($image);
    }

    public function primary() {}

    public function find(string $id): ?Image
    {
        return $this->imageRepository->find($id);
    }

    public function update(Image $image, array $data): Image
    {
        return $this->imageRepository->update($image, $data);
    }
}
