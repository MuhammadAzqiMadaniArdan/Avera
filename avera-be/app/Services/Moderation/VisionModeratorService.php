<?php

namespace App\Services\Moderation;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\ImageSource;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;

class VisionModeratorService
{
    protected ImageAnnotatorClient $client;

    public function __construct()
    {
        $this->client = new ImageAnnotatorClient([
            'credentials' => config('services.google.vision_credentials'),
        ]);
    }

    public function checkImage(string $imageUrl): array
    {
        $image = (new Image())->setSource((new ImageSource())->setImageUri($imageUrl));
        $feature = (new Feature())->setType(Feature\Type::SAFE_SEARCH_DETECTION);

        $annotateRequest = (new AnnotateImageRequest())
            ->setImage($image)
            ->setFeatures([$feature]);

        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$annotateRequest]);

        $response = $this->client->batchAnnotateImages($batchRequest);

        $annotation = $response->getResponses()[0]->getSafeSearchAnnotation();

        return [
            'adult'    => $annotation->getAdult(),
            'violence' => $annotation->getViolence(),
            'racy'     => $annotation->getRacy(),
            'medical'  => $annotation->getMedical(),
            'spoof'    => $annotation->getSpoof(),
        ];
    }

    public function __destruct()
    {
        $this->client->close();
    }
}
