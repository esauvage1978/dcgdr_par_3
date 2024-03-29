<?php

namespace App\Security;

use App\Entity\Deployement;
use App\Entity\User;
use App\Workflow\WorkflowData;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class DeployementVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'read';
    const DELETE = 'delete';
    const APPEND_READ = 'append_read';
    const APPEND_UPDATE = 'append_update';

    private $security;

    /**
     * @var ActionVoter
     */
    private $actionVoter;

    public function __construct(Security $security, ActionVoter $actionVoter)
    {
        $this->security = $security;
        $this->actionVoter = $actionVoter;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [
            self::READ,
            self::UPDATE,
            self::DELETE,
            self::APPEND_READ,
            self::APPEND_UPDATE,
        ])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Deployement) {
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

        /** @var Deployement $deploiement */
        $deploiement = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($deploiement, $user);
            case self::UPDATE:
                return $this->canUpdate($deploiement, $user);
            case self::APPEND_READ:
                return $this->canAppendRead($deploiement, $user);
            case self::APPEND_UPDATE:
                return $this->canAppendUpdate($deploiement, $user);
            case self::DELETE:
                return $this->canDelete($deploiement, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Deployement $deploiement, User $user)
    {
        return $this->actionVoter->canUpdate($deploiement->getAction(), $user);
    }

    public function canUpdate(Deployement $deploiement, User $user)
    {
        if (!in_array($deploiement->getAction()->getStateCurrent(), WorkflowData::STATES_DEPLOYEMENT_UPDATE)) {
            return false;
        }

        return $this->actionVoter->canUpdate($deploiement->getAction(), $user);
    }

    public function canDelete(Deployement $deploiement, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        return $this->actionVoter->canUpdate($deploiement->getAction(), $user);
    }

    public function canAppendRead(Deployement $deploiement, User $user)
    {
        if (!in_array($deploiement->getAction()->getStateCurrent(), WorkflowData::STATES_DEPLOYEMENT_READ)) {
            return false;
        }

        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        foreach ($deploiement->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        if ($this->actionVoter->canUpdate($deploiement->getAction(), $user)) {
            return true;
        }


        return $this->canAppendUpdate($deploiement, $user);
    }

    public function canAppendUpdate(Deployement $deploiement, User $user)
    {
        if (!in_array($deploiement->getAction()->getStateCurrent(), WorkflowData::STATES_DEPLOYEMENT_UPDATE) && 
        !in_array($deploiement->getAction()->getStateCurrent(), WorkflowData::STATES_DEPLOYEMENT_APPEND)) {
            return false;
        }

        if ($deploiement->getAction()->getCategory()->getThematique()->getPole()->getAxe()->getIsArchiving()) {
            return false;
        }

        if ($this->security->isGranted('ROLE_GES_LOCAL')) {
            foreach ($user->getOrganismes() as $organisme) {
                if ($organisme->getId() == $deploiement->getOrganisme()->getId()) {
                    return true;
                }
            }
        }

        foreach ($deploiement->getWriters() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return $this->actionVoter->canUpdate($deploiement->getAction(), $user);
    }
}
