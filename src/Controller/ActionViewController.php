<?php

namespace App\Controller;

use App\Dto\AxeDto;
use App\Entity\Axe;
use App\Dto\PoleDto;
use App\Entity\Pole;
use App\Dto\ActionDto;
use App\Dto\CategoryDto;
use App\Entity\Category;
use App\Dto\ThematiqueDto;
use App\Entity\Thematique;
use App\Manager\ActionManager;
use App\Service\ActionMakerDto;
use App\Repository\ActionDtoRepository;
use App\Repository\IndicatorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ActionController
 * @package App\Controller
 * @route("/action/view")
 */
class ActionViewController extends AbstractGController
{
    const DOMAINE = 'action';

    /**
     * @var ActionMakerDto
     */
    private $actionMakerDto;

    public function __construct(
        ActionDtoRepository $repository,
        ActionManager $manager,
        ActionMakerDto $actionMakerDto
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'action';
        $this->actionMakerDto= $actionMakerDto;
    }

    /**
     * @Route("/contributif", name="indicator_contributif", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function indexIndicatorContributifAction(IndicatorRepository $repository)
    {
        return $this->render('indicator/index_contributif.html.twig', [
            'items' => $repository->findAllIndicatorContributif(),
        ]);
    }
    /**
     * @Route("/action_without_jalon_writers", name="actions_action_without_jalon_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_without_jalon_writers()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_WITHOUT_JALON_WRITERS));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_to_late_writers", name="actions_action_jalon_to_late_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_to_late_writers()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_TO_LATE_WRITERS));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_to_near_writers", name="actions_action_jalon_to_near_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_to_near_writers()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_TO_NEAR_WRITERS));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_come_up_writers", name="actions_action_jalon_come_up_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_come_up_writers()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_COME_UP_WRITERS));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/action_without_jalon_validers_cotech", name="actions_action_without_jalon_validers_cotech", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_without_jalon_validers_cotech()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_COTECH));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_to_late_validers_cotech", name="actions_action_jalon_to_late_validers_cotech", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_to_late_validers_cotech()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_COTECH));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_to_near_validers_cotech", name="actions_action_jalon_to_near_validers_cotech", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_to_near_validers_cotech()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_TO_NEAR_VALIDERS_COTECH));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_come_up_validers_cotech", name="actions_action_jalon_come_up_validers_cotech", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_come_up_validers_cotech()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_COME_UP_VALIDERS_COTECH));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/action_without_jalon_validers_codir", name="actions_action_without_jalon_validers_codir", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_without_jalon_validers_codir()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_CODIR));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_to_late_validers_codir", name="actions_action_jalon_to_late_validers_codir", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_to_late_validers_codir()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_CODIR));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_to_near_validers_codir", name="actions_action_jalon_to_near_validers_codir", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_to_near_validers_codir()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_TO_NEAR_VALIDERS_CODIR));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_jalon_come_up_validers_codir", name="actions_action_jalon_come_up_validers_codir", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_jalon_come_up_validers_codir()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_JALON_COME_UP_VALIDERS_CODIR));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/action_without_writers", name="actions_action_without_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_without_writers()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_WITHOUT_WRITERS));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }


    /**
     * @Route("/action_without_validers_cotech", name="actions_action_without_validers_cotech", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_without_validers_cotech()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_WITHOUT_VALIDERS_COTECH));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/action_without_validers_codir", name="actions_action_without_validers_codir", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_action_without_validers_codir()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ACTION_WITHOUT_VALIDERS_CODIR));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    
    /**
     * @Route("/axe/{id}", name="actions_for_axe", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionsForAxeAction(
        Axe $item,
        ActionDtoRepository $actionDtoRepository
    ) {
        $dto=new ActionDto();
        
        $dtoAxe = new AxeDto();
        $dtoAxe->setId($item->getId());

        $dto->setAxeDto($dtoAxe);
        $dto->setVisible(ActionDto::TRUE);

        return $this->render(self::DOMAINE . '/index_axe.html.twig', [
            'axe' => $item,
            'items' => $actionDtoRepository->findAllForDto($dto),
        ]);
    }

    /**
     * @Route("/pole/{id}", name="actions_for_pole", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionsForPoleAction(
        Pole $item,
        ActionDtoRepository $actionDtoRepository
    ) {
        $dto = new ActionDto();

        $dtoP = new PoleDto();
        $dtoP->setId($item->getId());

        $dto->setPoleDto($dtoP);
        $dto->setVisible(ActionDto::TRUE);

        return $this->render(self::DOMAINE . '/index_pole.html.twig', [
            'pole' => $item,
            'items' => $actionDtoRepository->findAllForDto($dto),
        ]);
    }

    /**
     * @Route("/thematique/{id}", name="actions_for_thematique", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionsForThematiqueAction(
        Thematique $item,
        ActionDtoRepository $actionDtoRepository
    ) {
        $dto = new ActionDto();

        $dtoT = new ThematiqueDto();
        $dtoT->setId($item->getId());

        $dto->setThematiqueDto($dtoT);
        $dto->setVisible(ActionDto::TRUE);

        return $this->render(self::DOMAINE . '/index_thematique.html.twig', [
            'thematique' => $item,
            'items' => $actionDtoRepository->findAllForDto($dto),
        ]);
    }

    /**
     * @Route("/category/{id}", name="actions_for_category", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionsForCategoryAction(
        Category $item,
        ActionDtoRepository $actionDtoRepository
    ) {
        $dto = new ActionDto();

        $dtoT = new CategoryDto();
        $dtoT->setId($item->getId());

        $dto->setCategoryDto($dtoT);
        $dto->setVisible(ActionDto::TRUE);

        return $this->render(self::DOMAINE . '/index_category.html.twig', [
            'category' => $item,
            'items' => $actionDtoRepository->findAllForDto($dto),
        ]);
    }
}
