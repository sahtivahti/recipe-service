<?php
declare(strict_types = 1);

namespace App\Tests\Integration\Service;

use App\Repository\FermentableRepository;
use App\Service\FermentableService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FermentableServiceTest extends TestCase
{
    /**
     * @var FermentableRepository|MockObject
     */
    private $recipeRepositoryMock;

    /**
     * @var FermentableService
     */
    private FermentableService $fermentableService;

    protected function setUp(): void
    {
        $this->recipeRepositoryMock = $this->createMock(FermentableRepository::class);

        $this->fermentableService = new FermentableService(
            $this->recipeRepositoryMock
        );
    }

    public function testThatFindByIdCallsRepository(): void
    {
        $this->recipeRepositoryMock->expects(static::once())->method('find')->with(1);

        $this->fermentableService->getById(1);
    }
}
