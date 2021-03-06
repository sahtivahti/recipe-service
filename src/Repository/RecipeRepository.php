<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Model\RecipeSearchFilters;
use ArrayIterator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

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
     * @param RecipeSearchFilters $filters
     *
     * @return Recipe[]
     *
     * @throws Exception
     */
    public function search(RecipeSearchFilters $filters): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('r')
            ->from(Recipe::class, 'r')
            ->setMaxResults(20);

        $filters->applyTo($builder);

        $iterator = (new Paginator($builder, true))->getIterator();

        return $iterator instanceof ArrayIterator ? $iterator->getArrayCopy() : iterator_to_array($iterator);
    }
}
