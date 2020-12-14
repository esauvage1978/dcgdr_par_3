<?php

namespace App\Controller;

use App\Entity\Action;
use App\Security\ActionVoter;
use App\Form\Admin\ActionType;
use App\Manager\ActionManager;
use App\Service\ActionMakerDto;
use App\Repository\ActionRepository;
use App\Repository\ActionDtoRepository;
use App\Repository\ActionFileRepository;
use App\Repository\CadrageFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
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
     * @Route("/started", name="actions_started", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actions_state_started(Request $request)
    {
        $items = $this->repository->findAllForDto($this->actionMakerDto->get( ActionMakerDto::STARTED));
        return $this->render('action/list.html.twig', ['items'=>$items]);
    }    
}
