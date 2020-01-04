<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Hop;
use App\Helpers\Traits\ValidationErrorsTrait;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeHopsController extends AbstractController
{
    use ValidationErrorsTrait;

    private RecipeService $recipeService;

    private ValidatorInterface $validator;

    public function __construct(RecipeService $recipeService, ValidatorInterface $validator)
    {
        $this->recipeService = $recipeService;
        $this->validator = $validator;
    }

    /**
     * @Route("/v1/recipe/{recipeId}/hop", methods={"POST"})
     *
     * @param Hop $fromBody
     * @param int $recipeId
     *
     * @return JsonResponse
     */
    public function addHopToRecipeAction(Hop $fromBody, int $recipeId): JsonResponse
    {
        $errors = $this->validator->validate($fromBody);

        if (count($errors) > 0) {
            return $this->createValidationErrorResponse($errors);
        }

        $recipe = $this->recipeService->getById($recipeId);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        $recipe->addHop($fromBody);

        $this->recipeService->addOrUpdate($recipe);

        return $this->json($fromBody, Response::HTTP_CREATED, [], ['groups' => ['Details']]);
    }

    /**
     * @Route("/v1/recipe/{recipeId}/hop/{hopId}", methods={"DELETE"})
     *
     * @param int $recipeId
     * @param int $hopId
     *
     * @return JsonResponse
     */
    public function removeHopFromRecipeAction(int $recipeId, int $hopId): JsonResponse
    {
        $recipe = $this->recipeService->getById($recipeId);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        $hop = array_filter(
            $recipe->getHops()->toArray(),
            fn(Hop $hop) => $hop->getId() === $hopId
        )[0] ?? null;

        if ($hop === null) {
            throw $this->createNotFoundException();
        }

        $recipe->removeHop($hop);

        $this->recipeService->addOrUpdate($recipe);

        return $this->json($hop, Response::HTTP_OK, [], ['groups' => ['Details']]);
    }
}
