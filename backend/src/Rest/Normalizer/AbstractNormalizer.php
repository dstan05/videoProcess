<?php

namespace App\Rest\Normalizer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer as SymfonyAbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Абстрактный класс с готовыми методами для работы с группами.
 */
abstract class AbstractNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param array<string, mixed> $context
     */
    abstract public function normalize(mixed $object, string $format = null, array $context = []): mixed;

    /**
     * Проверяет наличие группы в контексте.
     *
     * @param array<string, mixed> $context контекст
     * @param string               $group   группа
     */
    public function hasGroup(array $context, string $group): bool
    {
        return isset($context[SymfonyAbstractNormalizer::GROUPS]) &&
            is_array($context[SymfonyAbstractNormalizer::GROUPS]) &&
            in_array($group, $context[SymfonyAbstractNormalizer::GROUPS]);
    }

    /**
     * Удаляет группу из контекста.
     *
     * @param array<string, mixed> $context контекст
     * @param string               $group   группа
     */
    public function removeGroup(array &$context, string $group): void
    {
        if (!is_array($context[SymfonyAbstractNormalizer::GROUPS])) {
            return;
        }

        $key = array_search($group, $context[SymfonyAbstractNormalizer::GROUPS]);
        if (false === $key) {
            return;
        }

        unset($context[SymfonyAbstractNormalizer::GROUPS][$key]);
    }
}
