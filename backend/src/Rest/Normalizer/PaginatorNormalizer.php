<?php

namespace App\Rest\Normalizer;

use App\Rest\Tools\Paginator;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class PaginatorNormalizer extends AbstractNormalizer
{
    /**
     * @param Paginator<mixed>     $object
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'result' => $this->normalizer->normalize($object->getQuery()->getResult(), $format, $context),
            'pagination' => [
                'count' => $object->count(),
                'page' => $object->page(),
                'maxPage' => $object->maxPage(),
                'limit' => $object->limit(),
            ],
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Paginator;
    }
}
