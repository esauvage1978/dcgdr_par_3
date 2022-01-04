<?php

namespace App\Controller;

use App\Entity\Deployement;
use App\Entity\DeployementFile;
use App\Form\File\DeployementFileType;
use App\Manager\DeployementFileManager;
use App\Form\File\DeployementFileAddType;
use App\Repository\DeployementRepository;
use App\Repository\DeployementFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class DeployementFileController extends AbstractGController
{


    public function __construct(
        DeployementFileRepository $repository,
        DeployementFileManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'deployementfile';
    }

    /**
     * @Route("/deployementfile/{parent_id}/add", name="deployement_file_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        DeployementRepository $deployementRepository,
        string $parent_id
    ) {
        $deployement = $deployementRepository->find($parent_id);
        $file = new DeployementFile();
        $file->setDeployement($deployement);

        $form = $this->createForm(DeployementFileAddType::class, $file, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($file)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($file));
            }
        }

        return $this->render('deployement/_edit/_deployement_file_form_add.html.twig', [
            'file' => $file,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/deployementfile/{id}/edit", name="deployement_file_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        DeployementFile $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            DeployementFileType::class,
            $item,
            ['action' => $this->generateUrl($request->get('_route'), ['id' => $item->getId()])]
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

        return $this->render('deployement/_edit/_deployement_file_form_edit.html.twig', [
            'file' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    


    /**
     * @Route("/deployementfile/{id}/delete", name="deployement_file_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteFileDeployement(
        DeployementFile $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/deployementfile/{id}", name="deployement_files_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Deployement $item)
    {
        return $this->render('deployement/_edit/_deployementfile.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/deployementfile/{id}/showsecure", name="deployement_file_show_secure", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deployementFileShowSecureDeployement(
        SluggerInterface $slugger,
        DeployementFile $deployementFile
    ): Response {

        $deployementFile->setNbrView($deployementFile->getNbrView() + 1);
        $this->manager->save($deployementFile);

        $file = new File($deployementFile->getHref());

        return $this->file($file, $slugger->slug($deployementFile->getTitle()) . '.' . $deployementFile->getFileExtension());
    }
}
