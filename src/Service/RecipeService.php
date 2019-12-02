<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;

class RecipeService
{
    private RecipeRepository $recipeRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Recipe $recipe
     *
     * @return Recipe
     */
    public function addOrUpdate(Recipe $recipe): Recipe
    {
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $recipe;
    }

    /**
     * @param Recipe $recipe
     */
    public function deleteRecipe(Recipe $recipe): void
    {
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();
    }

    /**
     * @return Recipe[]
     */
    public function getAllRecipes(): array
    {
        return $this->recipeRepository->findAll();
    }
}
