<?php

namespace App\Twig;

use App\Entity\Action;
use App\Entity\User;
use App\Security\ActionVoter;
use App\Security\CurrentUser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ActionVoterExtension extends AbstractExtension
{
    /**
     * @var ActionVoter
     */
    private $actionVoter;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        CurrentUser $currentUser,
        ActionVoter $actionVoter
    ) {
        $this->currentUser = $currentUser;
        $this->actionVoter=$actionVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('actionCanRead', [$this, 'actionCanRead']),
            new TwigFilter('actionCanUpdate', [$this, 'actionCanUpdate']),
            new TwigFilter('actionCanDelete', [$this, 'actionCanDelete']),
        ];
    }

    public function actionCanRead(Action $action)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->actionVoter->canRead($action, $user);
    }

    public function actionCanUpdate(Action $action)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->actionVoter->canUpdate($action, $user);
    }

    public function actionCanDelete(Action $action)
    {
        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->actionVoter->canDelete($action, $user);
    }
}
