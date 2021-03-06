<?php

namespace App\Repository;

use App\Entity\IndicatorValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndicatorValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndicatorValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndicatorValue[]    findAll()
 * @method IndicatorValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicatorValueRepository extends ServiceEntityRepository
{
    const ALIAS = 'iv';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndicatorValue::class);
    }

    public function initialiseTaux(string $indicatorId, bool $taux1, $taux)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(IndicatorValue::class, self::ALIAS)
            ->set(self::ALIAS.($taux1 ? '.taux1 ' : '.taux2 '), $taux)
            ->where(self::ALIAS.'.indicator = :id')
            ->setParameter('id', $indicatorId);
        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function propagationWeight()
    {
        $table_source = 'indicator_value';
        $table_distante = 'indicator';

        $alias_distante = IndicatorRepository::ALIAS;

        $sql = ' UPDATE ' . $table_source . ' ' . self::ALIAS
            . ' INNER JOIN ' . $table_distante . ' ' . $alias_distante
            . ' ON ' . $alias_distante . '.id=' . self::ALIAS . '.' . $table_distante . '_id '
            . ' SET ' . self::ALIAS . '.weight=' . $alias_distante . '.weight '
            . ' ; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (\Exception $e) {
            return 'Error' . $e->getMessage();
        }
    }
}
