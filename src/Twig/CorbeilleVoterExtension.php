<?php

namespace App\Twig;

use App\Entity\Corbeille;
use App\Entity\User;
use App\Security\CorbeilleVoter;
use App\Security\CurrentUser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CorbeilleVoterExtension extends AbstractExtension
{
    /**
     * @var CorbeilleVoter
     */
    private $corbeilleVoter;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        CurrentUser $currentUser,
        CorbeilleVoter $corbeilleVoter
    ) {
        $this->currentUser = $currentUser;
        $this->corbeilleVoter=$corbeilleVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('corbeilleCanRead', [$this, 'corbeilleCanRead']),
            new TwigFilter('corbeilleCanUpdate', [$this, 'corbeilleCanUpdate']),
            new TwigFilter('corbeilleCanCreate', [$this, 'corbeilleCanCreate']),
            new TwigFilter('corbeilleCanDelete', [$this, 'corbeilleCanDelete']),
        ];
    }

    public function corbeilleCanRead(Corbeille $corbeille)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->corbeilleVoter->canRead($corbeille, $user);
    }

    public function corbeilleCanUpdate(Corbeille $corbeille)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->corbeilleVoter->canUpdate($corbeille, $user);
    }

    public function corbeilleCanCreate()
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->corbeilleVoter->canCreate($user);
    }

    public function corbeilleCanDelete(Corbeille $corbeille)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->corbeilleVoter->canDelete($corbeille, $user);
    }
}
