<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/v1/recipe")
 */
class RecipeController extends AbstractController
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
     * @Route(path="", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createRecipe(Request $request): JsonResponse
    {
        $recipe = (new Recipe())
            ->setName($request->get('name'))
            ->setAuthor($request->get('author'));

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $this->json($recipe, 201);
    }

    /**
     * @Route(path="", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function listRecipes(): JsonResponse
    {
        $recipes = $this->recipeRepository->findAll();

        return $this->json($recipes);
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     *
     * @param Recipe $recipe
     *
     * @return JsonResponse
     */
    public function getRecipe(Recipe $recipe): JsonResponse
    {
        return $this->json($recipe);
    }

    /**
     * @Route(path="/{id}", methods={"PUT"})
     *
     * @param Recipe $recipe
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateRecipe(Recipe $recipe, Request $request): JsonResponse
    {
        $recipe
            ->setName($request->get('name', $recipe->getName()))
            ->setAuthor($request->get('author', $recipe->getAuthor()));

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $this->json($recipe);
    }
}
