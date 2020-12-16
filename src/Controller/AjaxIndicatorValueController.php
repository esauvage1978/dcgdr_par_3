<?php

namespace App\Controller;

use App\Manager\IndicatorValueManager;
use App\Controller\AbstractGController;
use App\Repository\IndicatorRepository;
use App\Repository\DeployementRepository;
use App\Repository\IndicatorValueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxIndicatorValueController extends AbstractGController
{
    /**
     * @Route("/ajax/indicatorvalue/toggle/{indicator_id}/{deployement_id}", name="ajax_toogle_indicator_deployement")
     *
     * @IsGranted("ROLE_USER")
     */
    public function toggleIndicatorDeployement(
        Request $request,
        string $indicator_id,
        string $deployement_id,
        IndicatorValueManager $manager,
        IndicatorValueRepository $indicatorValueRepo,
        IndicatorRepository $indicatorRepo,
        DeployementRepository $deployementRepo
    ) {
        $indicator = $indicatorRepo->find($indicator_id);
        $deployement = $deployementRepo->find($deployement_id);

        $indicatorValue = $indicatorValueRepo->findOneBy(
            [
                'deployement' => $deployement,
                'indicator' => $indicator,
            ]
        );

        $indicatorValue = $manager->initialiseEntity($indicator, $deployement, $indicatorValue);

        $manager->save($indicatorValue);
        dump($indicatorValue);
        return $this->json([
            'code' => 200,
            'value' => $indicatorValue->getIsEnable(),
            'message' => ($indicatorValue->getIsEnable() ? 'Abonné' : 'Désabonné')
        ], 200);
    }
}
