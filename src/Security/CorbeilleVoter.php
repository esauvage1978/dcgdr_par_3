<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Corbeille;
use App\Workflow\WorkflowData;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CorbeilleVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'read';
    const DELETE = 'delete';
    const CREATE = 'create';

    private $security;



    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [
            self::READ,
            self::UPDATE,
            self::CREATE,
            self::DELETE,
        ])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Corbeille) {
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

        /** @var Corbeille $corbeille */
        $corbeille = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($corbeille, $user);
            case self::UPDATE:
                return $this->canUpdate($corbeille, $user);
            case self::DELETE:
                return $this->canDelete($corbeille, $user);
            case self::CREATE:
                return $this->canCreate( $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Corbeille $corbeille, User $user)
    {
        return $this->canUpdate($corbeille, $user);
    }

    public function canCreate(User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        if (Role::isGestionnaireLocal($user)) {
            return true;
        }

        return false;
    }

    public function canUpdate(Corbeille $corbeille, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }


        if (Role::isGestionnaireLocal($user) ) {
            foreach($user->getOrganismes() as $organisme) {
                if ($corbeille->getOrganisme()==$organisme) {
                    return true;
                }
            }
        }

        return false;
    }

    public function canDelete(Corbeille $corbeille, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        return $this->canUpdate($corbeille, $user);
    }

}
