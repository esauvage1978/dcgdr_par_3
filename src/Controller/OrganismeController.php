<?php

namespace App\Controller;

use App\Entity\Organisme;
use App\Form\Admin\OrganismeType;
use App\Manager\OrganismeManager;
use App\Repository\OrganismeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrganismeController
 * @package App\Controller
 * @route("/organisme")
 */
class OrganismeController extends AbstractGController
{
    const DOMAINE = 'organisme';

    public function __construct
    (
        OrganismeRepository $repository,
        OrganismeManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'organisme';
    }

    /**
     * @Route("/", name="organisme_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="organisme_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Organisme(), OrganismeType::class,false);
    }

    /**
     * @Route("/{id}", name="organisme_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Organisme $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="organisme_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Organisme $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="organisme_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Organisme $item)
    {
        return $this->editAction($request, $item, OrganismeType::class);
    }
}
