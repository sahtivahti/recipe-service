<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Recipe;
use App\Helpers\Traits\ValidationErrorsTrait;
use App\Model\RecipeSearchFilters;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(path="/v1/recipe")
 */
class RecipeController extends AbstractController
{
    use ValidationErrorsTrait;

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
        $errors = $this->validator->validate($fromBody, null, ['Create', 'Default']);

        if (count($errors) > 0) {
            return $this->createValidationErrorResponse($errors);
        }

        $recipe = $this->recipeService->addOrUpdate($fromBody);

        return $this->json($recipe, Response::HTTP_CREATED, [], ['groups' => ['Details']]);
    }

    /**
     * @Route(path="", methods={"GET"})
     *
     * @param RecipeSearchFilters $filtersFromBody
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function listRecipes(RecipeSearchFilters $filtersFromBody): JsonResponse
    {
        $recipes = $this->recipeService->getAllRecipes($filtersFromBody);

        return $this->json($recipes, Response::HTTP_OK, [], ['groups' => ['Listing']]);
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

        return $this->json($recipe, Response::HTTP_OK, [], ['groups' => ['Details']]);
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
            return $this->createValidationErrorResponse($errors);
        }

        $oldRecipe = $this->recipeService->getById($id);

        if ($oldRecipe === null) {
            throw $this->createNotFoundException();
        }

        $oldRecipe
            ->setAuthor($fromBody->getAuthor())
            ->setName($fromBody->getName())
            ->setStyle($fromBody->getStyle())
            ->setBatchSize($fromBody->getBatchSize());

        $this->recipeService->addOrUpdate($oldRecipe);

        return $this->json($oldRecipe, Response::HTTP_OK, [], ['groups' => ['Details']]);
    }

    /**
     * @Route(path="/{id}", methods={"DELETE"})
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteRecipe(int $id): JsonResponse
    {
        $recipe = $this->recipeService->getById($id);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        $this->recipeService->deleteRecipe($recipe);

        return $this->json($recipe, Response::HTTP_OK, [], ['groups' => ['Details']]);
    }
}
