<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Cible;
use App\Form\Admin\CibleType;
use App\Repository\CibleRepository;
use App\Manager\CibleManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/cible")
 */
class AdminCibleController extends AbstractGController
{
    const DOMAINE = 'cible';

    public function __construct(
        CibleRepository $repository,
        CibleManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'cible';
    }


    /**
     * @Route("/", name="cible_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="cible_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Cible(), CibleType::class, false);
    }

    /**
     * @Route("/{id}", name="cible_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Cible $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="cible_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Cible $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="cible_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Cible $item)
    {
        return $this->editAction($request, $item, CibleType::class);
    }
}
