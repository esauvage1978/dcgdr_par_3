<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Deployement;
use App\Security\DeployementVoter;
use App\Form\Admin\DeployementType;
use App\Manager\DeployementManager;
use App\Repository\OrganismeRepository;
use App\Repository\CadrageFileRepository;
use App\Repository\DeployementRepository;
use App\Repository\DeployementFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Class DeployementController
 * @package App\Controller
 * @route("/deployement")
 */
class DeployementController extends AbstractGController
{
    const DOMAINE = 'deployement';

    public function __construct(
        DeployementRepository $repository,
        DeployementManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'deployement';
    }

    /**
     * @Route("/", name="deployement_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="deployement_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Deployement(), DeployementType::class, false);
    }

    /**
     * @Route("/{id}", name="deployement_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Deployement $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="deployement_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Deployement $item)
    {
        $this->denyAccessUnlessGranted(DeployementVoter::READ, $item);
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="deployement_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Deployement $item)
    {
        return $this->editAction($request, $item, DeployementType::class);
    }


    /**
     * @Route("/deployement/{id}", name="deployement_append", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function appendShowAction(Request $request, Deployement $entity)
    {
        $this->denyAccessUnlessGranted(DeployementVoter::APPEND_READ, $entity);

        return $this->render(self::DOMAINE . '/append.html.twig', [
            self::DOMAINE => $entity,
        ]);
    }

    /**
     * @Route("/deployement/{id}/file/{fileId}", name="deployement_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployementFileShowDeployement(
        Request $request,
        Deployement $deployement,
        string $fileId,
        DeployementFileRepository $deployementFileRepository
    ) {
        $this->denyAccessUnlessGranted(DeployementVoter::READ, $deployement);

        $deployementFile = $deployementFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($deployementFile->getHref());

        // rename the downloaded file
        return $this->file($file, $deployementFile->getTitle() . '.' . $deployementFile->getFileExtension());
    }

    /**
     * @Route("/deployement/{id}/filecadrage/{fileId}", name="cadrage_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function cadrageFileShowDeployement(
        Request $request,
        Deployement $deployement,
        string $fileId,
        CadrageFileRepository $cadrageFileRepository
    ) {
        $this->denyAccessUnlessGranted(DeployementVoter::READ, $deployement);

        $cadrageFile = $cadrageFileRepository->find($fileId);

        // load the file from the filesystem
        $file = new File($cadrageFile->getHref());

        // rename the downloaded file
        return $this->file($file, $cadrageFile->getTitle() . '.' . $cadrageFile->getFileExtension());
    }

    /**
     * @Route("/action/{id}/deployements", name="deployements_for_action", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(Action $action, DeployementRepository $repository, OrganismeRepository $organismeRepository)
    {
        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $repository->findAllForAction($action->getId()),
            'organismes' => $organismeRepository->findAll(),
            'action' => $action,
        ]);
    }

}
