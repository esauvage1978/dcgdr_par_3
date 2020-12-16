<?php


namespace App\Repository;


use DateTime;
use App\Dto\CorbeilleDto;
use App\Entity\Corbeille;
use App\Dto\DtoInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CorbeilleDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var CorbeilleDto
     */
    private $dto;

    const ALIAS = 'c';


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corbeille::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_selectCount();

        $this->initialise_where();

        $this->initialise_orderBy();



        return $this->builder
            ->getQuery()->getSingleScalarResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_select();

        $this->initialise_where();

        $this->initialise_orderBy();

        if (empty($page)) {
            $this->builder
                ->getQuery()
                ->getResult();
        } else {
            $this->builder
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
        }

        return new Paginator($this->builder);
    }
    public function findForCombobox(DtoInterface $dto)
    {
        $this->dto = $dto;

        $this->initialise_selectCombobox();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    public function findAllForDto(DtoInterface $dto, $filtre = '')
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_select();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                OrganismeRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS);
    }

    private function initialise_selectCombobox()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.id, ' . self::ALIAS . '.name')
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_enable();

        $this->initialise_where_organisme_id();

        $this->initialise_where_is_use_by_default();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }


    private function initialise_where_enable()
    {
        $dto = $this->dto;
        if ($this->dto->getVisible() === CorbeilleDto::TRUE) {
            $this->builder
                ->andWhere(self::ALIAS . '.isEnable=true')
                ->andWhere(OrganismeRepository::ALIAS . '.isEnable=true');
        }
    }

    private function initialise_where_is_use_by_default()
    {
        $dto = $this->dto;
        if ($this->dto->getIsUseByDefault() === CorbeilleDto::TRUE) {
            $this->builder
                ->andWhere(self::ALIAS . '.isUseByDefault=true');
        }
    }

    private function initialise_where_organisme_id()
    {
        if (!empty($this->dto->getOrganismeDto()->getId())) {
            $this->builder->andwhere(OrganismeRepository::ALIAS . '.id = :id');
            $this->addParams('id', $this->dto->getOrganismeDto()->getId());
        }
    }


    private function initialise_orderBy()
    {
        $this->builder
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }
}
