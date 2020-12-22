<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    const ALIAS = 'ca';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                ActionRepository::ALIAS
            )
            ->innerJoin(self::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllFillComboboxForThematique(string $thematiqueId, string $isEnable = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, '.self::ALIAS.'.name, '.self::ALIAS.'.ref')
            ->join(self::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->andWhere(ThematiqueRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $thematiqueId);

        if ('all' != $isEnable) {
            $builder = $builder
                ->andWhere(self::ALIAS.'.isEnable = :val2')
                ->andWhere(ThematiqueRepository::ALIAS.'.isEnable = :val2')
                ->setParameter('val2', $isEnable);
        }

        $builder = $builder
            ->orderBy(self::ALIAS.'.ref', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Category::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'category';
        $table_distante = 'action';

        $alias_distante = ActionRepository::ALIAS;

        $sql = ' UPDATE '.$table_source.' '.self::ALIAS
            .' INNER JOIN ( '
            .' SELECT '.$table_source.'_id, FLOOR(AVG(taux1)) AS taux1, FLOOR(AVG(taux2)) AS taux2 '
            .' FROM '.$table_distante.' WHERE state_current in ( \'started\',\'cotech\',\'codir\',\'finalised\',\'deployed\',\'measured\',\'clotured\')'
            . ' GROUP BY '.$table_source.'_id ) '.$alias_distante.' '
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
