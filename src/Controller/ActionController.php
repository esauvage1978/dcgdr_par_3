<?php

namespace App\Controller;

use App\Entity\Action;
use App\History\HistoryShow;
use App\Security\ActionVoter;
use App\Manager\ActionManager;
use App\Form\Action\ActionEditType;
use App\Repository\ActionRepository;
use App\Form\Action\ActionCreateType;
use App\Controller\AbstractGController;
use App\Repository\ActionFileRepository;
use App\Repository\CadrageFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Class ActionController
 * @package App\Controller
 * @route("/action")
 */
class ActionController extends AbstractGController
{
    const DOMAINE = 'action';

    public function __construct(
        ActionRepository $repository,
        ActionManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'action';
    }

    /**
     * @Route("/", name="action_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="action_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Action(), ActionCreateType::class, false);
    }

    /**
     * @Route("/{id}", name="action_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Action $item)
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $this->manager->remove($item);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}", name="action_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Action $item)
    {
        $this->denyAccessUnlessGranted(ActionVoter::READ, $item);
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="action_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Action $item)
    {
        $this->denyAccessUnlessGranted(ActionVoter::UPDATE, $item);
        $itemOld = clone ($item);
        $form = $this->createForm(ActionEditType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
                $this->manager->historize($item, $itemOld);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('action/edit.html.twig', [
            'item' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/action/{id}/file/{fileId}", name="action_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowAction(
        Request $request,
        Action $action,
        string $fileId,
        ActionFileRepository $actionFileRepository
    ) {
        $this->denyAccessUnlessGranted(ActionVoter::READ, $action);

        $actionFile = $actionFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($actionFile->getHref());

        // rename the downloaded file
        return $this->file($file, $actionFile->getTitle() . '.' . $actionFile->getFileExtension());
    }

    /**
     * @Route("/action/{id}/filecadrage/{fileId}", name="cadrage_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function cadrageFileShowAction(
        Request $request,
        Action $action,
        string $fileId,
        CadrageFileRepository $cadrageFileRepository
    ) {
        $this->denyAccessUnlessGranted(ActionVoter::READ, $action);

        $cadrageFile = $cadrageFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($cadrageFile->getHref());

        // rename the downloaded file
        return $this->file($file, $cadrageFile->getTitle() . '.' . $cadrageFile->getFileExtension());
    }

    /**
     * @Route("/action/{id}/history", name="action_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function historyAction(Request $request, Action $item)
    {
        $this->denyAccessUnlessGranted(ActionVoter::READ, $item);
        $historyShow = new HistoryShow(
            $this->generateUrl('action_edit', ['id' => $item->getId()]),
            "Porte-document : " . $item->getName(),
            "Historiques des modifications du porte-document"
        );

        return $this->render('action/history.html.twig', [
            'item' => $item,
            'histories' => $item->getHistories(),
            'data' => $historyShow->getParams()
        ]);
    }



}
