<?php

namespace App\Controller;

use App\Manager\DeployementManager;
use App\Service\DeployementMakerDto;
use App\Repository\DeployementDtoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class DeployementController
 * @package App\Controller
 * @route("/deployement/view")
 */
class DeployementViewController extends AbstractGController
{
    const DOMAINE = 'deployement';

    /**
     * @var DeployementMakerDto
     */
    private $deployementMakerDto;

    public function __construct(
        DeployementDtoRepository $repository,
        DeployementManager $manager,
        DeployementMakerDto $deployementMakerDto
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'depoyement';
        $this->deployementMakerDto = $deployementMakerDto;
    }

    /**
     * @Route("/deployed_writable", name="deployements_deployement_deployed_writable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployements_deployement_deployed_writable()
    {
        $items = $this->repository->findAllForDto($this->deployementMakerDto->get(DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE));
        return $this->render('deployement/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/deployed_writable_terminated", name="deployements_deployement_deployed_writable_terminated", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployements_deployement_deployed_writable_terminated()
    {
        $items = $this->repository->findAllForDto($this->deployementMakerDto->get(DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE_TERMINATED));
        return $this->render('deployement/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/deployed_jalon_to_near", name="deployements_deployement_jalon_to_near_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployements_deployement_jalon_to_near_writers()
    {
        $items = $this->repository->findAllForDto($this->deployementMakerDto->get(DeployementMakerDto::DEPLOYEMENT_JALON_TO_NEAR_WRITERS));
        return $this->render('deployement/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/deployed_jalon_come_up", name="deployements_deployement_jalon_come_up_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployements_deployement_jalon_come_up_writers()
    {
        $items = $this->repository->findAllForDto($this->deployementMakerDto->get(DeployementMakerDto::DEPLOYEMENT_JALON_COME_UP_WRITERS));
        return $this->render('deployement/list.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/deployed_jalon_to_late", name="deployements_deployement_jalon_to_late_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployements_deployement_jalon_to_late_writers()
    {
        $items = $this->repository->findAllForDto($this->deployementMakerDto->get(DeployementMakerDto::DEPLOYEMENT_JALON_TO_LATE_WRITERS));
        return $this->render('deployement/list.html.twig', ['items' => $items]);
    }


    /**
     * @Route("/deployed_without_jalon", name="deployements_deployement_without_jalon_writers", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployements_deployement_without_jalon_writers()
    {
        $items = $this->repository->findAllForDto($this->deployementMakerDto->get(DeployementMakerDto::DEPLOYEMENT_WITHOUT_JALON_WRITERS));
        return $this->render('deployement/list.html.twig', ['items' => $items]);
    }
}
