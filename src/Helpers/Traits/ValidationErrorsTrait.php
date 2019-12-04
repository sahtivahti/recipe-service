<?php
declare(strict_types = 1);

namespace App\Helpers\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ValidationErrorsTrait
{
    protected function createValidationErrorResponse(ConstraintViolationListInterface $validationErrors): JsonResponse
    {
        return new JsonResponse(['errors' => (string)$validationErrors], Response::HTTP_BAD_REQUEST);
    }
}
