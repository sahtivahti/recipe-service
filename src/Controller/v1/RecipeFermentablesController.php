<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Fermentable;
use App\Helpers\Traits\ValidationErrorsTrait;
use App\Service\FermentableService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeFermentablesController extends AbstractController
{
    use ValidationErrorsTrait;

    private RecipeService $recipeService;

    private ValidatorInterface $validator;

    private FermentableService $fermentableService;

    public function __construct(
        RecipeService $recipeService,
        ValidatorInterface $validator,
        FermentableService $fermentableService
    ) {
        $this->recipeService = $recipeService;
        $this->validator = $validator;
        $this->fermentableService = $fermentableService;
    }

    /**
     * @Route("/v1/recipe/{recipeId}/fermentable", methods={"POST"})
     *
     * @param Fermentable $fromBody
     * @param int $recipeId
     *
     * @return JsonResponse
     */
    public function addFermentableToRecipeAction(Fermentable $fromBody, int $recipeId): JsonResponse
    {
        $errors = $this->validator->validate($fromBody);

        if (count($errors) > 0) {
            return $this->createValidationErrorResponse($errors);
        }

        $recipe = $this->recipeService->getById($recipeId);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        $recipe->addFermentable($fromBody);

        $this->recipeService->addOrUpdate($recipe);

        return $this->json($fromBody, Response::HTTP_CREATED, [], ['groups' => ['Details']]);
    }

    /**
     * @Route("/v1/recipe/{recipeId}/fermentable/{fermentableId}", methods={"DELETE"})
     *
     * @param int $recipeId
     * @param int $fermentableId
     *
     * @return JsonResponse
     */
    public function removeFermentableFromRecipeAction(int $recipeId, int $fermentableId): JsonResponse
    {
        $recipe = $this->recipeService->getById($recipeId);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        $fermentable = $this->fermentableService->getById($fermentableId);

        if ($fermentable === null) {
            throw $this->createNotFoundException();
        }

        $recipe->removeFermentable($fermentable);

        $this->recipeService->addOrUpdate($recipe);

        return $this->json($fermentable, Response::HTTP_OK, [], ['groups' => ['Details']]);
    }
}
