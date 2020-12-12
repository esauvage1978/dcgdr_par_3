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
 
}