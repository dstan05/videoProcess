<?php

namespace App\Normalizer;

use App\Entity\Video;
use App\Rest\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class VideoNormalizer extends AbstractNormalizer
{

    public const FULL = 'video_detail';

    /**
     * @param Video $object
     * @param string|null $format
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $data = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'url' => $object->getPath()
        ];

        if ($this->hasGroup($context, static::FULL)) {
            $data['thumbnails'] = null;
            foreach ($object->getResizedVideos() as $resizedVideo) {
                $data['thumbnails'][] = $this->normalizer->normalize($resizedVideo, $format, $context);
            }
        }

        return $data;
    }

    /**
     * @param Video $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Video;
    }
}