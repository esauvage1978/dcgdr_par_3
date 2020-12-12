<?php

namespace App\Repository;

use App\Entity\Corbeille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Corbeille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Corbeille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Corbeille[]    findAll()
 * @method Corbeille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorbeilleRepository extends ServiceEntityRepository
{
    const ALIAS = 'c';

    const ALIAS_ACTION_READERS = 'car';
    const ALIAS_ACTION_WRITERS = 'caw';
    const ALIAS_ACTION_VALIDERS = 'cav';
    const ALIAS_DEPLOYEMENT_WRITERS = 'cdw';
    const ALIAS_DEPLOYEMENT_READERS = 'cdr';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corbeille::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                UserRepository::ALIAS,
                OrganismeRepository::ALIAS,
                self::ALIAS_ACTION_READERS,
                self::ALIAS_ACTION_WRITERS,
                self::ALIAS_ACTION_VALIDERS,
                self::ALIAS_DEPLOYEMENT_WRITERS,
            self::ALIAS_DEPLOYEMENT_READERS
            )
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.users', UserRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.actionReaders', self::ALIAS_ACTION_READERS)
            ->leftJoin(self::ALIAS . '.actionWriters', self::ALIAS_ACTION_WRITERS)
            ->leftJoin(self::ALIAS . '.actionValiders', self::ALIAS_ACTION_VALIDERS)
            ->leftJoin(self::ALIAS . '.deployementWriters', self::ALIAS_DEPLOYEMENT_WRITERS)
            ->leftJoin(self::ALIAS . '.deployementReaders', self::ALIAS_DEPLOYEMENT_READERS)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllForUser(string $userId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.users', UserRepository::ALIAS)
            ->where(UserRepository::ALIAS . '.id = :user')
            ->setParameter('user', $userId)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
