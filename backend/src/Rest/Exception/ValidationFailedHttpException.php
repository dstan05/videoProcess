<?php

namespace App\Rest\Exception;

use App\Rest\Normalizer\ProblemNormalizer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * HTTP-ошибка, выбрасываемая при проблемах с валидацией.
 * Кроме стандартного для BadRequest вывода содержит в себе список нарушений валидации.
 *
 * @see ProblemNormalizer
 */
class ValidationFailedHttpException extends BadRequestHttpException
{
    private ConstraintViolationListInterface $violations;

    /**
     * @param string[] $headers
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Validation Failed',
        Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ) {
        $this->violations = $violations;
        parent::__construct($message, $previous, $code, $headers);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
