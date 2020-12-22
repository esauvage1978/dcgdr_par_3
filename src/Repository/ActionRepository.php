<?php

namespace App\Repository;

use App\Entity\Action;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    const ALIAS = 'a';
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
    }

    public function findAllActionsforCategoryForViewSmallCard(string $categoryId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.ref, '.self::ALIAS.'.name')
            ->where(self::ALIAS.'.category = :cat')
            ->setParameter('cat', $categoryId)
            ->orderBy(self::ALIAS.'.ref', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Action::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'action';
        $table_distante = 'indicator';

        $alias_distante = IndicatorRepository::ALIAS;

        $sql = ' UPDATE '.$table_source.' '.self::ALIAS
            .' INNER JOIN ( '
            .' SELECT '.$table_source.'_id, FLOOR(AVG(taux1)) AS taux1, FLOOR(AVG(taux2)) AS taux2, is_enable '
            .' FROM '.$table_distante. ' WHERE is_enable=true GROUP BY '.$table_source.'_id ) '.$alias_distante.' '
            .' ON '.self::ALIAS.'.id='.$alias_distante.'.'.$table_source.'_id '
            .' SET '.self::ALIAS.'.taux1='.$alias_distante.'.taux1, '
            .self::ALIAS.'.taux2='.$alias_distante.'.taux2 '
            .' WHERE '.self::ALIAS.'.state_current IN ( \'started\',\'cotech\',\'codir\',\'finalised\',\'deployed\',\'measured\',\'clotured\')';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (\Exception $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
