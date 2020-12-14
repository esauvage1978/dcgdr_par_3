<?php

namespace App\Controller;

use App\Entity\Action;
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
        return $this->deleteAction($request, $item);
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
        return $this->editAction($request, $item, ActionEditType::class);
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

}
