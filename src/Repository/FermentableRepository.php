<?php

namespace App\Repository;

use App\Entity\Fermentable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Fermentable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fermentable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fermentable[]    findAll()
 * @method Fermentable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FermentableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fermentable::class);
    }
}
