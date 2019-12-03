<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use DateTime;
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

    public function addOrUpdate(Recipe $recipe): Recipe
    {
        $recipe->setUpdatedAt(new DateTime());

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $recipe;
    }

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