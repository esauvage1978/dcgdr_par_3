<?php

namespace App\Controller;

use App\Dto\AxeDto;
use App\Dto\PoleDto;
use App\Repository\PoleDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxPoleController extends AbstractGController
{
    /**
     * @Route("/ajax/combo/getpoles", name="ajax_combo_poles", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboPoles(Request $request, PoleDtoRepository $poleDtoRepository): Response
    {
        $dto = new PoleDto();
        $dto->setAxeDto((new AxeDto())->setid($request->request->get('id')));

        if ($request->isXmlHttpRequest()) {
            return $this->json($poleDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/combo/getpolesisenable", name="ajax_combo_poles_isenable", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboPolesIsEnable(Request $request, PoleDtoRepository $poleDtoRepository): Response
    {
        $dto = new PoleDto();
        $dto->setAxeDto((new AxeDto())->setid($request->request->get('id')));
        $dto->setVisible(PoleDto::TRUE);

        if ($request->isXmlHttpRequest()) {
            return $this->json($poleDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
