<?php


namespace App\Repository;


use DateTime;
use App\Dto\DeployementDto;
use App\Entity\Deployement;
use App\Dto\DtoInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DeployementDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var DeployementDto
     */
    private $dto;

    const ALIAS = 'd';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deployement::class);
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
                ActionRepository::ALIAS,
                OrganismeRepository::ALIAS,

                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
            )
            ->innerJoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->innerJoin(ActionRepository::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->innerJoin(self::ALIAS . '.axe', ActionRepository::ALIAS);
    }

    private function initialise_selectCombobox()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.id, ' . self::ALIAS . '.name')
            ->innerJoin(self::ALIAS . '.axe', ActionRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_enable();

        $this->initialise_where_action_id();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }


    private function initialise_where_enable()
    {
        $dto = $this->dto;
        if ($this->dto->getVisible() === DeployementDto::TRUE) {
            $this->builder
                ->andWhere(self::ALIAS . '.isEnable=true')
                ->andWhere(OrganismeRepository::ALIAS . '.isEnable=true')
                ->andWhere(AxeRepository::ALIAS . '.isArchiving=false')
                ->andWhere(AxeRepository::ALIAS . '.isEnable=true')
                ->andWhere(PoleRepository::ALIAS . '.isEnable=true')
                ->andWhere(ThematicRepository::ALIAS . '.isEnable=true')
                ->andWhere(CategoryRepository::ALIAS . '.isEnable=true');
        }
    }

    private function initialise_where_action_id()
    {
        if (!empty($this->dto->getActionDto()->getId())) {
            $this->builder->andwhere(ActionRepository::ALIAS . '.id = :id');
            $this->addParams('id', $this->dto->getActionDto()->getId());
        }
    }


    private function initialise_orderBy()
    {
        $this->builder
            ->addOrderBy(OrganismeRepository::ALIAS . '.ref', 'ASC')
            ->addOrderBy(OrganismeRepository::ALIAS . '.name', 'ASC');
    }
}
