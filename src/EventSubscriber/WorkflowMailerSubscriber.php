<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Action;
use App\Mail\ActionMail;
use App\Workflow\WorkflowData;
use App\Helper\ParamsInServices;
use App\Repository\UserRepository;
use App\Entity\ActionMailHistory;
use App\Event\WorkflowTransitionEvent;
use App\Repository\ActionRepository;
use App\Manager\ActionMailHistoryManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WorkflowMailerSubscriber implements EventSubscriberInterface
{
    /**
     * @var ActionMail
     */
    private $actionMail;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    /**
     * @var ActionMailHistoryManager
     */
    private $actionMailHistoryManager;


    /**
     * @var UserRepository
     */
    private $userRepository;

    private $users;

    public function __construct(
        ActionMail $actionMail,
        ActionRepository $actionRepository,
        ParamsInServices $paramsInServices,
        ActionMailHistoryManager $actionMailHistoryManager,
        UserRepository $userRepository
    ) {
        $this->actionMail = $actionMail;
        $this->actionRepository = $actionRepository;
        $this->paramsInServices = $paramsInServices;
        $this->users = new ArrayCollection();
        $this->actionMailHistoryManager = $actionMailHistoryManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            WorkflowTransitionEvent::NAME => 'onWorklowTransitionEvent',
        ];
    }

    public function onWorklowTransitionEvent(WorkflowTransitionEvent $event): int
    {

        /** @var Action $action */
        $action = $event->getAction();
        /** @var string $state */
        $state = $action->getStateCurrent();


        $mailState = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_TO_CONTROL,
            WorkflowData::STATE_TO_CHECK,
            WorkflowData::STATE_PUBLISHED,
            WorkflowData::STATE_TO_REVISE,
            WorkflowData::STATE_IN_REVIEW,
        ];

        if (in_array($state, $mailState)) {
            $this->sendMailForAction($action, $state);
        }
        return 0;
    }

    private function sendMailForAction(Action $action, string $state)
    {
        if (!$this->checkMailForState($state)) {
            return -1;
        }


        $stateOwner = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_TO_CONTROL,
            WorkflowData::STATE_TO_CHECK,
            WorkflowData::STATE_PUBLISHED,
            WorkflowData::STATE_TO_REVISE,
            WorkflowData::STATE_IN_REVIEW,
        ];

        $stateForContributor = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_REVISE,
            WorkflowData::STATE_IN_REVIEW,
        ];

        $stateForValidator = [
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_PUBLISHED,
        ];


        if (in_array($state, $stateOwner)) {
            $this->getOwner($action);
        }
        if (in_array($state, $stateForValidator)) {
            $this->getUserForValidator($action);
        }
        if (in_array($state, $stateForContributor)) {
            $this->getUserForContributor($action);
        }
        if ($state === WorkflowData::STATE_TO_CONTROL) {
            $this->getUsersControl();
        }
        if ($state === WorkflowData::STATE_TO_CHECK) {
            $this->getUsersDoc();
        }


        if ($this->users->isEmpty()) {
            return -1;
        }

        $this->saveHistoryOfMail($action);

        return $this->actionMail->sendForUsers(
            $this->users,
            $action,
            $state,
            WorkflowData::getTitleOfMail($state)
        );
    }

    private function saveHistoryOfMail(Action $action)
    {
        $content = 'Notification lors du changement d\'état aux adresses suivantes :<br/><ul> ';

        foreach ($this->users as $user) {
            $content = $content . '<li>' . $user->getName() . ' (' . $user->getEmail() . ')</li>';
        }
        $content = $content . '</ul>';
        $actionMailHistory = new ActionMailHistory();
        $actionMailHistory
            ->setAction($action)
            ->setContent($content)
            ->setSendAt(new \DateTime());
        $this->actionMailHistoryManager->save($actionMailHistory);
    }

    private function checkMailForState(string $state): bool
    {
        $parameter = 'es.mailer.workflow.' . $state;
        return $this->paramsInServices->get($parameter);
    }

    public function getUserMprocessValider(Action $action)
    {
        $this->addUsers($action->getMProcess()->getDirValidators());
    }
    public function getOwner(Action $action)
    {

        $this->addUser($action->getOwner());
    }

    public function getUsersControl()
    {
        $this->addUsers($this->userRepository->findAllForControl());
    }

    public function getUsersDoc()
    {
        $this->addUsers($this->userRepository->findAllForDoc());
    }

    public function getUserForValidator(Action $action)
    {
        if ($action->getProcess() !== null) {
            $this->addUsers($action->getProcess()->getValidators());
        } elseif ($action->getCategory()->getIsValidatedByADD()) {
            $this->addUsers($action->getMProcess()->getDirValidators());
        } else {
            $this->addUsers($action->getMProcess()->getPoleValidators());
        }
    }

    public function getUserForContributor(Action $action)
    {
        if ($action->getProcess() !== null) {
            $this->addUsers($action->getProcess()->getContributors());
        } else {
            $this->addUsers($action->getMProcess()->getContributors());
        }
    }

    private function addUser(User $user)
    {
        if ($user->getIsEnable()) {
            if (!$this->users->contains($user)) {
                $this->users[] = $user;
            }
        }
    }

    private function addUsers($users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }
}
