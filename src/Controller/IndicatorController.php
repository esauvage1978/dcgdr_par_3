<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Indicator;
use App\Security\IndicatorVoter;
use App\Form\Indicator\IndicatorType;
use App\Manager\IndicatorManager;
use App\Controller\AbstractGController;
use App\Repository\IndicatorRepository;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{id}/indicator/add", name="indicator_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Action $action, Request $request, IndicatorManager $manager)
    {
        $entity = new Indicator();
        $entity->setAction($action);
        $form = $this->createForm(IndicatorType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
            $this->addFlash(self::SUCCESS, self::MSG_CREATE);
            return $this->redirectToRoute('indicator_edit', ['id' => $entity->getId()]);
        }

        return $this->render('indicator/add.html.twig', [
            'item' => $entity,
            'action' => $action,
            self::FORM => $form->createView(),
        ]);
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
    public function edit(Request$request, IndicatorManager $manager, Indicator $item)
    {
        $form = $this->createForm(IndicatorType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($item);
            $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
        }

        return $this->render('indicator/edit.html.twig', [
            'item' => $item,
            self::FORM => $form->createView(),
        ]);
    }

}
