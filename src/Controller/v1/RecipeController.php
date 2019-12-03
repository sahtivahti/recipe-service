<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Recipe;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/v1/recipe")
 */
class RecipeController extends AbstractController
{
    private RecipeService $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    /**
     * @Route(path="", methods={"POST"})
     *
     * @param Recipe $fromBody
     *
     * @return JsonResponse
     */
    public function createRecipe(Recipe $fromBody): JsonResponse
    {
        $recipe = $this->recipeService->addOrUpdate($fromBody);

        return $this->json($recipe, 201);
    }

    /**
     * @Route(path="", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function listRecipes(): JsonResponse
    {
        $recipes = $this->recipeService->getAllRecipes();

        return $this->json($recipes);
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getRecipe(int $id): JsonResponse
    {
        return $this->json($this->recipeService->getById($id));
    }

    /**
     * @Route(path="/{id}", methods={"PUT"})
     *
     * @param Recipe $fromBody
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateRecipe(Recipe $fromBody, int $id): JsonResponse
    {
        $oldRecipe = $this->recipeService->getById($id);

        if ($oldRecipe === null) {
            $this->createNotFoundException();
        }

        $oldRecipe
            ->setAuthor($fromBody->getAuthor())
            ->setName($fromBody->getName());

        $this->recipeService->addOrUpdate($oldRecipe);

        return $this->json($oldRecipe);
    }
}
