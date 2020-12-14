<?php

namespace App\Repository;

use App\Entity\ActionLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActionLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionLink[]    findAll()
 * @method ActionLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionLinkRepository extends ServiceEntityRepository
{
    const ALIAS = 'al';
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionLink::class);
    }

}
