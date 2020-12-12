<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Thematique;
use App\Form\Admin\ThematiqueType;
use App\Repository\ThematiqueRepository;
use App\Manager\ThematiqueManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/thematique")
 */
class AdminThematiqueController extends AbstractGController
{
    const DOMAINE = 'thematique';

    public function __construct(
        ThematiqueRepository $repository,
        ThematiqueManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'thematique';
    }


    /**
     * @Route("/", name="thematique_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="thematique_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Thematique(), ThematiqueType::class, false);
    }

    /**
     * @Route("/{id}", name="thematique_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Thematique $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="thematique_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Thematique $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="thematique_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Thematique $item)
    {
        return $this->editAction($request, $item, ThematiqueType::class);
    }
}
