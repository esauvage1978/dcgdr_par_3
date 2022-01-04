<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\ActionLink;
use App\Form\File\ActionLinkType;
use App\Manager\ActionLinkManager;
use App\Repository\ActionRepository;
use App\Repository\ActionLinkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class ActionLinkController extends AbstractGController
{


    public function __construct(
        ActionLinkRepository $repository,
        ActionLinkManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'actionlink';
    }

    /**
     * @Route("/actionlink/{parent_id}/add", name="action_link_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        ActionRepository $actionRepository,
        string $parent_id
    ) {
        $action = $actionRepository->find($parent_id);
        $link = new ActionLink();
        $link->setAction($action);

        $form = $this->createForm(ActionLinkType::class, $link, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($link)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($link));
            }
        }

        return $this->render('action/_edit/_action_link_form_add.html.twig', [
            'link' => $link,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/actionlink/{id}/edit", name="action_link_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        ActionLink $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            ActionLinkType::class,
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

        return $this->render('action/_edit/_action_link_form_edit.html.twig', [
            'link' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/actionlink/{id}/delete", name="action_link_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteLinkAction(
        ActionLink $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/actionlink/{id}", name="action_links_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Action $item)
    {
        return $this->render('action/_edit/_actionlink.html.twig', [
            'item' => $item
        ]);
    }
}
