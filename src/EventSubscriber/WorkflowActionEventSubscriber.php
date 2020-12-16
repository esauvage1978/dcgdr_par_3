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
    private $ActionRepository;
    
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
    public function onGuardGoToResume(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_RESUME);
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardGoToRevise(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_REVISE);
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardGoInReview(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_IN_REVIEW);
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardGoToControl(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_CONTROL);
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardGoToCheck(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_CHECK);
    }


    /**
     * @param GuardEvent $event
     */
    public function onGuardGoToValidate(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_VALIDATE);
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
            'workflow.wkf_all.guard.goAbandonned' => ['onGuardToAbandonned'],
            'workflow.wkf_all.guard.goToResume' => ['onGuardToCotech'],
            'workflow.wkf_all.guard.goToValidate' => ['onGuardGoToValidate'],
            'workflow.wkf_all.guard.goToControl' => ['onGuardGoToControl'],
            'workflow.wkf_all.guard.goToCheck' => ['onGuardGoToCheck'],
            'workflow.wkf_all.guard.goPublished' => ['onGuardGoPublished'],
            'workflow.wkf_all.guard.goToRevise' => ['onGuardGoToRevise'],
            'workflow.wkf_all.guard.goInReview' => ['onGuardGoInReview'],
        ];
    }
}
