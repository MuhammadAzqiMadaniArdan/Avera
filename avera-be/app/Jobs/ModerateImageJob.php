<?php

namespace App\Jobs;

use App\Events\ImageModerated;
use App\Modules\Image\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ModerateImageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $imageId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        ImageService $imageService
    ): void
    {
        $image = $imageService->find($this->imageId);
        if($image->moderation_status !== 'pending') {
            return;
        }
        $result = $imageService->analyze($image);
        $data = [
            'moderation_status' => $result['status'],
            'moderation_result' => $result['result'],
        ];
        $imageService->update($image,$data);
        
        event(new ImageModerated(
            $image->id,
            $image->owner_type,
            $image->owner_id,
            $image->moderation_status,
            ));
    }

}
