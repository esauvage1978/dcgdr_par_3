<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public const ALIAS = 'u';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                OrganismeRepository::ALIAS,
                CorbeilleRepository::ALIAS,
                UserParamRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.userParam', UserParamRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.organismes', OrganismeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

        /**
     * @return User[] Returns an array of User objects
     */
    public function findAllForContactAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->Where(self::ALIAS.'.roles like :val1')
            ->setParameter('val1', '%ROLE_ADMIN%')
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
  
}
