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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ActionController
 * @package App\Controller
 * @route("/action/view")
 */
class ActionViewWorkflowController extends AbstractGController
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
     * @Route("/started", name="actions_started", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_started()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get( ActionMakerDto::STARTED));
        return $this->render('action/list.html.twig', ['items'=>$items]);
    }
    /**
     * @Route("/started_writable", name="actions_started_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_started_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::STARTED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/started_readable", name="actions_started_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_started_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::STARTED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/cotech", name="actions_cotech", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_cotech()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::COTECH));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/cotech_writable", name="actions_cotech_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_cotech_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::COTECH_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/cotech_readable", name="actions_cotech_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_cotech_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::COTECH_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/codir", name="actions_codir", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_codir()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::CODIR));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/codir_writable", name="actions_codir_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_codir_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::CODIR_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/codir_readable", name="actions_codir_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_codir_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::CODIR_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/rejected", name="actions_rejected", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_rejected()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::REJECTED));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/rejected_writable", name="actions_rejected_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_rejected_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::REJECTED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/rejected_readable", name="actions_rejected_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_rejected_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::REJECTED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/finalised", name="actions_finalised", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_finalised()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::FINALISED));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/finalised_writable", name="actions_finalised_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_finalised_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::FINALISED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/finalised_readable", name="actions_finalised_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_finalised_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::FINALISED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/deployed", name="actions_deployed", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_deployed()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::DEPLOYED));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/deployed_writable", name="actions_deployed_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_deployed_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::DEPLOYED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/deployed_readable", name="actions_deployed_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_deployed_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::DEPLOYED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/measured", name="actions_measured", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_measured()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::MEASURED));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/measured_writable", name="actions_measured_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_measured_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::MEASURED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/measured_readable", name="actions_measured_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_measured_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::MEASURED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/clotured", name="actions_clotured", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_clotured()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::CLOTURED));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/clotured_writable", name="actions_clotured_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_clotured_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::CLOTURED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/clotured_readable", name="actions_clotured_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_clotured_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::CLOTURED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/abandonned", name="actions_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_abandonned()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ABANDONNED));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/abandonned_writable", name="actions_abandonned_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_abandonned_writable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ABANDONNED_WRITABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
    /**
     * @Route("/abandonned_readable", name="actions_abandonned_readable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_abandonned_readable()
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get(ActionMakerDto::ABANDONNED_READABLE));
        return $this->render('action/list.html.twig', ['items' => $items]);
    }
  
}
