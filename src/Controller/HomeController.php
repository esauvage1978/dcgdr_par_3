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
        CurrentUser $currentUser,
        ParamsInServices $paramsInServices,
        IndicatorRepository $indicatorRepository
    ) {
        $dto = new AxeDto();
        $dto->setVisible(AxeDto::TRUE);

        $md = new MakeActionDashboard($actionDtoRepository, $currentUser, $paramsInServices);
        $mdd = new MakeDeployementDashboard($deployementDtoRepository, $currentUser, $paramsInServices);

        $actions_jalon_for_pilote = [
            'without' => $md->getData(ActionMakerDto::ACTION_WITHOUT_JALON_WRITERS),
            'to_late' => $md->getData(ActionMakerDto::ACTION_JALON_TO_LATE_WRITERS),
            'to_near' => $md->getData(ActionMakerDto::ACTION_JALON_TO_NEAR_WRITERS),
            'come_up' => $md->getData(ActionMakerDto::ACTION_JALON_COME_UP_WRITERS),
        ];
        $actions_jalon_for_validers_cotech = [
            'without' => $md->getData(ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_COTECH),
            'to_late' => $md->getData(ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_COTECH),
            'to_near' => $md->getData(ActionMakerDto::ACTION_JALON_TO_NEAR_VALIDERS_COTECH),
            'come_up' => $md->getData(ActionMakerDto::ACTION_JALON_COME_UP_VALIDERS_COTECH),
        ];
        $actions_jalon_for_validers_codir = [
            'without' => $md->getData(ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_CODIR),
            'to_late' => $md->getData(ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_CODIR),
            'to_near' => $md->getData(ActionMakerDto::ACTION_JALON_TO_NEAR_VALIDERS_CODIR),
            'come_up' => $md->getData(ActionMakerDto::ACTION_JALON_COME_UP_VALIDERS_CODIR),
        ];
        $actions_intervenant = [
            'writers' => $md->getData(ActionMakerDto::ACTION_WITHOUT_WRITERS),
            'cotech' => $md->getData(ActionMakerDto::ACTION_WITHOUT_VALIDERS_COTECH),
            'codir' => $md->getData(ActionMakerDto::ACTION_WITHOUT_VALIDERS_CODIR)
        ];

        $actions_my_for_pilote = [
            'started'=>$md->getData(ActionMakerDto::STARTED_WRITABLE),
            'rejected' => $md->getData(ActionMakerDto::REJECTED_WRITABLE),
            'finalised' => $md->getData(ActionMakerDto::FINALISED_WRITABLE),
            'measured' => $md->getData(ActionMakerDto::MEASURED_WRITABLE),
        ];

        $actions_supervision_for_pilote = [
            'cotech' => $md->getData(ActionMakerDto::COTECH_WRITABLE),
            'codir' => $md->getData(ActionMakerDto::CODIR_WRITABLE),
            'deployed' => $md->getData(ActionMakerDto::DEPLOYED_WRITABLE),
            'clotured' => $md->getData(ActionMakerDto::CLOTURED_WRITABLE),
        ];
        
        $deployements_intervenant = [];
        if(Role::isGestionnaire($currentUser->getUser())) {
            array_push(
                $deployements_intervenant ,
                ['writersGestionnaire' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_WITHOUT_WRITERS_FOR_GESTIONNAIRE)]
            );
        }
        if (Role::isGestionnaireLocal($currentUser->getUser())) {
            array_push(
                $deployements_intervenant,
                ['writersGestionnaireLocal' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_WITHOUT_WRITERS_FOR_GESTIONNAIRE_LOCAL)]
            );
        }

        
        $deployements_my = [
            'en_cours' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE),
            'terminated' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE_TERMINATED)
        ];

        $deployements_jalon_for_pilote = [
            'without' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_WITHOUT_JALON_WRITERS),
            'to_late' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_JALON_TO_LATE_WRITERS),
            'to_near' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_JALON_TO_NEAR_WRITERS),
            'come_up' => $mdd->getData(DeployementMakerDto::DEPLOYEMENT_JALON_COME_UP_WRITERS),
        ];


        return $this->render('home/index.html.twig', [
            'axes' => $axeDtoRepository->findAllForDto($dto, AxeDtoRepository::FILTRE_DTO_INIT_HOME),
            'contributif' => $indicatorRepository->findAllIndicatorContributif(),
            'deployements_jalon_for_pilote' => $deployements_jalon_for_pilote,
            'deployements_intervenant' => $deployements_intervenant,
            'deployements_my' => $deployements_my,
            'actions_jalon_for_pilote' => $actions_jalon_for_pilote,
            'actions_jalon_for_validers_cotech' => $actions_jalon_for_validers_cotech,
            'actions_jalon_for_validers_codir' => $actions_jalon_for_validers_codir,
            'actions_intervenant' => $actions_intervenant,
            'actions_my_for_pilote' => $actions_my_for_pilote,
            'actions_supervision_for_pilote' => $actions_supervision_for_pilote,
        ]);
    }
}
