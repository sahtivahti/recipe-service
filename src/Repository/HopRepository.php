<?php

namespace App\Repository;

use App\Entity\Hop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hop[]    findAll()
 * @method Hop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hop::class);
    }
}
