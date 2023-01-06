<?php

namespace App\Normalizer;

use App\Entity\ResizeVideo;
use App\Rest\Normalizer\AbstractNormalizer;

class ResizedVideoNormalize extends AbstractNormalizer
{

    /**
     * @param ResizeVideo $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->id,
            'path' => $object->path,
            'quality' => $object->quality,
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof ResizeVideo;
    }
}