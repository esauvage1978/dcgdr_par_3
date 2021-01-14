<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\Deployement;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
/**
 * @method Deployement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deployement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deployement[]    findAll()
 * @method Deployement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeployementRepository extends ServiceEntityRepository
{
    const ALIAS = 'd';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry,Deployement::class);
    }

    public function findAllForAction(string $actionId)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS,
                ActionRepository::ALIAS)
            ->join(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->join(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->andWhere(ActionRepository::ALIAS . '.id = :val1')
            ->setParameter('val1', $actionId);

        $builder = $builder
            ->orderBy(OrganismeRepository::ALIAS . '.name', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Deployement::class, self::ALIAS)
            ->set(self::ALIAS . '.taux1 ', 0)
            ->set(self::ALIAS . '.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'deployement';
        $table_distante = 'indicator_value';
        $table_distante2 = 'indicator';

        $alias_distante = IndicatorValueRepository::ALIAS;
        $alias_distante2 = IndicatorRepository::ALIAS;

        $sql = ' UPDATE ' . $table_source . ' ' . self::ALIAS
            . ' INNER JOIN ( '
            . ' SELECT ' . $table_source . '_id, FLOOR(SUM(' . $alias_distante . '.taux1 * ' . $alias_distante . '.weight )/SUM(' . $alias_distante . '.weight )) AS taux1,'
            . ' FLOOR(SUM(' . $alias_distante . '.taux2 * ' . $alias_distante . '.weight )/SUM(' . $alias_distante . '.weight )) AS taux2, ' 
            . $alias_distante . '.is_enable '
            . ' FROM ' . $table_distante . '  ' . $alias_distante 
            . ' INNER JOIN ' . $table_distante2 . ' ' . $alias_distante2 . ' ON ' . $alias_distante2 . '.id=' . $alias_distante . '.indicator_id '
            . ' WHERE ' . $alias_distante . '.is_enable=true ' 
            . ' AND ' . $alias_distante2 . '.is_for_calcul=true '
            . ' AND ' . $alias_distante2 . '.is_enable=true GROUP BY ' . $table_source . '_id ) ' . $alias_distante . ' '
            . ' ON ' . self::ALIAS . '.id=' . $alias_distante . '.' . $table_source . '_id '
            . ' SET ' . self::ALIAS . '.taux1=' . $alias_distante . '.taux1, '
            . self::ALIAS . '.taux2=' . $alias_distante . '.taux2 ; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (\Exception $e) {
            return 'Error' . $e->getMessage();
        }
    }

}
