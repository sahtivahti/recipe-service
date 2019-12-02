<?php
declare(strict_types = 1);

namespace App\Tests\Integration\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeServiceTest extends TestCase
{
    /**
     * @var RecipeRepository|MockObject
     */
    private $recipeRepositoryMock;

    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManagerMock;

    /**
     * @var RecipeService
     */
    private RecipeService $recipeService;

    protected function setUp(): void
    {
        $this->recipeRepositoryMock = $this->createMock(RecipeRepository::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $this->recipeService = new RecipeService(
            $this->recipeRepositoryMock,
            $this->entityManagerMock
        );
    }

    public function testThatSaveRecipeCallsEntityManager(): void
    {
        $recipe = new Recipe();

        $this->entityManagerMock
            ->expects(static::once())
            ->method('persist')
            ->with($recipe);

        $this->entityManagerMock
            ->expects(static::once())
            ->method('flush');

        $this->recipeService->addOrUpdate($recipe);
    }

    public function testThatDeleteRecipeCallsRepository(): void
    {
        $recipe = new Recipe();

        $this->entityManagerMock
            ->expects(static::once())
            ->method('remove')
            ->with($recipe);

        $this->entityManagerMock
            ->expects(static::once())
            ->method('flush');

        $this->recipeService->deleteRecipe($recipe);
    }

    public function testThatGetAllRecipesCallsRepository(): void
    {
        $this->recipeRepositoryMock
            ->expects(static::once())
            ->method('findAll')
            ->willReturn([]);

        $this->recipeService->getAllRecipes();
    }
}
