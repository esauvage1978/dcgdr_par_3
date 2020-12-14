<?php

namespace App\Repository;

use App\Entity\Axe;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Axe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Axe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Axe[]    findAll()
 * @method Axe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AxeRepository extends ServiceEntityRepository
{
    const ALIAS = 'pa';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Axe::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                PoleRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                CategoryRepository::ALIAS,
                ActionRepository::ALIAS
            )
            ->leftJoin(self::ALIAS.'.poles', PoleRepository::ALIAS)
            ->leftJoin(PoleRepository::ALIAS.'.thematiques', ThematiqueRepository::ALIAS)
            ->leftJoin(ThematiqueRepository::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->leftJoin(CategoryRepository::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findAllForHome()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                PoleRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                CategoryRepository::ALIAS,
                ActionRepository::ALIAS
                )
            ->leftJoin(self::ALIAS.'.poles', PoleRepository::ALIAS)
            ->leftJoin(PoleRepository::ALIAS.'.thematiques', ThematiqueRepository::ALIAS)
            ->leftJoin(ThematiqueRepository::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->leftJoin(CategoryRepository::ALIAS.'.actions', ActionRepository::ALIAS)
            ->where(self::ALIAS.'.isEnable=true')
            ->andwhere(self::ALIAS.'.archiving=false')
            ->andwhere(PoleRepository::ALIAS.'.isEnable=true')
            ->andwhere(ThematiqueRepository::ALIAS.'.isEnable=true')
            ->andwhere(CategoryRepository::ALIAS.'.isEnable=true')
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllFillCombobox(string $isEnable = 'all', string $isArchiving = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, '.self::ALIAS.'.name');

        if ('all' != $isEnable) {
            $builder = $builder
                ->Where(self::ALIAS.'.isEnable = :val1')
                ->setParameter('val1', $isEnable);
        }

        if ('all' != $isArchiving) {
            $builder = $builder
                ->andWhere(self::ALIAS.'.archiving = :val2')
                ->setParameter('val2', $isArchiving);
        }

        $builder = $builder->orderBy(self::ALIAS.'.name', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }


    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Axe::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='axe';
        $table_distante='pole';

        $alias_distante=PoleRepository::ALIAS;

        $sql = ' update '.$table_source.' '.self::ALIAS
            .' inner join ( '
            .' select '.$table_source.'_id, avg(taux1) as taux1, avg(taux2) as taux2, enable '
            .' from '.$table_distante.' where enable=true group by '.$table_source.'_id ) '.$alias_distante.' '
            .' on '.self::ALIAS.'.id='.$alias_distante.'.'.$table_source.'_id '
            .' set '.self::ALIAS.'.taux1='.$alias_distante.'.taux1, '
            .self::ALIAS.'.taux2='.$alias_distante.'.taux2 '
            .' where '.self::ALIAS.'.isEnable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (Exception $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
