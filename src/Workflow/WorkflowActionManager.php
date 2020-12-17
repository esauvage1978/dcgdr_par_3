<?php

namespace App\Workflow;

use App\Entity\User;
use App\Entity\Action;
use App\Security\CurrentUser;
use App\Repository\UserRepository;
use App\Manager\ActionStateManager;
use App\Event\WorkflowTransitionEvent;
use App\Repository\ActionRepository;
use Symfony\Component\Workflow\Registry;
use App\Manager\ActionDuplicatorManager;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class WorkflowActionManager
{
    /**
     * @var ActionStateManager
     */
    private $actionStateManager;

    /**
     * @var Registry
     */
    private $workflow;
    /**
     * @var StateMachine
     */
    private $stateMachine;

    /**
     * @var WorkflowActionTransitionManager
     */
    private $workflowActionTransitionManager;

    /**
     * @var Security
     */
    private $currentUser;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    public function __construct(
        ActionStateManager $actionStateManager,
        Registry $workflow,
        CurrentUser $currentUser,
        EventDispatcherInterface $dispatcher,
        UserRepository $userRepository,
        ActionRepository $actionRepository
    ) {
        $this->actionStateManager = $actionStateManager;
        $this->currentUser = $currentUser;
        $this->workflow = $workflow;
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
        $this->actionRepository= $actionRepository;
    }

    private function initialiseStateMachine(Action $item)
    {
        if (null == $this->stateMachine) {
            $this->stateMachine = $this->workflow->get($item);
        }
    }

    public function applyTransition(Action $item, string $transition, string $content, bool $automate = false)
    {
        $stateOld = $item->getStateCurrent();

        $this->initialiseStateMachine($item);

        if ($this->stateMachine->can($item, $transition)) {
            $this->apply_change_state($item, $transition, $automate, $content);

            $user = $this->loadUser($automate);

            
            $this->historisation($user, $item, $stateOld);
            
            
            $this->send_mails($user, $item, $automate);

            return true;
        } else {
            dump('ERROR ' . $item->getId() . ' can:not. Current state : ' . $item->getStateCurrent() . '. Transition : ' . $transition);
            return false;
        }

        return false;
    }

    private function apply_change_state(Action $item, string $transition, bool $automate, string $content)
    {
        $this->workflowActionTransitionManager = new WorkflowActionTransitionManager($item, $this->actionRepository, $transition);
        $this->workflowActionTransitionManager->intialiseActionForTransition($content, $automate);
        $this->stateMachine->apply($item, $transition);
    }

    private function send_mails(User $user, Action $item, bool $automate)
    {
        if (!$automate) {
            $event = new WorkflowTransitionEvent($user, $item);
            $this->dispatcher->dispatch($event, WorkflowTransitionEvent::NAME);
        }
    }

    private function historisation(User $user, Action $item, string $stateOld)
    {
        $this->actionStateManager->saveActionInHistory($item, $stateOld, $user);
    }


    private function loadUser(bool $automate)
    {
        if (!$automate) {
            return $this->currentUser->getUser();
        } else {
            return $this->userRepository->findAll()[0];
        }
    }
}
