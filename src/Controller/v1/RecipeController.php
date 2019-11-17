<?php
declare(strict_types = 1);

namespace App\Controller\v1;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route(path="/v1/recipe")
 */
class RecipeController
{
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @param RecipeRepository $recipeRepository
     * @param EntityManagerInterface $entityManager
     * @param NormalizerInterface $normalizer
     */
    public function __construct(
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route(path="/", methods={"POST"})
     *
     * @param Request $request
     * @param NormalizerInterface $normalizer
     *
     * @return JsonResponse
     *
     * @throws ExceptionInterface
     */
    public function createRecipe(Request $request): JsonResponse
    {
        $recipe = (new Recipe())
            ->setName($request->get('name'))
            ->setAuthor($request->get('author'));

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return new JsonResponse($this->normalizer->normalize($recipe, 'array'), 201);
    }

    /**
     * @Route(path="/", methods={"GET"})
     *
     * @return JsonResponse
     *
     * @throws ExceptionInterface
     */
    public function listRecipes(): JsonResponse
    {
        $recipes = $this->recipeRepository->findAll();

        return new JsonResponse($this->normalizer->normalize($recipes, 'array'));
    }
}
