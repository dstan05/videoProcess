<?php

namespace App\Rest\Normalizer;

use App\Rest\Exception\ValidationFailedHttpException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer as DefaultProblemNormalizer;

/**
 * Дополнительная обертка над обработкой ошибок, которые показываются в REST API, для того, чтобы они соответствовали
 * нашей документации.
 */
class ProblemNormalizer extends DefaultProblemNormalizer implements NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param FlattenException     $object
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $data = parent::normalize($object, $format, $context);

        // типы ошибок заменяем своими
        $data['type'] = match ($object->getStatusCode()) {
            404 => '/docs/#tag/Error_NotFound',
            default => $data['type']
        };

        // По умолчанию в detail хранится текст сообщения только если $debug = false. Нам нужно, чтобы текст сообщения
        // выводился всегда.
        if (!empty($object->getMessage())) {
            $data['detail'] = $object->getMessage();
        } else {
            $data['detail'] = $object->getStatusText();
        }

        // если основная ошибка - ValidationFailed, то мы добавляем дополнительные данные для вывода ошибки
        // todo: мб стоит вынести в отдельный нормалайзер
        if (isset($context['exception']) && $context['exception'] instanceof ValidationFailedHttpException) {
            $data['type'] = '/docs/#tag/Error_ValidationFailed';

            $normalizedViolations = $this->normalizer->normalize(
                $context['exception']->getViolations(),
                $format,
                $context
            );

            if (is_array($normalizedViolations)) {
                $data['violations'] = $normalizedViolations['violations'];
                $data['detail'] = $normalizedViolations['detail'];
                $data['title'] = $normalizedViolations['title'];
            }
        }

        return $data;
    }
}
