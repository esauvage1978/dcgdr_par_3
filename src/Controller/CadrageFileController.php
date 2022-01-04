<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Cadrage;
use App\Entity\CadrageFile;
use App\Form\File\CadrageFileType;
use App\Manager\CadrageFileManager;
use App\Repository\ActionRepository;
use App\Form\File\CadrageFileAddType;
use App\Repository\CadrageFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class CadrageFileController extends AbstractGController
{


    public function __construct(
        CadrageFileRepository $repository,
        CadrageFileManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'cadragefile';
    }

    /**
     * @Route("/cadragefile/{parent_id}/add", name="cadrage_file_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        ActionRepository $actionRepository,
        string $parent_id
    ) {
        $action = $actionRepository->find($parent_id);
        $file = new CadrageFile();
        $file->setAction($action);

        $form = $this->createForm(CadrageFileAddType::class, $file, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($file)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($file));
            }
        }

        return $this->render('action/_edit/_cadrage_file_form_add.html.twig', [
            'file' => $file,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/cadragefile/{id}/edit", name="cadrage_file_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        CadrageFile $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            CadrageFileType::class,
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

        return $this->render('action/_edit/_cadrage_file_form_edit.html.twig', [
            'file' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    


    /**
     * @Route("/cadragefile/{id}/delete", name="cadrage_file_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteFileAction(
        CadrageFile $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/cadragefile/{id}", name="cadrage_files_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Action $item)
    {
        return $this->render('action/_edit/_cadragefile.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/cadragefile/{id}/showsecure", name="cadrage_file_show_secure", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowSecureAction(
        SluggerInterface $slugger,
        CadrageFile $actionFile
    ): Response {

        $actionFile->setNbrView($actionFile->getNbrView() + 1);
        $this->manager->save($actionFile);

        $file = new File($actionFile->getHref());

        return $this->file($file, $slugger->slug($actionFile->getTitle()) . '.' . $actionFile->getFileExtension());
    }
}
