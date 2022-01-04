<?php

namespace App\Controller;

use App\Entity\Deployement;
use App\Entity\DeployementLink;
use App\Form\File\DeployementLinkType;
use App\Manager\DeployementLinkManager;
use App\Repository\DeployementRepository;
use App\Repository\DeployementLinkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class DeployementLinkController extends AbstractGController
{


    public function __construct(
        DeployementLinkRepository $repository,
        DeployementLinkManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'deployementlink';
    }

    /**
     * @Route("/deployementlink/{parent_id}/add", name="deployement_link_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        DeployementRepository $deployementRepository,
        string $parent_id
    ) {
        $deployement = $deployementRepository->find($parent_id);
        $link = new DeployementLink();
        $link->setDeployement($deployement);

        $form = $this->createForm(DeployementLinkType::class, $link, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($link)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($link));
            }
        }

        return $this->render('deployement/_edit/_deployement_link_form_add.html.twig', [
            'link' => $link,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/deployementlink/{id}/edit", name="deployement_link_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        DeployementLink $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            DeployementLinkType::class,
            $item,
            ['action' => $this->generateUrl($request->get('_route'), ['id' => $item->getId() ])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->manager->historize($item, $itemOld);
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('deployement/_edit/_deployement_link_form_edit.html.twig', [
            'link' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/deployementlink/{id}/delete", name="deployement_link_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteLinkDeployement(
        DeployementLink $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/deployementlink/{id}", name="deployement_links_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Deployement $item)
    {
        return $this->render('deployement/_edit/_deployementlink.html.twig', [
            'item' => $item
        ]);
    }
}
