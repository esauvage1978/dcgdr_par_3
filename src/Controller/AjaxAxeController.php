<?php

namespace App\Controller;

use App\Dto\AxeDto;
use App\Repository\AxeDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxAxeController extends AbstractGController
{
    /**
     * @Route("/ajax/combo/getaxes", name="ajax_combo_axes", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboAxes(Request $request, AxeDtoRepository $axeDtoRepository): Response
    {
        $dto = new AxeDto();

        if ($request->isXmlHttpRequest()) {
            return $this->json($axeDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/combo/getaxesisenable", name="ajax_combo_axes_isenable", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxComboAxesIsEnable(Request $request, AxeDtoRepository $axeDtoRepository): Response
    {
        $dto = new AxeDto();
        $dto->setVisible(AxeDto::TRUE);

        if ($request->isXmlHttpRequest()) {
            return $this->json($axeDtoRepository->findForCombobox($dto));
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

}
