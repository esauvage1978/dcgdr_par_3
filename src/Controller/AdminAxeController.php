<?php

namespace App\Controller;

use App\Entity\Axe;
use App\Form\Admin\AxeType;
use App\Manager\AxeManager;
use App\Repository\AxeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrganismeController
 * @package App\Controller
 * @route("/admin/axe")
 */
class AdminAxeController extends AbstractGController
{
    const DOMAINE = 'axe';

    public function __construct(
        AxeRepository $repository,
        AxeManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'axe';
    }

    /**
     * @Route("/", name="axe_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="axe_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Axe(), AxeType::class, false);
    }

    /**
     * @Route("/{id}", name="axe_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Axe $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="axe_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Axe $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="axe_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Axe $item)
    {
        return $this->editAction($request, $item, AxeType::class);
    }
}
