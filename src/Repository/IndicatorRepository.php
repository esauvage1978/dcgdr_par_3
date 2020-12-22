<?php

namespace App\Repository;

use App\Entity\Indicator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Indicator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indicator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indicator[]    findAll()
 * @method Indicator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicatorRepository extends ServiceEntityRepository
{
    const ALIAS = 'i';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indicator::class);
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Indicator::class, self::ALIAS)
            ->set(self::ALIAS . '.taux1 ', 0)
            ->set(self::ALIAS . '.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'indicator';
        $table_distante = 'indicator_value';

        $alias_distante = IndicatorValueRepository::ALIAS;

        $sql = ' UPDATE ' . $table_source . ' ' . self::ALIAS
            . ' INNER JOIN ( '
            . ' SELECT ' . $table_source . '_id, FLOOR(AVG(taux1)) AS taux1, FLOOR(AVG(taux2)) AS taux2, is_enable '
            . ' FROM ' . $table_distante . ' WHERE is_enable=true GROUP BY ' . $table_source . '_id ) ' . $alias_distante . ' '
            . ' ON ' . self::ALIAS . '.id=' . $alias_distante . '.' . $table_source . '_id '
            . ' SET ' . self::ALIAS . '.taux1=' . $alias_distante . '.taux1, '
            . self::ALIAS . '.taux2=' . $alias_distante . '.taux2  '
            . ' WHERE ' . self::ALIAS . '.is_enable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (\Exception $e) {
            return 'Error' . $e->getMessage();
        }
    }

    public function findAllIndicatorContributif()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ActionRepository::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS
            )
            ->leftjoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->leftjoin(ActionRepository::ALIAS . '.category', CategoryRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS)
            ->where(AxeRepository::ALIAS . '.isEnable=true')
            ->where(AxeRepository::ALIAS . '.isArchiving=false')
            ->andwhere(PoleRepository::ALIAS . '.isEnable=true')
            ->andwhere(ThematiqueRepository::ALIAS . '.isEnable=true')
            ->andwhere(CategoryRepository::ALIAS . '.isEnable=true')
            ->andwhere(self::ALIAS . '.isEnable=true')
            ->andwhere(self::ALIAS . '.indicatorType=\'contributif\'')
            ->orderBy(AxeRepository::ALIAS . '.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS . '.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS . '.name', 'ASC')
            ->orderBy(CategoryRepository::ALIAS . '.name', 'ASC')
            ->orderBy(ActionRepository::ALIAS . '.name', 'ASC')
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
