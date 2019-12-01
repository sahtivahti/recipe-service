<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class RecipeService
{
    private RecipeRepository $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @param Recipe $recipe
     *
     * @return Recipe
     *
     * @throws ORMException
     */
    public function addOrUpdate(Recipe $recipe): Recipe
    {
        return $this->recipeRepository->saveRecipe($recipe);
    }

    /**
     * @param Recipe $recipe
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteRecipe(Recipe $recipe): void
    {
        $this->recipeRepository->deleteRecipe($recipe);
    }

    /**
     * @return Recipe[]
     */
    public function getAllRecipes(): array
    {
        return $this->recipeRepository->findAll();
    }
}
