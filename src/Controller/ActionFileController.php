<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\ActionFile;
use App\Form\File\ActionFileType;
use App\Manager\ActionFileManager;
use App\Form\File\ActionFileAddType;
use App\Repository\ActionRepository;
use App\Repository\ActionFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class ActionFileController extends AbstractGController
{


    public function __construct(
        ActionFileRepository $repository,
        ActionFileManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'actionfile';
    }

    /**
     * @Route("/actionfile/{parent_id}/add", name="action_file_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        ActionRepository $actionRepository,
        string $parent_id
    ) {
        $action = $actionRepository->find($parent_id);
        $file = new ActionFile();
        $file->setAction($action);

        $form = $this->createForm(ActionFileAddType::class, $file, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($file)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($file));
            }
        }

        return $this->render('action/_edit/_action_file_form_add.html.twig', [
            'file' => $file,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/actionfile/{id}/edit", name="action_file_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        ActionFile $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            ActionFileType::class,
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

        return $this->render('action/_edit/_action_file_form_edit.html.twig', [
            'file' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    


    /**
     * @Route("/actionfile/{id}/delete", name="action_file_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteFileAction(
        ActionFile $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/actionfile/{id}", name="action_files_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Action $item)
    {
        return $this->render('action/_edit/_actionfile.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/actionfile/{id}/showsecure", name="action_file_show_secure", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowSecureAction(
        SluggerInterface $slugger,
        ActionFile $actionFile
    ): Response {

        $actionFile->setNbrView($actionFile->getNbrView() + 1);
        $this->manager->save($actionFile);

        $file = new File($actionFile->getHref());

        return $this->file($file, $slugger->slug($actionFile->getTitle()) . '.' . $actionFile->getFileExtension());
    }
}
