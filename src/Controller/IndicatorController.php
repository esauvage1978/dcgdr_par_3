<?php

namespace App\Controller;

use App\Entity\Indicator;
use App\Security\IndicatorVoter;
use App\Form\Admin\IndicatorType;
use App\Manager\IndicatorManager;
use App\Repository\IndicatorRepository;
use App\Repository\IndicatorFileRepository;
use App\Repository\CadrageFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Class IndicatorController
 * @package App\Controller
 * @route("/indicator")
 */
class IndicatorController extends AbstractGController
{
    const DOMAINE = 'indicator';

    public function __construct(
        IndicatorRepository $repository,
        IndicatorManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'indicator';
    }

    /**
     * @Route("/", name="indicator_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="indicator_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Indicator(), IndicatorType::class, false);
    }

    /**
     * @Route("/{id}", name="indicator_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Indicator $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="indicator_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Indicator $item)
    {
        $this->denyAccessUnlessGranted(IndicatorVoter::READ, $item);
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="indicator_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Indicator $item)
    {
        return $this->editAction($request, $item, IndicatorType::class);
    }

}
