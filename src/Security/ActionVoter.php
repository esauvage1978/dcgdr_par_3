<?php

namespace App\Security;

use App\Entity\Action;
use App\Entity\User;
use App\Workflow\WorkflowData;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ActionVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'read';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Action) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Action $action */
        $action = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($action, $user);
            case self::UPDATE:
                return $this->canUpdate($action, $user);
            case self::DELETE:
                return $this->canDelete($action, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Action $action, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        if ($action->getIsShowAll()) {
            return true;
        }

        foreach ($action->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return $this->canUpdate($action, $user);
    }

    public function canDelete(Action $action, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        return false;
    }

    public function canUpdate(Action $action, User $user)
    {
        if ($action->getCategory()->getThematique()->getPole()->getAxe()->getIsArchiving()) {
            return false;
        }
        
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }
        
        if (in_array($action->getStateCurrent(), WorkflowData::STATES_ACTION_UPDATE_BY_PILOTES)) {
            foreach ($action->getWriters() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }
        }
        
        if (in_array($action->getStateCurrent(), WorkflowData::STATES_ACTION_UPDATE_BY_COTECH)) {
            foreach ($action->getCOTECHValiders() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }
        }

        if (in_array($action->getStateCurrent(), WorkflowData::STATES_ACTION_UPDATE_BY_CODIR)) {
            foreach ($action->getCODIRValiders() as $corbeille) {
                if (in_array($user, $corbeille->getUsers()->toArray())) {
                    return true;
                }
            }
        }
        return false;
    }

}
