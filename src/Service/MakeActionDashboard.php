<?php

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ActionDto;
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
        ];


        return $this->getArray($datas[$filter],$filter);
    }

    public function __construct(
        ActionDtoRepository $actionDtoRepository,
        User $user
    ) {
        $this->counter = new ActionCounter($actionDtoRepository, $user);
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
