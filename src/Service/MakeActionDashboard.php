<?php

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ActionDto;
use App\Security\CurrentUser;
use App\Widget\WidgetInfoBox;
use App\Workflow\WorkflowData;
use App\Repository\ActionDtoRepository;
use App\Repository\BackpackDtoRepository;

class MakeActionDashboard
{
    /**
     * @var ActionCounter
     */
    private $counter;


    private const ROUTE = 'route';
    private const ROUTE_OPTIONS = 'route_options';
    private const BG_COLOR = 'bgColor';
    private const FORE_COLOR = 'foreColor';
    private const TITLE = 'title';
    private const ICONE = 'icone';
    private const NBR = 'nbr';


private const STATE='state';


    public function getData(string $filter)
    {
        $datas = [
            ActionMakerDto::STARTED => [
                self::STATE =>  WorkflowData::STATE_STARTED,
                self::TITLE => 'Tous les brouillons',
            ],
            ActionMakerDto::STARTED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_STARTED,
                self::TITLE => 'Les brouillons modifiable',
            ],
            ActionMakerDto::STARTED_READABLE => [
                self::STATE =>  WorkflowData::STATE_STARTED,
                self::TITLE => 'Les brouillons consultable',
            ],
            ActionMakerDto::COTECH => [
                self::STATE =>  WorkflowData::STATE_COTECH,
                self::TITLE => 'Au COTECH',
            ],
            ActionMakerDto::COTECH_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_COTECH,
                self::TITLE => 'Au COTECH modifiable',
            ],
            ActionMakerDto::COTECH_READABLE => [
                self::STATE =>  WorkflowData::STATE_COTECH,
                self::TITLE => 'Au COTECH consultable',
            ],
            ActionMakerDto::CODIR => [
                self::STATE =>  WorkflowData::STATE_CODIR,
                self::TITLE => 'Au CODIR',
            ],
            ActionMakerDto::CODIR_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_CODIR,
                self::TITLE => 'Au CODIR modifiable',
            ],
            ActionMakerDto::CODIR_READABLE => [
                self::STATE =>  WorkflowData::STATE_CODIR,
                self::TITLE => 'Au CODIR consultable',
            ],
            ActionMakerDto::REJECTED => [
                self::STATE =>  WorkflowData::STATE_REJECTED,
                self::TITLE => 'Rejeté',
            ],
            ActionMakerDto::REJECTED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_REJECTED,
                self::TITLE => 'Rejeté et modifiable',
            ],
            ActionMakerDto::REJECTED_READABLE => [
                self::STATE =>  WorkflowData::STATE_REJECTED,
                self::TITLE => 'Rejeté et consultable',
            ],
            ActionMakerDto::FINALISED => [
                self::STATE =>  WorkflowData::STATE_FINALISED,
                self::TITLE => 'A finaliser',
            ],
            ActionMakerDto::FINALISED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_FINALISED,
                self::TITLE => 'A finaliser et modifiable',
            ],
            ActionMakerDto::FINALISED_READABLE => [
                self::STATE =>  WorkflowData::STATE_FINALISED,
                self::TITLE => 'A finaliser et consultable',
            ],
            ActionMakerDto::DEPLOYED => [
                self::STATE =>  WorkflowData::STATE_DEPLOYED,
                self::TITLE => 'Déployé',
            ],
            ActionMakerDto::DEPLOYED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_DEPLOYED,
                self::TITLE => 'Déployé et modifiable',
            ],
            ActionMakerDto::DEPLOYED_READABLE => [
                self::STATE =>  WorkflowData::STATE_DEPLOYED,
                self::TITLE => 'Déployé consultable',
            ],
            ActionMakerDto::MEASURED => [
                self::STATE =>  WorkflowData::STATE_MEASURED,
                self::TITLE => 'A mesurer',
            ],
            ActionMakerDto::MEASURED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_MEASURED,
                self::TITLE => 'A mesurer et modifiable',
            ],
            ActionMakerDto::MEASURED_READABLE => [
                self::STATE =>  WorkflowData::STATE_MEASURED,
                self::TITLE => 'A mesurer et consultable',
            ],
            ActionMakerDto::CLOTURED => [
                self::STATE =>  WorkflowData::STATE_CLOTURED,
                self::TITLE => 'Clôturé',
            ],
            ActionMakerDto::CLOTURED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_CLOTURED,
                self::TITLE => 'Clôturé et modifiable',
            ],
            ActionMakerDto::CLOTURED_READABLE => [
                self::STATE =>  WorkflowData::STATE_CLOTURED,
                self::TITLE => 'Clôturé et consultable',
            ],
            ActionMakerDto::ABANDONNED => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Abandonné',
            ],
            ActionMakerDto::ABANDONNED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Abandonné et modifiable',
            ],
            ActionMakerDto::ABANDONNED_READABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Abandonné et consultable',
            ],
        ];


        return $this->getArray($datas[$filter],$filter);
    }

    public function __construct(
        ActionDtoRepository $actionDtoRepository,
        CurrentUser $currentUser
    ) {
        $this->counter = new ActionCounter($actionDtoRepository, $currentUser);
    }

    private function getArray($datas,$filter)
    {
        $ib = new WidgetInfoBox();

        return $ib
            ->setRoute('actions_' . $filter)
            ->setRouteOptions(key_exists(self::ROUTE_OPTIONS,$datas)? $datas[self::ROUTE_OPTIONS]:null)
            ->setBgColor(WorkflowData::getBGColorOfState($datas[self::STATE]))
            ->setForeColor(WorkflowData::getForeColorOfState($datas[self::STATE]))
            ->setIcone(WorkflowData::getIconOfState($datas[self::STATE]))
            ->setTitle($datas[self::TITLE])
            ->setData($this->counter->get($filter))
            ->createArray();
    }


}
