<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Cadrage;
use App\Entity\CadrageLink;
use App\Form\File\CadrageLinkType;
use App\Manager\CadrageLinkManager;
use App\Repository\ActionRepository;
use App\Repository\CadrageLinkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class CadrageLinkController extends AbstractGController
{


    public function __construct(
        CadrageLinkRepository $repository,
        CadrageLinkManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'cadragelink';
    }

    /**
     * @Route("/cadragelink/{parent_id}/add", name="cadrage_link_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        ActionRepository $actionRepository,
        string $parent_id
    ) {
        $action = $actionRepository->find($parent_id);
        $link = new CadrageLink();
        $link->setAction($action);

        $form = $this->createForm(CadrageLinkType::class, $link, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($link)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($link));
            }
        }

        return $this->render('action/_edit/_cadrage_link_form_add.html.twig', [
            'link' => $link,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/cadragelink/{id}/edit", name="cadrage_link_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        CadrageLink $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            CadrageLinkType::class,
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

        return $this->render('action/_edit/_cadrage_link_form_edit.html.twig', [
            'link' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/cadragelink/{id}/delete", name="cadrage_link_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteLinkAction(
        CadrageLink $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/cadragelink/{id}", name="cadrage_links_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Action $item)
    {
        return $this->render('action/_edit/_cadragelink.html.twig', [
            'item' => $item
        ]);
    }
}
