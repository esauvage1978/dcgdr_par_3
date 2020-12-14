<?php

namespace App\Controller;

use App\Dto\ThematiqueDto;
use App\Dto\CategoryDto;
use App\Repository\CategoryDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxCategoryController extends AbstractGController
{
    /**
     * @Route("/ajax/combo/getcategorys", name="ajax_combo_categorys", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboCategorys(Request $request, CategoryDtoRepository $categoryDtoRepository): Response
    {
        $dto = new CategoryDto();
        $dto->setThematiqueDto((new ThematiqueDto())->setid($request->request->get('id')));

        if ($request->isXmlHttpRequest()) {
            return $this->json($categoryDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/combo/getcategorysisenable", name="ajax_combo_categorys_isenable", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboCategorysIsEnable(Request $request, CategoryDtoRepository $categoryDtoRepository): Response
    {
        $dto = new CategoryDto();
        $dto->setThematiqueDto((new ThematiqueDto())->setid($request->request->get('id')));
        $dto->setVisible(CategoryDto::TRUE);

        if ($request->isXmlHttpRequest()) {
            return $this->json($categoryDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
