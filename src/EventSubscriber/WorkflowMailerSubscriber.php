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
            WorkflowData::STATE_COTECH,
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


        $stateForPilote = [
            WorkflowData::STATE_COTECH,
            WorkflowData::STATE_CODIR,
            WorkflowData::STATE_REJECTED,
            WorkflowData::STATE_FINALISED,
            WorkflowData::STATE_DEPLOYED,
            WorkflowData::STATE_MEASURED,
            WorkflowData::STATE_CLOTURED,
            WorkflowData::STATE_ABANDONNED,

        ];


        if (in_array($state, $stateForPilote)) {
            $this->getUserForPilote($action);
        }
        if ($state === WorkflowData::STATE_COTECH) {
            $this->getUsersCOTECH($action);
        }
        if ($state === WorkflowData::STATE_CODIR) {
            $this->getUsersCODIR($action);
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

    public function getUserForPilote(Action $action)
    {
        foreach ($action->getWriters() as $corbeille) {
            $this->addUsers($corbeille->getUsers());
        }
    }
    public function getUsersCOTECH(Action $action)
    {
        foreach ($action->getCOTECHValiders() as $corbeille) {
            $this->addUsers($corbeille->getUsers());
        }
    }
    public function getUsersCODIR(Action $action)
    {
        foreach ($action->getCODIRValiders() as $corbeille) {
            $this->addUsers($corbeille->getUsers());
        }
    }

    public function getUsersControl()
    {
        $this->addUsers($this->userRepository->findAllForControl());
    }

    public function getUsersDoc()
    {
        $this->addUsers($this->userRepository->findAllForDoc());
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
