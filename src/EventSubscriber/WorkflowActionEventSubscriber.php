<?php

namespace App\EventSubscriber;

use App\Entity\Action;
use App\Workflow\WorkflowData;
use App\Repository\ActionRepository;
use Symfony\Component\Workflow\Event\GuardEvent;
use App\Workflow\WorkflowActionTransitionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ActionSubscriber.
 */
class WorkflowActionEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var ActionRepository
     */
    private $actionRepository;
    
    public function __construct(ActionRepository $actionRepository)
    {
        $this->actionRepository= $actionRepository;
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardToAbandonned(GuardEvent $event) 
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_ABANDONNED);
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardToCotech(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_COTECH);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardToCodir(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_CODIR);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardToRejected(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_REJECTED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardToFinalised(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_FINALISED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardToDeployed(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_DEPLOYED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardToMeasured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_MEASURED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardToClotured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_CLOTURED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardUnDeployed(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_UN_DEPLOYED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardUnMeasured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_UN_MEASURED);
    }
    /**
     * @param GuardEvent $event
     */
    public function onGuardUnClotured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_UN_CLOTURED);
    }

    private function onGuard(GuardEvent $event, string $transition)
    {
        /** @var Action $action */
        $action = $event->getSubject();
        $workflowActionTransitionManager = new WorkflowActionTransitionManager(
            $action,
            $this->actionRepository,
            $transition
        );
        if (!$workflowActionTransitionManager->can()) {
            $event->setBlocked(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.wkf_all.guard.toAbandonned' => ['onGuardToAbandonned'],
            'workflow.wkf_all.guard.toCotech' => ['onGuardtoCotech'],
            'workflow.wkf_all.guard.toCodir' => ['onGuardToCodir'],
            'workflow.wkf_all.guard.toRejected' => ['onGuardToRejected'],
            'workflow.wkf_all.guard.toFinalised' => ['onGuardToFinalised'],
            'workflow.wkf_all.guard.toDeployed' => ['onGuardToDeployed'],
            'workflow.wkf_all.guard.toMeasured' => ['onGuardToMeasured'],
            'workflow.wkf_all.guard.toClotured' => ['onGuardToClotured'],
            'workflow.wkf_all.guard.unDeployed' => ['onGuardUnDeployed'],
            'workflow.wkf_all.guard.unMeasured' => ['onGuardUnMeasured'],
            'workflow.wkf_all.guard.unClotured' => ['onGuardUnClotured'],
        ];
    }
}
