<?php

namespace App\Controller;

use App\Dto\ActionDto;
use App\Entity\Action;
use App\Dto\CorbeilleDto;
use App\Dto\OrganismeDto;
use App\Dto\DeployementDto;
use App\Entity\Deployement;
use App\Security\DeployementVoter;
use App\Form\Admin\DeployementType;
use App\Manager\DeployementManager;
use App\Repository\OrganismeRepository;
use App\Repository\CadrageFileRepository;
use App\Repository\DeployementRepository;
use App\Repository\CorbeilleDtoRepository;
use App\Repository\OrganismeDtoRepository;
use App\Repository\DeployementDtoRepository;
use App\Form\Deployement\DeployementEditType;
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
     * @Route("/action/{id}/deployements/add/{organismeid}", name="deployement_add", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function addDeployementAction(
        Action $action,
        string $organismeid,
        OrganismeRepository $organismeRepository,
        CorbeilleDtoRepository $corbeilleDtoRepository,
        DeployementManager $deployementManager
    ) {
        $organisme=$organismeRepository->find($organismeid);

        $orgDto = new organismeDto();
        $orgDto->setId($organismeid);

        $corDto=new CorbeilleDto();
        $corDto->setOrganismeDto($orgDto);
        $corDto->setIsUseByDefault(CorbeilleDto::TRUE);
        $corDto->setVisible(CorbeilleDto::TRUE);

        $corbeilles= $corbeilleDtoRepository->findAllForDto($corDto);

        $deployement = $deployementManager->createDeployement(
            new Deployement(),
            $action,
            $organisme,
            $corbeilles
        );
        return $this->redirectToRoute('deployement_edit', ['id' => $deployement->getId()]);
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
     * @Route("/deploiement/{id}/actionedit", name="deployement_edit", methods={"GET","POST"})
     *
     * @param string $message =self::MSG_MODIFY
     *
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function edit(
        Request $request,
        Deployement $entity,
        DeployementManager $manager,
        string $message = self::MSG_MODIFY
    ) {
        $form = $this->createForm(DeployementEditType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($entity);
            $this->addFlash(self::SUCCESS, $message);
        }

        return $this->render('deployement/edit.html.twig', [
            'item' => $entity,
            self::FORM => $form->createView(),
        ]);
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
     * @Route("/action/{id}/selection", name="selection_organismes_for_action", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function selectionOrganismeForAction(Action $action, DeployementDtoRepository $repository, OrganismeDtoRepository $organismeRepository)
    {
        $orgDto=new OrganismeDto();
        $orgDto->setVisible(OrganismeDto::TRUE);

        $actionDto = new ActionDto();
        $actionDto->setid($action->getId());
        $actionDto->setVisible(ActionDto::TRUE);


        $depDto=new DeployementDto();
        $depDto->setActionDto($actionDto);

        return $this->render(self::DOMAINE . '/selection.html.twig', [
            'deployements' => $repository->findAllForDto($depDto),
            'organismes' => $organismeRepository->findAllForDto($orgDto),
            'action' => $action,
        ]);
    }

}
