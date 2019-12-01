<?php
declare(strict_types = 1);

namespace App\Tests\Integration\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeServiceTest extends TestCase
{
    /**
     * @var RecipeRepository|MockObject
     */
    private $recipeRepositoryMock;

    /**
     * @var RecipeService
     */
    private RecipeService $recipeService;

    protected function setUp(): void
    {
        $this->recipeRepositoryMock = $this->createMock(RecipeRepository::class);

        $this->recipeService = new RecipeService($this->recipeRepositoryMock);
    }

    /**
     * @throws ORMException
     */
    public function testThatSaveRecipeCallsRepository(): void
    {
        $recipe = new Recipe();

        $this->recipeRepositoryMock->expects(static::once())->method('saveRecipe')->with($recipe);
        $this->recipeService->addOrUpdate($recipe);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testThatDeleteRecipeCallsRepository(): void
    {
        $recipe = new Recipe();

        $this->recipeRepositoryMock->expects(static::once())->method('deleteRecipe')->with($recipe);
        $this->recipeService->deleteRecipe($recipe);
    }

    public function testThatGetAllRecipesCallsRepository(): void
    {
        $this->recipeRepositoryMock->expects(static::once())->method('findAll')->willReturn([]);
        $this->recipeService->getAllRecipes();
    }
}
