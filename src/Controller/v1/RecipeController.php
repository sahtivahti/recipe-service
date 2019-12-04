<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Recipe;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(path="/v1/recipe")
 */
class RecipeController extends AbstractController
{
    private RecipeService $recipeService;

    private ValidatorInterface $validator;

    public function __construct(
        RecipeService $recipeService,
        ValidatorInterface $validator
    ) {
        $this->recipeService = $recipeService;
        $this->validator = $validator;
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
        $errors = $this->validator->validate($fromBody);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string)$errors], 400);
        }

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
        $recipe = $this->recipeService->getById($id);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        return $this->json($recipe);
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
        $errors = $this->validator->validate($fromBody);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string)$errors]);
        }

        $oldRecipe = $this->recipeService->getById($id);

        if ($oldRecipe === null) {
            throw $this->createNotFoundException();
        }

        $oldRecipe
            ->setAuthor($fromBody->getAuthor())
            ->setName($fromBody->getName());

        $this->recipeService->addOrUpdate($oldRecipe);

        return $this->json($oldRecipe);
    }
}
