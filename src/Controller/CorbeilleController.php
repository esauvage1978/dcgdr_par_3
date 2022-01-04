<?php

namespace App\Controller;

use App\Security\Role;
use App\Entity\Corbeille;
use App\Form\Admin\CorbeilleGesLocType;
use App\Security\CorbeilleVoter;
use App\Form\Admin\CorbeilleType;
use App\Manager\CorbeilleManager;
use App\Repository\CorbeilleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CorbeilleController
 * @package App\Controller
 * @route("/corbeille")
 */
class CorbeilleController extends AbstractGController
{
    public function __construct
    (
        CorbeilleRepository $repository,
        CorbeilleManager $manager
    )
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'corbeille';
    }

    /**
     * @Route("/", name="corbeille_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="corbeille_add", methods={"GET","POST"})
     */
    public function add(Request $request)
    {
        $item=new Corbeille();
        if (Role::isGestionnaire( $this->getUser()) || Role::isAdmin( $this->getUser())) {
            $form = $this->createForm(CorbeilleType::class, $item);
        } else {
            $form = $this->createForm(CorbeilleGesLocType::class, $item,['extra_fields_message'=>$this->getUser()->getId()]);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_CREATE);

                return $this->redirectToRoute($this->domaine . '_edit', ['id' => $item->getId()]);
            }

            $this->addFlash(self::DANGER, self::MSG_CREATE_ERROR . $this->manager->getErrors($item));
        }
        return $this->render($this->domaine . '/add.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);        
    }

    /**
     * @Route("/{id}", name="corbeille_del", methods={"DELETE"})
     */
    public function delete(Request $request, Corbeille $item)
    {
        $this->denyAccessUnlessGranted(CorbeilleVoter::DELETE,$item);
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="corbeille_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Corbeille $item)
    {
        return $this->showAction($request, $item);
    }

    /**
     * @Route("/{id}/use", name="corbeille_show_use", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showUse( Corbeille $item)
    {
        return $this->render($this->domaine . '/showUse.html.twig', [
            'item' => $item
        ]);
    }


    /**
     * @Route("/{id}/edit", name="corbeille_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Corbeille $item)
    {
        $this->denyAccessUnlessGranted(CorbeilleVoter::UPDATE,$item);
        if (Role::isGestionnaire( $this->getUser()) || Role::isAdmin( $this->getUser())) {
            $form = $this->createForm(CorbeilleType::class, $item);
        } else {
            $form = $this->createForm(CorbeilleGesLocType::class, $item,['extra_fields_message'=>$this->getUser()->getId()]);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                return $this->redirectToRoute($this->domaine . '_edit', ['id' => $item->getId()]);
            }

            $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
        }
        return $this->render($this->domaine . '/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);          
        return $this->editAction($request, $item, CorbeilleType::class);
    }
}
