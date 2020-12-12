<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use App\Entity\Deployement;
use App\Security\CurrentUser;
use App\Security\DeployementVoter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;

class DeployementVoterExtension extends AbstractExtension
{
    /**
     * @var DeployementVoter
     */
    private $deployementVoter;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        CurrentUser $currentUser,
        DeployementVoter $deployementVoter
    )
    {
        $this->currentUser = $currentUser;
        $this->deployementVoter = $deployementVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('deployementCanAppendRead', [$this, 'deployementCanAppendRead']),
            new TwigFilter('deployementCanAppendUpdate', [$this, 'deployementCanAppendUpdate']),
            new TwigFilter('deployementCanUpdate', [$this, 'deployementCanUpdate']),
            new TwigFilter('deployementCanDelete', [$this, 'deployementCanDelete']),
        ];
    }

    public function deployementCanAppendRead(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->deployementVoter->canAppendRead($deployement, $user);
    }

    public function deployementCanAppendUpdate(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->deployementVoter->canAppendUpdate($deployement, $user);
    }

    public function deployementCanUpdate(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->deployementVoter->canUpdate($deployement, $user);
    }

    public function deployementCanDelete(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->deployementVoter->canDelete($deployement, $user);
    }
}
