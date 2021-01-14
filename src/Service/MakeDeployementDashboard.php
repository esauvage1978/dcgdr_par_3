<?php

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\DeployementDto;
use App\Helper\ParamsInServices;
use App\Security\CurrentUser;
use App\Widget\WidgetInfoBox;
use App\Workflow\WorkflowData;
use App\Repository\DeployementDtoRepository;
use App\Repository\BackpackDtoRepository;

class MakeDeployementDashboard
{
    /**
     * @var DeployementCounter
     */
    private $counter;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    private const ROUTE = 'route';
    private const ROUTE_OPTIONS = 'route_options';
    private const BG_COLOR = 'bgColor';
    private const FORE_COLOR = 'foreColor';
    private const TITLE = 'title';
    private const ICONE = 'icone';
    private const NBR = 'nbr';


    private const STATE = 'state';


    public function getData(string $filter)
    {
        $datas = [
            DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE => [
                self::TITLE => 'En cours',
            ],
            DeployementMakerDto::DEPLOYEMENT_DEPLOYED_WRITABLE_TERMINATED => [
                self::TITLE => 'Terminé',
            ],
            DeployementMakerDto::DEPLOYEMENT_JALON_COME_UP_WRITERS => [
                self::TITLE => 'Jalon > ' . $this->paramsInServices->get(ParamsInServices::ES_JALON_TO_NEAR) . ' jours',
            ],
            DeployementMakerDto::DEPLOYEMENT_JALON_TO_LATE_WRITERS => [
                self::TITLE => 'Jalon dépassé',
            ],
            DeployementMakerDto::DEPLOYEMENT_WITHOUT_JALON_WRITERS => [
                self::TITLE => 'Sans Jalon',
            ],            
            DeployementMakerDto::DEPLOYEMENT_JALON_TO_NEAR_WRITERS => [
                self::TITLE => 'Jalon <= ' . $this->paramsInServices->get(ParamsInServices::ES_JALON_TO_NEAR) . ' jours',
            ],
            DeployementMakerDto::DEPLOYEMENT_WITHOUT_WRITERS_FOR_GESTIONNAIRE => [
                self::TITLE => 'Sans pilote (Gestionnaire)',
            ],
            DeployementMakerDto::DEPLOYEMENT_WITHOUT_WRITERS_FOR_GESTIONNAIRE_LOCAL => [
                self::TITLE => 'Sans pilote (Gestionnaire local)',
            ],                           
        ];


        return $this->getArray($datas[$filter], $filter);
    }

    public function __construct(
        DeployementDtoRepository $deployementDtoRepository,
        CurrentUser $currentUser,
        ParamsInServices $paramsInServices
    ) {
        $this->counter = new DeployementCounter($deployementDtoRepository, $currentUser);
        $this->paramsInServices=$paramsInServices;
    }

    private function getArray($datas, $filter)
    {
        $ib = new WidgetInfoBox();
        return $ib
            ->setRoute('deployements_' . $filter)
            ->setRouteOptions(key_exists(self::ROUTE_OPTIONS, $datas) ? $datas[self::ROUTE_OPTIONS] : null)
            ->setBgColor(array_keys($datas, self::STATE) ?  WorkflowData::getBGColorOfState($datas[self::STATE]) : "")
            ->setForeColor(array_keys($datas, self::STATE) ? WorkflowData::getForeColorOfState($datas[self::STATE]) : "")
            ->setIcone(
                array_keys($datas, self::STATE) ?
                WorkflowData::getIconOfState($datas[self::STATE]) :"fas fa-city"
            )
            ->setTitle($datas[self::TITLE])
            ->setData($this->counter->get($filter))
            ->createArray();
    }
}
