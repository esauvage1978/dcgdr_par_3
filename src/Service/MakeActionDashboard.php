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


    private const STATE = 'state';


    public function getData(string $filter)
    {
        $datas = [
            ActionMakerDto::ACTION_WITHOUT_JALON_WRITERS => [
                self::TITLE => '<strong>Sans jalon</strong> pour les pilotes',
            ],
            ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_COTECH => [
                self::TITLE => '<strong>Sans jalon</strong> au COTECH',
            ],
            ActionMakerDto::ACTION_WITHOUT_JALON_VALIDERS_CODIR  => [
                self::TITLE => '<strong>Sans jalon</strong> au CODIR',
            ],
            ActionMakerDto::ACTION_JALON_TO_LATE_WRITERS => [
                self::TITLE => 'Jalon <strong>depassé</strong> pour les pilotes',
            ],
            ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_COTECH => [
                self::TITLE => 'Jalon <strong>depassé</strong> au COTECH',
            ],
            ActionMakerDto::ACTION_JALON_TO_LATE_VALIDERS_CODIR  => [
                self::TITLE => 'Jalon <strong>depassé</strong> au CODIR',
            ],            
            ActionMakerDto::ACTION_WITHOUT_WRITERS => [
                self::TITLE => '<strong>Sans pilote</strong>',
            ],
            ActionMakerDto::ACTION_WITHOUT_VALIDERS_COTECH => [
                self::TITLE => '<strong>Sans valideur du COTECH</strong>',
            ],
            ActionMakerDto::ACTION_WITHOUT_VALIDERS_CODIR  => [
                self::TITLE => '<strong>Sans valideur du CODIR</strong>',
            ],
            ActionMakerDto::STARTED => [
                self::STATE =>  WorkflowData::STATE_STARTED,
                self::TITLE => 'Tous les brouillons',
            ],
            ActionMakerDto::STARTED_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_STARTED,
                self::TITLE => '<strong>Brouillons</strong> modifiable',
            ],
            ActionMakerDto::STARTED_READABLE => [
                self::STATE =>  WorkflowData::STATE_STARTED,
                self::TITLE => '<strong>Les brouillons</strong> consultable',
            ],
            ActionMakerDto::COTECH => [
                self::STATE =>  WorkflowData::STATE_COTECH,
                self::TITLE => 'Au COTECH',
            ],
            ActionMakerDto::COTECH_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_COTECH,
                self::TITLE => '<strong>Au COTECH</strong>',
            ],
            ActionMakerDto::COTECH_READABLE => [
                self::STATE =>  WorkflowData::STATE_COTECH,
                self::TITLE => '<strong>Au COTECH</strong> consultable',
            ],
            ActionMakerDto::CODIR => [
                self::STATE =>  WorkflowData::STATE_CODIR,
                self::TITLE => 'Au CODIR',
            ],
            ActionMakerDto::CODIR_WRITABLE => [
                self::STATE =>  WorkflowData::STATE_CODIR,
                self::TITLE => '<strong>Au CODIR</strong>',
            ],
            ActionMakerDto::CODIR_READABLE => [
                self::STATE =>  WorkflowData::STATE_CODIR,
                self::TITLE => '<strong>Au CODIR</strong> consultable',
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
                self::TITLE => '<strong>Déployé</strong>',
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
                self::TITLE => '<strong>A mesurer</strong>',
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


        return $this->getArray($datas[$filter], $filter);
    }

    public function __construct(
        ActionDtoRepository $actionDtoRepository,
        CurrentUser $currentUser
    ) {
        $this->counter = new ActionCounter($actionDtoRepository, $currentUser);
    }

    private function getArray($datas, $filter)
    {
        $ib = new WidgetInfoBox();
        return $ib
            ->setRoute('actions_' . $filter)
            ->setRouteOptions(key_exists(self::ROUTE_OPTIONS, $datas) ? $datas[self::ROUTE_OPTIONS] : null)
            ->setBgColor(array_keys($datas, self::STATE) ?  WorkflowData::getBGColorOfState($datas[self::STATE]) : "")
            ->setForeColor(array_keys($datas, self::STATE) ? WorkflowData::getForeColorOfState($datas[self::STATE]) : "")
            ->setIcone(
                array_keys($datas, self::STATE) ?
                WorkflowData::getIconOfState($datas[self::STATE]) :"fas fa-bolt"
            )
            ->setTitle($datas[self::TITLE])
            ->setData($this->counter->get($filter))
            ->createArray();
    }
}
