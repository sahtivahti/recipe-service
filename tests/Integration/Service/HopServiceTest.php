<?php
declare(strict_types = 1);

namespace App\Tests\Integration\Service;

use App\Repository\HopRepository;
use App\Service\HopService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HopServiceTest extends TestCase
{
    /**
     * @var HopRepository|MockObject
     */
    private $recipeRepositoryMock;

    /**
     * @var HopService
     */
    private HopService $hopService;

    protected function setUp(): void
    {
        $this->recipeRepositoryMock = $this->createMock(HopRepository::class);

        $this->hopService = new HopService(
            $this->recipeRepositoryMock
        );
    }

    public function testThatFindByIdCallsRepository(): void
    {
        $this->recipeRepositoryMock->expects(static::once())->method('find')->with(1);

        $this->hopService->getById(1);
    }
}
