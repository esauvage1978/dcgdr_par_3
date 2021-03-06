<?php

namespace App\Repository;

use App\Entity\Pole;
use App\Entity\Thematique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Thematique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematique[]    findAll()
 * @method Thematique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematiqueRepository extends ServiceEntityRepository
{
    const ALIAS = 't';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematique::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                CategoryRepository::ALIAS,
                ActionRepository::ALIAS
            )
            ->leftJoin(self::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->leftJoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftJoin(CategoryRepository::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllFillComboboxForPole(string $poleId, string $isEnable = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, '.self::ALIAS.'.name, '.self::ALIAS .'.ref')
            ->join(self::ALIAS.'.pole', PoleRepository::ALIAS)
            ->andWhere(PoleRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $poleId);

        if ('all' != $isEnable) {
            $builder = $builder
                ->andWhere(self::ALIAS.'.isEnable = :val2')
                ->andWhere(PoleRepository::ALIAS.'.isEnable = :val2')
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
        $queryBuilder->update(Thematique::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='thematique';
        $table_distante='category';

        $alias_distante=CategoryRepository::ALIAS;

        $sql = ' UPDATE '.$table_source.' '.self::ALIAS
            .' INNER JOIN ( '
            .' SELECT '.$table_source. '_id, FLOOR(AVG(taux1)) AS taux1, FLOOR(AVG(taux2)) AS taux2, is_enable '
            .' FROM '.$table_distante. ' WHERE is_enable=true GROUP BY '.$table_source.'_id ) '.$alias_distante.' '
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
