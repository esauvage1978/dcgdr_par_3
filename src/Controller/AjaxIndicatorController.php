<?php

namespace App\Controller;

use App\Dto\AxeDto;
use App\Indicator\IndicatorData;
use App\Repository\AxeDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxIndicatorController extends AbstractGController
{
    /**
     * @Route("/ajax/indicator/showgoal/{indicator_type}", name="ajax_indicator_show_goal")
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxIndicatorShowGoal(string $indicator_type): Response
    {
        return $this->json(IndicatorData::getShowGoalOfIndicator($indicator_type));
    }

    /**
     * @Route("/ajax/indicator/showinitialvalue/{indicator_type}", name="ajax_indicator_show_initial_value")
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function ajaxIndicatorShowInitialValue(string $indicator_type): Response
    {
        return $this->json(IndicatorData::getShowInitialeValueOfIndicator($indicator_type));
    }
}
