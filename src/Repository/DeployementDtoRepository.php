<?php


namespace App\Repository;


use DateTime;
use App\Dto\DtoInterface;
use App\Dto\DeployementDto;
use App\Entity\Deployement;
use App\Helper\ParamsInServices;
use App\Workflow\WorkflowData;
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

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    const ALIAS = 'd';

    public function __construct(ManagerRegistry $registry, ParamsInServices $paramsInServices)
    {
        parent::__construct($registry, Deployement::class);
        $this->paramsInServices = $paramsInServices;
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

                IndicatorValueRepository::ALIAS,
                IndicatorRepository::ALIAS,
            )
            ->innerJoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.indicatorValues', IndicatorValueRepository::ALIAS)
            ->leftJoin(IndicatorValueRepository::ALIAS . '.indicator', IndicatorRepository::ALIAS)
            ->innerJoin(ActionRepository::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->innerJoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->innerJoin(ActionRepository::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_selectCombobox()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.id, ' . self::ALIAS . '.name')
            ->innerJoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_enable();

        $this->initialise_where_action_id();

        $this->initialise_where_is_updatable();

        $this->initialise_where_is_corbeille();

        $this->initialise_where_has_corbeille();

        $this->initialise_where_is_terminated();

        $this->initialise_where_is_role();

        $this->initialise_where_has_jalon();
        $this->initialise_where_has_jalon_to_late();
        $this->initialise_where_has_jalon_to_near();
        $this->initialise_where_has_jalon_come_up();

        $this->initialise_where_states();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }

    private function initialise_where_states()
    {
        if (!empty($this->dto->getActionDto()) && !empty($this->dto->getActionDto()->getStates())) {
            $states = implode('\',\'', $this->dto->getActionDto()->getStates());
            $this->builder->andwhere(ActionRepository::ALIAS . '.stateCurrent in (\'' . $states . '\')');
        }
    }

    private function initialise_where_is_terminated()
    {
        $dto = $this->dto;
        $builder = $this->builder;

        if ($dto->getIsTerminated() === DeployementDto::FALSE) {
            $builder->andWhere(self::ALIAS . '.endAt is null ');
        } elseif ($dto->getIsTerminated() === DeployementDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.endAt is not null ');
        }
    }

    private function initialise_where_has_jalon()
    {
        $dto = $this->dto;
        $builder = $this->builder;

        if ($dto->getHasJalon() === DeployementDto::FALSE) {
            $builder->andWhere(self::ALIAS . '.showAt is null ');
        } elseif ($dto->getHasJalon() === DeployementDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt is not null ');
        }
    }
    private function initialise_where_has_jalon_to_near()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        $date1 =  date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d') . ' +' . $this->paramsInServices->get(ParamsInServices::ES_JALON_TO_NEAR) . ' day'));
        $date2 =  (new \DateTime())->format('Y-m-d');

        if ($dto->getHasJalonToNear() === DeployementDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt BETWEEN  :from AND :to');
            $this->addParams('from', $date2);
            $this->addParams('to', $date1);
        }
    }
    private function initialise_where_has_jalon_come_up()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        $date1 =  date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d') . ' +' . $this->paramsInServices->get(ParamsInServices::ES_JALON_TO_NEAR) . ' day'));

        if ($dto->getHasJalonComeUp() === DeployementDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt > :to');
            $this->addParams('to', $date1);
        }
    }
    private function initialise_where_has_jalon_to_late()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        $date =  (new \DateTime())->format('Y-m-d');
        if ($dto->getHasJalonToLate() === DeployementDto::FALSE) {
            $builder->andWhere(self::ALIAS . '.showAt is null ');
        } elseif ($dto->getHasJalonToLate() === DeployementDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt < :from ');
            $this->addParams('from', $date);
        }
    }

    private function initialise_where_has_corbeille()
    {
        if ($this->dto->getHasWriters() == DeployementDto::FALSE) {
            $qWu = $this->createQueryBuilder('id')
                ->innerJoin('id.writers', 'cordw');
            $this->builder->andwhere(self::ALIAS . '.id NOT IN (' . $qWu->getDQL() . ')');
        } else if ($this->dto->getHasWriters() == DeployementDto::TRUE) {
            $qWu = $this->createQueryBuilder('id')
                ->innerJoin('id.writers', 'cordw');
            $this->builder->andwhere(self::ALIAS . '.id IN (' . $qWu->getDQL() . ')');
        }
    }

    private function initialise_where_is_corbeille()
    {
        if ($this->dto->getIsWriter() === DeployementDto::TRUE) {
            $u = $this->dto->getUserDto();

            if (!empty($u) && !empty($u->getId())) {

                $rqtPilote = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS . '1')
                    ->join(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS . '1.users', UserRepository::ALIAS . '1')
                    ->where(UserRepository::ALIAS . '1.id= :idUser');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(self::ALIAS . '.id IN (' . $rqtPilote->getDQL() . ')');
            }
        }
    }

    private function initialise_where_is_updatable()
    {
        if ($this->dto->getIsWritable() === DeployementDto::TRUE) {
            $u = $this->dto->getUserDto();
            if (!empty($u) && !empty($u->getId())) {
                $states = implode('\',\'', WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $rqtPilote = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS . '1')
                    ->join(CorbeilleRepository::ALIAS_ACTION_WRITERS . '1.users', UserRepository::ALIAS . '1')
                    ->where(UserRepository::ALIAS . '1.id= :idUser')
                    ->andwhere(ActionRepository::ALIAS . '.stateCurrent in (\'' . $states . '\')');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(
                        '( ' .
                            self::ALIAS . '.id IN (' . $rqtPilote->getDQL() . '))'
                    );
            }
        }
    }


    private function initialise_where_is_role()
    {
        if ($this->dto->getIsGestionnaireLocal() === DeployementDto::TRUE) {
            $u = $this->dto->getUserDto();
            if (!empty($u) && !empty($u->getId())) {
                $states = implode('\',\'', WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $rqtPilote = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS . '1')
                    ->join(CorbeilleRepository::ALIAS_ACTION_WRITERS . '1.users', UserRepository::ALIAS . '1')
                    ->where(UserRepository::ALIAS . '1.id= :idUser')
                    ->andwhere(ActionRepository::ALIAS . '.stateCurrent in (\'' . $states . '\')');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(
                        '( ' .
                            self::ALIAS . '.id IN (' . $rqtPilote->getDQL() . '))'
                    );
            }
        }
    }

    private function initialise_where_enable()
    {
        $dto = $this->dto;
        if ($this->dto->getVisible() === DeployementDto::TRUE) {
            $this->builder
                ->andWhere(OrganismeRepository::ALIAS . '.isEnable=true')
                ->andWhere(AxeRepository::ALIAS . '.isArchiving=false')
                ->andWhere(AxeRepository::ALIAS . '.isEnable=true')
                ->andWhere(PoleRepository::ALIAS . '.isEnable=true')
                ->andWhere(ThematiqueRepository::ALIAS . '.isEnable=true')
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
