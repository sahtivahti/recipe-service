<?php
declare(strict_types = 1);

namespace App\Helpers\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use function array_map;
use function iterator_to_array;

trait ValidationErrorsTrait
{
    public function createValidationErrorResponse(ConstraintViolationListInterface $validationErrors): JsonResponse
    {
        $errors = array_map(
            fn(ConstraintViolationInterface $violation) =>
            [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ],
            iterator_to_array($validationErrors)
        );

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
