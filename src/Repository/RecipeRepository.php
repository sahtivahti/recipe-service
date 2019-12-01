<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param Recipe $recipe
     *
     * @return Recipe
     *
     * @throws ORMException
     */
    public function saveRecipe(Recipe $recipe): Recipe
    {
       $this->getEntityManager()->persist($recipe);
       $this->getEntityManager()->flush($recipe);

       return $recipe;
    }

    /**
     * @param Recipe $recipe
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteRecipe(Recipe $recipe): void
    {
        $this->getEntityManager()->remove($recipe);
        $this->getEntityManager()->flush($recipe);
    }
}
