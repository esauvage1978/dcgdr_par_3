<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Pole;
use App\Form\Admin\PoleType;
use App\Repository\PoleRepository;
use App\Manager\PoleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/pole")
 */
class AdminPoleController extends AbstractGController
{
    const DOMAINE = 'pole';

    public function __construct(
        PoleRepository $repository,
        PoleManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'pole';
    }


    /**
     * @Route("/", name="pole_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="pole_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Pole(), PoleType::class, false);
    }

    /**
     * @Route("/{id}", name="pole_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Pole $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="pole_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Pole $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="pole_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Pole $item)
    {
        return $this->editAction($request, $item, PoleType::class);
    }
}
