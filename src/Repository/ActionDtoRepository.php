<?php


namespace App\Repository;


use DateTime;
use App\Dto\RubricDto;
use App\Dto\ProcessDto;
use App\Dto\ActionDto;
use App\Dto\MProcessDto;
use App\Entity\Action;
use App\Entity\MProcess;
use App\Dto\DtoInterface;
use App\Entity\ActionLink;
use App\Workflow\WorkflowData;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\MakerBundle\Validator;

class ActionDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var ActionDto
     */
    private $dto;

    const ALIAS = 'b';

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_TREE = 'tree';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
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
                $this->initialise_select();
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
                ActionLinkRepository::ALIAS,
                UserRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.backpackFiles', ActionFileRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.backpackLinks', ActionLinkRepository::ALIAS);
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

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
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
        if (!empty($dto->getWordSearch())) {
            $builder
                ->andwhere(
                    self::ALIAS . '.content like :search' .
                        ' OR ' . self::ALIAS . '.name like :search' .
                        ' OR ' . self::ALIAS . '.stateContent like :search' .
                        ' OR ' . ActionLinkRepository::ALIAS . '.title like :search' .
                        ' OR ' . ActionLinkRepository::ALIAS . '.link like :search' .
                        ' OR ' . ActionLinkRepository::ALIAS . '.content like :search' .
                        ' OR ' . ActionFileRepository::ALIAS . '.title like :search' .
                        ' OR ' . ActionFileRepository::ALIAS . '.fileName like :search' .
                        ' OR ' . ActionFileRepository::ALIAS . '.content like :search'
                );

            $this->addParams('search', '%' . $dto->getWordSearch() . '%');
        }
    }

    private function initialise_orderBy()
    {
        $this->builder
            ->addOrderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }
}
