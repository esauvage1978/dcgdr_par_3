<?php

namespace App\Controller;

use App\Dto\PoleDto;
use App\Dto\ThematiqueDto;
use App\Repository\ThematiqueDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxThematiqueController extends AbstractGController
{
    /**
     * @Route("/ajax/combo/getthematiques", name="ajax_combo_thematiques", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboThematiques(Request $request, ThematiqueDtoRepository $thematiqueDtoRepository): Response
    {
        $dto = new ThematiqueDto();
        $dto->setPoleDto((new PoleDto())->setid($request->request->get('id')));

        if ($request->isXmlHttpRequest()) {
            return $this->json($thematiqueDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/combo/getthematiquesisenable", name="ajax_combo_thematiques_isenable", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboThematiquesIsEnable(Request $request, ThematiqueDtoRepository $thematiqueDtoRepository): Response
    {
        $dto = new ThematiqueDto();
        $dto->setPoleDto((new PoleDto())->setid($request->request->get('id')));
        $dto->setVisible(ThematiqueDto::TRUE);

        if ($request->isXmlHttpRequest()) {
            return $this->json($thematiqueDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
