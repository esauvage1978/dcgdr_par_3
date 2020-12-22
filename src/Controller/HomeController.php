<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AxeDto;
use App\Helper\ParamsInServices;
use App\Security\Role;
use App\Security\CurrentUser;
use App\Service\ActionMakerDto;
use App\Repository\AxeDtoRepository;
use App\Service\DeployementMakerDto;
use App\Service\MakeActionDashboard;
use App\Repository\ActionDtoRepository;
use App\Service\MakeDeployementDashboard;
use App\Repository\DeployementDtoRepository;
use App\Repository\IndicatorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        AxeDtoRepository $axeDtoRepository,
        ActionDtoRepository $actionDtoRepository,
        DeployementDtoRepository $deployementDtoRepository,
        CurrentUser $user,
        ParamsInServices $paramsInServices,
        IndicatorRepository $indicatorRepository
    ) {
        $dto = new AxeDto();
        $dto->setVisible(AxeDto::TRUE);

        $md = new MakeActionDashboard($actionDtoRepository, $user);
        $mdd = new MakeDeployementDashboard($deployementDtoRepository, $user, $paramsInServices);

        $action_urgent = [
            $md->getData(ActionMakerDto::ACTION_WITHOUT_JALON_WRITERS),
            $md->getData(ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_COTECH),
            $md->getData(ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_CODIR),
            $md->getData(ActionMakerDto::ACTION_JALON_TO_LATE_WRITERS),
            $md->getData(ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_COTECH),
            $md->getData(ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_CODIR),
            $md->getData(ActionMakerDto::ACTION_WITHOUT_VALIDERS_COTECH),
            $md->getData(ActionMakerDto::ACTION_WITHOUT_VALIDERS_CODIR)
        ];
        
        if (Role::isGestionnaire($user->getUser())) {
            $action_urgent = array_merge($action_urgent, [
                $md->getData(ActionMakerDto::ACTION_WITHOUT_WRITERS),

            ]);
        }


        $action_en_cours = [
            $md->getData(ActionMakerDto::STARTED_WRITABLE),
            $md->getData(ActionMakerDto::REJECTED_WRITABLE),
            $md->getData(ActionMakerDto::FINALISED_WRITABLE),
            $md->getData(ActionMakerDto::MEASURED_WRITABLE),
            $md->getData(ActionMakerDto::COTECH_WRITABLE),
            $md->getData(ActionMakerDto::CODIR_WRITABLE),
        ];

        $action_supervision = [
            $md->getData(ActionMakerDto::COTECH_READABLE),
            $md->getData(ActionMakerDto::CODIR_READABLE),
            $md->getData(ActionMakerDto::DEPLOYED_WRITABLE),
            $md->getData(ActionMakerDto::CLOTURED_WRITABLE),
        ];
       
        $action_a_venir = [
            $md->getData(ActionMakerDto::ACTION_WITHOUT_WRITERS),
            $md->getData(ActionMakerDto::ACTION_WITHOUT_VALIDERS_COTECH),
            $md->getData(ActionMakerDto::ACTION_WITHOUT_VALIDERS_CODIR),
        ];

        $action_terminated = [];


        $actions = [
            ['data' => $action_urgent, 'color' => 'danger'],
            ['data' => $action_en_cours, 'color' => 'warning'],
            ['data' => $action_a_venir, 'color' => 'success'],
            ['data' => $action_supervision, 'color' => 'primary'],
            ['data' => $action_terminated, 'color' => 'info'],
        ];

        $deployement_urgent = [
            $mdd->getData(DeployementMakerDto::DEPLOYEMENT_WITHOUT_JALON_WRITERS),
            $mdd->getData(DeployementMakerDto::DEPLOYEMENT_JALON_TO_LATE_WRITERS),
        ];
        $deployement_en_cours = [];
        $deployement_a_venir = [
            $mdd->getData(DeployementMakerDto::DEPLOYEMENT_JALON_COME_UP_WRITERS),
        ];
        $deployement_supervision = [];
        $deployement_en_cours = [
            $mdd->getData(DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE),
            $mdd->getData(DeployementMakerDto::DEPLOYEMENT_JALON_TO_NEAR_WRITERS),
        ];
        $deployement_terminated = [
            $mdd->getData(DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE_TERMINATED),
        ];

        $deployements = [
            ['data' => $deployement_urgent, 'color' => 'danger'],
            ['data' => $deployement_en_cours, 'color' => 'warning'],
            ['data' => $deployement_a_venir, 'color' => 'success'],
            ['data' => $deployement_supervision, 'color' => 'primary'],
            ['data' => $deployement_terminated, 'color' => 'info'],
        ];

        return $this->render('home/index.html.twig', [
            'axes' => $axeDtoRepository->findAllForDto($dto, AxeDtoRepository::FILTRE_DTO_INIT_HOME),
            'actions' => $actions,
            'deployements' => $deployements,
            'contributif' => $indicatorRepository->findAllIndicatorContributif()
        ]);
    }
}
