<?php

namespace App\Repository;

use App\Entity\ActionFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActionFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionFile[]    findAll()
 * @method ActionFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionFileRepository extends ServiceEntityRepository
{
    const ALIAS = 'af';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionFile::class);
    }
}
