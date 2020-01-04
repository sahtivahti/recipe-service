<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Hop;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeHopsController extends AbstractController
{
    /**
     * @var RecipeService
     */
    private RecipeService $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
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
        $recipe = $this->recipeService->getById($recipeId);

        if ($recipe === null) {
            throw $this->createNotFoundException();
        }

        $recipe->addHop($fromBody);

        $this->recipeService->addOrUpdate($recipe);

        return $this->json($fromBody, Response::HTTP_CREATED);
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

        $hop = null;

        $recipe->getHops()->map(fn(Hop $x) => $x->getId() === $hopId && $recipe->removeHop($x));

        $this->recipeService->addOrUpdate($recipe);

        return $this->json([]);
    }
}
