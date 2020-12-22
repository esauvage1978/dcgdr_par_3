<?php

namespace App\Repository;

use App\Entity\Pole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Pole|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pole|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pole[]    findAll()
 * @method Pole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoleRepository extends ServiceEntityRepository
{
    const ALIAS='p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pole::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                AxeRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                CategoryRepository::ALIAS,
                ActionRepository::ALIAS
            )
            ->leftJoin(self::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.thematiques', ThematiqueRepository::ALIAS)
            ->leftJoin(ThematiqueRepository::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->leftJoin(CategoryRepository::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllFillComboboxForAxe(string $axeId, string $isEnable = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, ' .self::ALIAS . '.name')
            ->join(self::ALIAS.'.axe', AxeRepository::ALIAS)
            ->andWhere(AxeRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $axeId);

        if ('all' != $isEnable) {
            $builder = $builder
                ->andWhere(AxeRepository::ALIAS.'.isEnable = :val2')
                ->andWhere(self::ALIAS.'.isEnable = :val2')
                ->setParameter('val2', $isEnable);
        }

        $builder = $builder
            ->orderBy(self::ALIAS.'.name', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }


    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Pole::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='pole';
        $table_distante='thematique';

        $alias_distante=ThematiqueRepository::ALIAS;

        $sql = ' UPDATE '.$table_source.' '.self::ALIAS
            .' INNER JOIN ( '
            .' SELECT '.$table_source. '_id, FLOOR(AVG(taux1)) AS taux1, FLOOR(AVG(taux2)) AS taux2, is_enable '
            .' FROM '.$table_distante. ' WHERE is_enable=true group by '.$table_source.'_id ) '.$alias_distante.' '
            .' ON '.self::ALIAS.'.id='.$alias_distante.'.'.$table_source.'_id '
            .' SET '.self::ALIAS.'.taux1='.$alias_distante.'.taux1, '
            .self::ALIAS.'.taux2='.$alias_distante.'.taux2 '
            .' WHERE '.self::ALIAS. '.is_enable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (\Exception $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
