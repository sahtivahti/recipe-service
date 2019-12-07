<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Helpers;

use App\Helpers\Traits\ValidationErrorsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use function json_decode;

class ValidationErrorsTraitTest extends TestCase
{
    /**
     * @var ValidationErrorsTrait|MockObject
     */
    private $validationErrorsTrait;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        $this->validationErrorsTrait = $this->getMockForTrait(ValidationErrorsTrait::class);
    }

    public function testThatResponseReturns400(): void
    {
        $violations = new ConstraintViolationList();

        $response = $this->validationErrorsTrait->createValidationErrorResponse($violations);

        static::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testThatResponseBodyContainsErrorsFormatted(): void
    {
        $violations = new ConstraintViolationList();
        $violations->add(new ConstraintViolation('This value is not expected', null, [], '', 'path.to.prop', '123'));

        $response = $this->validationErrorsTrait->createValidationErrorResponse($violations);
        $body = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $expectedBody = [
            'errors' => [
                [
                    'property' => 'path.to.prop',
                    'message' => 'This value is not expected'
                ]
            ]
        ];

        static::assertEquals($expectedBody, $body);
    }
}
