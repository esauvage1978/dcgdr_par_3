<?php


namespace App\Workflow;


class WorkflowData
{
    const STATE_STARTED = 'started';
    const STATE_COTECH = 'cotech';
    const STATE_CODIR = 'codir';
    const STATE_REJECTED = 'rejected';
    const STATE_FINALISED = 'finalised';
    const STATE_DEPLOYED = 'deployed';
    const STATE_MEASURED = 'measured';
    const STATE_CLOTURED = 'clotured';
    const STATE_ABANDONNED = 'abandonned';

    const TRANSITION_TO_STARTED = 'toStarted';
    const TRANSITION_TO_COTECH = 'toCotech';
    const TRANSITION_TO_CODIR = 'toCodir';
    const TRANSITION_TO_REJECTED = 'toRejected';
    const TRANSITION_TO_FINALISED = 'toFinalised';
    const TRANSITION_TO_DEPLOYE = 'toDeploye';
    const TRANSITION_TO_MEASURED = 'toMeasured';
    const TRANSITION_TO_CLOTURED = 'toClotured';
    const TRANSITION_UN_DEPLOYED = 'unDeployed';
    const TRANSITION_UN_MEASURED = 'unMeasured';
    const TRANSITION_UN_CLOTURED = 'unClotured';
    const TRANSITION_TO_ABANDONNED = 'toAbandonned';

    const STATES_ACTION_UPDATE_PILOTES=['started','rejected','abandonned','deployed','measured','clotured'];
    const STATES_ACTION_UPDATE_VALIDER=['cotech','codir'];

    const STATES_DEPLOYEMENT_UPDATE=['started','finalised'];
    const STATES_DEPLOYEMENT_READ=['deployed','measured','clotured','abandonned'];
    const STATES_DEPLOYEMENT_APPEND=['deployed'];

    private const NAME = 'name';
    private const ICON = 'icon';
    private const TITLE_MAIL = 'title_mail';
    private const BGCOLOR = 'bgcolor';
    private const FORECOLOR = 'forecolor';
    private const TRANSITIONS = 'transitions';

    private static function getStates(): array
    {
        return [
            self::STATE_STARTED =>
            [
                self::NAME => ' 0. Action proposée',
                self::ICON => 'fab fa-firstdraft',
                self::TITLE_MAIL => ' Une action est revenue à l\'état "action proposée',
                self::BGCOLOR => '#440155',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::TRANSITION_TO_COTECH,
                    self::TRANSITION_TO_ABANDONNED
                ]
            ],
            self::STATE_COTECH =>
            [
                self::NAME => ' 1. COTECH',
                self::ICON => 'fab fa-firstdraft',
                self::TITLE_MAIL => ' Une action est au COTECH',
                self::BGCOLOR => '#5b0570',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::TRANSITION_TO_CODIR,
                    self::TRANSITION_TO_ABANDONNED
                ]
            ],
            self::STATE_ABANDONNED =>
            [
                self::NAME => ' Abandonné',
                self::ICON => 'far fa-trash-alt',
                self::TITLE_MAIL => ' Un porte-document est abandonné',
                self::BGCOLOR => '#AA0C0C',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                        self::TRANSITION_TO_STARTED
                ]
            ],
        ];
    }

    public static function hasTransition(string $data): bool
    {
        $datas = [
            self::TRANSITION_TO_STARTED,
            self::TRANSITION_TO_COTECH,
            self::TRANSITION_TO_ABANDONNED,
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        return false;
    }

    public static function hasState(string $data): bool
    {
        $datas = [
            self::STATE_STARTED,
            self::STATE_COTECH,
            self::STATE_ABANDONNED,
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        throw new \InvalidArgumentException('cet état n\'existe pas : ' . $data);
    }
    public static function getNameOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME);
    }

    public static function getIconOfState(string $state)
    {
        return self::getStatesValue($state, self::ICON);
    }

    public static function getTitleOfMail(string $state)
    {
        return self::getStatesValue($state, self::TITLE_MAIL);
    }

    public static function getShortNameOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME);
    }

    public static function getBGColorOfState(string $state)
    {
        return self::getStatesValue($state, self::BGCOLOR);
    }
    public static function getForeColorOfState(string $state)
    {
        return self::getStatesValue($state, self::FORECOLOR);
    }

    private static function  getStatesValue($state, $data)
    {
        if (!self::hasState($state)) {
            throw new \InvalidArgumentException('cet état n\'existe pas : ' . $state);
        }
        return self::getStates()[$state][$data];
    }

    public static function getTransitionsForState( $state)
    {
        return self::getStatesValueForWorkfow($state, self::TRANSITIONS);
    }

    private static function  getStatesValueForWorkfow( $state, $data)
    {
        if (!self::hasState($state)) {
            throw new \InvalidArgumentException('cet état n\'existe pas : ' . $state);
        }
            return self::getStates()[$state][$data];
    }
    public static function getModalDataForTransition(string $transition)
    {
        if (!self::hasTransition($transition)) {
            throw new \InvalidArgumentException('Cette transition n\'existe pas : ' . $transition);
        }
        $data = [
            'state' => '',
            'transition' => $transition,
            'titre' => '',
            'btn_label' => ''
        ];

        switch ($transition) {
            case self::TRANSITION_TO_STARTED:
                $data['state'] = self::STATE_STARTED;
                $data['titre'] = 'Mettre à la validation hiérarchique';
                $data['btn_label'] = 'A valider';
                break;
            case self::TRANSITION_TO_COTECH:
                $data['state'] = self::STATE_COTECH;
                $data['titre'] = 'Mettre à la validation du COTECH';
                $data['btn_label'] = 'COTECH';
                break;                
            case self::TRANSITION_TO_ABANDONNED:
                $data['state'] = self::STATE_ABANDONNED;
                $data['titre'] = 'Abandonner le porte-document';
                $data['btn_label'] = 'A abandonner';
                break;
        }

        return $data;
    }
}