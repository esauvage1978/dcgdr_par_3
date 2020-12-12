<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Repository\CategoryRepository;
use App\Manager\CategoryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/category")
 */
class AdminCategoryController extends AbstractGController
{
    const DOMAINE = 'category';

    public function __construct(
        CategoryRepository $repository,
        CategoryManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'category';
    }


    /**
     * @Route("/", name="category_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="category_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Category(), CategoryType::class, false);
    }

    /**
     * @Route("/{id}", name="category_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Category $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Category $item)
    {
        return $this->showAction($request, $item);
    }


    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Category $item)
    {
        return $this->editAction($request, $item, CategoryType::class);
    }
}
