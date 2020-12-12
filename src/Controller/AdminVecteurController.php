<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Vecteur;
use App\Form\Admin\VecteurType;
use App\Repository\VecteurRepository;
use App\Manager\VecteurManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/vecteur")
 */
class AdminVecteurController extends AbstractGController
{
    const DOMAINE = 'vecteur';

    public function __construct(
        VecteurRepository $repository,
        VecteurManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'vecteur';
    }


    /**
     * @Route("/", name="vecteur_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="vecteur_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Vecteur(), VecteurType::class, false);
    }

    /**
     * @Route("/{id}", name="vecteur_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Vecteur $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="vecteur_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Vecteur $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="vecteur_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Vecteur $item)
    {
        return $this->editAction($request, $item, VecteurType::class);
    }
}
