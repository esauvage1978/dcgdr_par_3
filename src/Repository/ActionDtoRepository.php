<?php


namespace App\Repository;


use DateTime;
use App\Dto\ActionDto;
use App\Dto\RubricDto;
use App\Entity\Action;
use App\Dto\ProcessDto;
use App\Dto\MProcessDto;
use App\Entity\MProcess;
use App\Dto\DtoInterface;
use App\Entity\ActionLink;
use App\Workflow\WorkflowData;
use App\Helper\ParamsInServices;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\MakerBundle\Validator;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ActionDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var ActionDto
     */
    private $dto;


    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    const ALIAS = 'b';

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_TREE = 'tree';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    public function __construct(ManagerRegistry$registry, ParamsInServices $paramsInServices)
    {
        parent::__construct($registry, Action::class);
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

    public function findAllForDto(DtoInterface $dto, string $filtre = self::FILTRE_DTO_INIT_HOME)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch ($filtre) {
            case self::FILTRE_DTO_INIT_TREE:
                $this->initialise_select_tree();
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_select();
                break;
            case self::FILTRE_DTO_INIT_HOME:
                $this->initialise_select_home();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_select_search();
                break;
        }

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_select_home()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_select_tree()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                UserRepository::ALIAS,
                ActionFileRepository::ALIAS,
                ActionLinkRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.actionFiles', ActionFileRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.actionLinks', ActionLinkRepository::ALIAS);
    }

    private function initialise_select_search()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                ActionFileRepository::ALIAS,
                ActionLinkRepository::ALIAS,
                DeployementRepository::ALIAS,
                IndicatorRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.actionFiles', ActionFileRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.actionLinks', ActionLinkRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.deployements', DeployementRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.indicators', IndicatorRepository::ALIAS);;

    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                UserRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];


        $this->builder
            ->where(self::ALIAS . '.id>0');


        $this->initialise_where_enable();

        $this->initialise_where_state();

        $this->initialise_where_axe();
        $this->initialise_where_pole();
        $this->initialise_where_thematique();
        $this->initialise_where_category();

        $this->initialise_where_is_updatable();
        $this->initialise_where_is_readable();

        $this->initialise_where_is_corbeille();

        $this->initialise_where_has_corbeille();

        $this->initialise_where_states();

        $this->initialise_where_search();

        $this->initialise_where_has_jalon();
        $this->initialise_where_has_jalon_to_late();
        $this->initialise_where_has_jalon_to_near();
        $this->initialise_where_has_jalon_come_up();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }

    private function initialise_where_has_jalon()
    {
        $dto = $this->dto;
        $builder = $this->builder;

        if ($dto->getHasJalon() === ActionDto::FALSE) {
            $builder->andWhere(self::ALIAS . '.showAt is null ');
        } elseif ($dto->getHasJalon() === ActionDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt is not null ');
        }
    }

    private function initialise_where_has_jalon_to_late()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        $date =  (new \DateTime())->format('Y-m-d');
        if ($dto->getHasJalonToLate() === ActionDto::FALSE) {
            $builder->andWhere(self::ALIAS . '.showAt is null ');
        } elseif ($dto->getHasJalonToLate() === ActionDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt < :from ');
            $this->addParams('from', $date);
        }
    }

    private function initialise_where_has_jalon_to_near()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        $date1 =  date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d') . ' +' . $this->paramsInServices->get(ParamsInServices::ES_JALON_TO_NEAR) . ' day'));
        $date2 =  (new \DateTime())->format('Y-m-d');

        if ($dto->getHasJalonToNear() === ActionDto::TRUE) {
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

        if ($dto->getHasJalonComeUp() === ActionDto::TRUE) {
            $builder->andWhere(self::ALIAS . '.showAt > :to');
            $this->addParams('to', $date1);
        }
    }

    private function initialise_where_has_corbeille()
    {
        if ($this->dto->getHasWriters() == ActionDto::FALSE) {
            $qWu = $this->createQueryBuilder('id')
                ->innerJoin('id.writers', 'cordw');
            $this->builder->andwhere(self::ALIAS . '.id NOT IN (' . $qWu->getDQL() . ')');
        } else if ($this->dto->getHasWriters() == ActionDto::TRUE) {
            $qWu = $this->createQueryBuilder('id')
                ->innerJoin('id.writers', 'cordw');
            $this->builder->andwhere(self::ALIAS . '.id IN (' . $qWu->getDQL() . ')');
        }

        if ($this->dto->getHasValidersCOTECH() == ActionDto::FALSE) {
            $qVC1 = $this->createQueryBuilder('id')
                ->innerJoin('id.COTECHValiders', 'cordvc1');
            $this->builder->andwhere(self::ALIAS . '.id NOT IN (' . $qVC1->getDQL() . ')');
        } else if ($this->dto->getHasValidersCOTECH() == ActionDto::TRUE) {
            $qVC1 = $this->createQueryBuilder('id')
                ->innerJoin('id.COTECHValiders', 'cordvc1');
            $this->builder->andwhere(self::ALIAS . '.id  IN (' . $qVC1->getDQL() . ')');
        }

        if ($this->dto->getHasValidersCODIR() == ActionDto::FALSE) {
            $qVC2 = $this->createQueryBuilder('id')
                ->innerJoin('id.CODIRValiders', 'cordvc2');
            $this->builder->andwhere(self::ALIAS . '.id NOT IN (' . $qVC2->getDQL() . ')');
        } else if ($this->dto->getHasValidersCODIR() == ActionDto::TRUE) {
            $qVC2 = $this->createQueryBuilder('id')
                ->innerJoin('id.CODIRValiders', 'cordvc2');
            $this->builder->andwhere(self::ALIAS . '.id  IN (' . $qVC2->getDQL() . ')');
        }
    }
    private function initialise_where_states()
    {
        if (!empty($this->dto->getStates())) {
            $states = implode('\',\'', $this->dto->getStates());
            $this->builder->andwhere(self::ALIAS . '.stateCurrent in (\'' . $states . '\')');
        }
    }

    private function initialise_where_is_corbeille()
    {
        if ($this->dto->getIsWriter() === ActionDto::TRUE) {
            $u = $this->dto->getUserDto();

            if (!empty($u) && !empty($u->getId())) {

                $rqtPilote = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS . '1')
                    ->join(CorbeilleRepository::ALIAS_ACTION_WRITERS . '1.users', UserRepository::ALIAS . '1')
                    ->where(UserRepository::ALIAS . '1.id= :idUser');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(self::ALIAS . '.id IN (' . $rqtPilote->getDQL() . ')');
            }
        }
        if ($this->dto->getIsValidersCOTECH() === ActionDto::TRUE) {
            $u = $this->dto->getUserDto();

            if (!empty($u) && !empty($u->getId())) {

                $rqtVC1 = $this->createQueryBuilder(self::ALIAS . '2')
                    ->select(self::ALIAS . '2.id')
                    ->join(self::ALIAS . '2.COTECHValiders', CorbeilleRepository::ALIAS_ACTION_COTECH . '2')
                    ->join(CorbeilleRepository::ALIAS_ACTION_COTECH . '2.users', UserRepository::ALIAS . '2')
                    ->where(UserRepository::ALIAS . '2.id= :idUser');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(self::ALIAS . '.id IN (' . $rqtVC1->getDQL() . ')');
            }
        }
        if ($this->dto->getIsValidersCODIR() === ActionDto::TRUE) {
            $u = $this->dto->getUserDto();

            if (!empty($u) && !empty($u->getId())) {

                $rqtVC2 = $this->createQueryBuilder(self::ALIAS . '3')
                    ->select(self::ALIAS . '3.id')
                    ->join(self::ALIAS . '3.CODIRValiders', CorbeilleRepository::ALIAS_ACTION_CODIR . '3')
                    ->join(CorbeilleRepository::ALIAS_ACTION_CODIR . '3.users', UserRepository::ALIAS . '3')
                    ->where(UserRepository::ALIAS . '3.id= :idUser');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(self::ALIAS . '.id IN (' . $rqtVC2->getDQL() . ')');
            }
        }
    }
 
    private function initialise_where_is_readable()
    {
        if ($this->dto->getIsReadable() === ActionDto::TRUE) {
            $u = $this->dto->getUserDto();

            if (!empty($u) && !empty($u->getId())) {
                $rqtReader = $this->createQueryBuilder(self::ALIAS . '4')
                    ->select(self::ALIAS . '4.id')
                    ->join(self::ALIAS . '4.readers', CorbeilleRepository::ALIAS_ACTION_READERS . '4')
                    ->join(CorbeilleRepository::ALIAS_ACTION_READERS . '4.users', UserRepository::ALIAS . '4')
                    ->where(UserRepository::ALIAS . '4.id= :idUser');

                $rqtPilote = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS . '1')
                    ->join(CorbeilleRepository::ALIAS_ACTION_WRITERS . '1.users', UserRepository::ALIAS . '1')
                    ->where(UserRepository::ALIAS . '1.id= :idUser');

                $rqtCotech = $this->createQueryBuilder(self::ALIAS . '2')
                    ->select(self::ALIAS . '2.id')
                    ->join(self::ALIAS . '2.COTECHValiders', CorbeilleRepository::ALIAS_ACTION_COTECH . '2')
                    ->join(CorbeilleRepository::ALIAS_ACTION_COTECH . '2.users', UserRepository::ALIAS . '2')
                    ->where(UserRepository::ALIAS . '2.id= :idUser');

                $rqtCodir = $this->createQueryBuilder(self::ALIAS . '3')
                    ->select(self::ALIAS . '3.id')
                    ->join(self::ALIAS . '3.CODIRValiders', CorbeilleRepository::ALIAS_ACTION_CODIR . '3')
                    ->join(CorbeilleRepository::ALIAS_ACTION_CODIR . '3.users', UserRepository::ALIAS . '3')
                    ->where(UserRepository::ALIAS . '3.id= :idUser');

                $this->addParams('idUser', $u->getId());


                $this->builder
                    ->andWhere(
                        '( '
                            . self::ALIAS . '.isShowAll=1 OR ' .
                            self::ALIAS . '.id IN (' . $rqtReader->getDQL() . ') OR ' .
                            self::ALIAS . '.id IN (' . $rqtPilote->getDQL() . ') OR ' .
                            self::ALIAS . '.id IN (' . $rqtCotech->getDQL() . ') OR ' .
                            self::ALIAS . '.id IN (' . $rqtCodir->getDQL() . '))'
                    );
            }
        }
    }
    private function initialise_where_is_updatable()
    {
        if ($this->dto->getIsWritable() === ActionDto::TRUE) {
            $u = $this->dto->getUserDto();
            if (!empty($u) && !empty($u->getId())) {
                $states = implode('\',\'', WorkflowData::STATES_ACTION_UPDATE_BY_PILOTES);
                $rqtPilote = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS . '1')
                    ->join(CorbeilleRepository::ALIAS_ACTION_WRITERS . '1.users', UserRepository::ALIAS . '1')
                    ->where(UserRepository::ALIAS . '1.id= :idUser')
                    ->andwhere(self::ALIAS . '.stateCurrent in (\'' . $states . '\')');

                $states = implode('\',\'', WorkflowData::STATES_ACTION_UPDATE_BY_COTECH);
                $rqtCotech = $this->createQueryBuilder(self::ALIAS . '2')
                    ->select(self::ALIAS . '2.id')
                    ->join(self::ALIAS . '2.COTECHValiders', CorbeilleRepository::ALIAS_ACTION_COTECH . '2')
                    ->join(CorbeilleRepository::ALIAS_ACTION_COTECH . '2.users', UserRepository::ALIAS . '2')
                    ->where(UserRepository::ALIAS . '2.id= :idUser')
                    ->andwhere(self::ALIAS . '.stateCurrent in (\'' . $states . '\')');

                $states = implode('\',\'', WorkflowData::STATES_ACTION_UPDATE_BY_CODIR);
                $rqtCodir = $this->createQueryBuilder(self::ALIAS . '3')
                    ->select(self::ALIAS . '3.id')
                    ->join(self::ALIAS . '3.CODIRValiders', CorbeilleRepository::ALIAS_ACTION_CODIR . '3')
                    ->join(CorbeilleRepository::ALIAS_ACTION_CODIR . '3.users', UserRepository::ALIAS . '3')
                    ->where(UserRepository::ALIAS . '3.id= :idUser')
                    ->andwhere(self::ALIAS . '.stateCurrent in (\'' . $states . '\')');

                $this->addParams('idUser', $u->getId());


                $this->builder
                    ->andWhere(
                        '( ' .
                            self::ALIAS . '.id IN (' . $rqtPilote->getDQL() . ') OR ' .
                            self::ALIAS . '.id IN (' . $rqtCotech->getDQL() . ') OR ' .
                            self::ALIAS . '.id IN (' . $rqtCodir->getDQL() . '))'
                    );
            }
        }
    }

    private function initialise_where_state()
    {
        if (!empty($this->dto->getStateCurrent())) {
            $this->builder->andwhere(self::ALIAS . '.stateCurrent = :state');
            $this->addParams('state', $this->dto->getStateCurrent());
        }
    }

    private function initialise_where_axe()
    {
        if (!empty($this->dto->getAxeDto()) && !empty($this->dto->getAxeDto()->getId())) {
            $this->builder->andwhere(PoleRepository::ALIAS . '.axe = :axeId');
            $this->addParams('axeId', $this->dto->getAxeDto()->getId());
        }
    }
    private function initialise_where_pole()
    {
        if (!empty($this->dto->getPoleDto()) && !empty($this->dto->getPoleDto()->getId())) {
            $this->builder->andwhere(ThematiqueRepository::ALIAS . '.pole = :poleId');
            $this->addParams('poleId', $this->dto->getPoleDto()->getId());
        }
    }
    private function initialise_where_thematique()
    {
        if (!empty($this->dto->getThematiqueDto()) && !empty($this->dto->getThematiqueDto()->getId())) {
            $this->builder->andwhere(CategoryRepository::ALIAS . '.thematique = :thematiqueId');
            $this->addParams('thematiqueId', $this->dto->getThematiqueDto()->getId());
        }
    }
    private function initialise_where_category()
    {
        if (!empty($this->dto->getCategoryDto()) && !empty($this->dto->getCategoryDto()->getId())) {
            $this->builder->andwhere(self::ALIAS . '.category = :categoryId');
            $this->addParams('categoryId', $this->dto->getCategoryDto()->getId());
        }
    }

    private function initialise_where_enable()
    {
        $dto = $this->dto;
        if ($this->dto->getVisible() === ActionDto::TRUE) {
            $this->builder
                ->where(AxeRepository::ALIAS . '.isEnable=true')
                ->andwhere(PoleRepository::ALIAS . '.isEnable=true')
                ->andwhere(ThematiqueRepository::ALIAS . '.isEnable=true')
                ->andwhere(CategoryRepository::ALIAS . '.isEnable=true')
                ->andwhere(AxeRepository::ALIAS . '.isArchiving=false');
        }
    }


    private function initialise_where_search()
    {
        $dto = $this->dto;

        $builder = $this->builder;
        if (!empty($dto->getRef())) {
            if ('*' != $dto->getRef()) {
                $builder->andwhere(self::ALIAS . '.ref = :actionref');
                $this->addParams('actionref', $dto->getRef());
            }
            if ('*' != $dto->getCategoryDto()->getRef()) {
                $builder->andwhere(CategoryRepository::ALIAS . '.ref = :categoryref');
                $this->addParams('categoryref', $dto->getCategoryDto()->getRef());
            }
            if ('*' != $dto->getThematiqueDto()->getRef()) {
                $builder->andwhere(ThematiqueRepository::ALIAS . '.ref = :thematiqueref');
                $this->addParams('thematiqueref', $dto->getThematiqueDto()->getRef());
            }
        }
        if (!empty($dto->getSearch())) {
            $builder
                ->andwhere(
                    self::ALIAS . '.name like :search' .
                    ' OR ' . self::ALIAS . '.content like :search' .
                    ' OR ' . self::ALIAS . '.stateContent like :search' .
                    ' OR ' . self::ALIAS . '.cadrage like :search' .
                    ' OR ' . self::ALIAS . '.measureContent like :search' .
                    ' OR ' . IndicatorRepository::ALIAS . '.name like :search' .
                    ' OR ' . IndicatorRepository::ALIAS . '.content like :search' .
                    ' OR ' . CategoryRepository::ALIAS . '.name like :search' .
                    ' OR ' . ThematiqueRepository::ALIAS . '.name like :search' .
                    ' OR ' . PoleRepository::ALIAS . '.name like :search' .
                    ' OR ' . AxeRepository::ALIAS . '.name like :search'
                );

            $this->addParams('search', '%' . $dto->getSearch() . '%');
        }

        if (!empty($dto->getSearchDate())) {
            $builder
                ->andWhere(
                    self::ALIAS . '.showAt = :search ' .
                    ' OR ' . self::ALIAS . '.stateAt = :search' .
                    ' OR ' . self::ALIAS . '.regionStartAt = :search' .
                    ' OR ' . self::ALIAS . '.regionEndAt = :search'
                );
            $this->addParams('search', $dto->getSearchDate());
        }
    }

    private function initialise_orderBy()
    {
        $this->builder
            ->addOrderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }
}
