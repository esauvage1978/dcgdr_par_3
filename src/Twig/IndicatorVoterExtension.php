<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use App\Entity\Indicator;
use App\Security\CurrentUser;
use App\Security\IndicatorVoter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;

class IndicatorVoterExtension extends AbstractExtension
{
    /**
     * @var IndicatorVoter
     */
    private $indicatorVoter;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        CurrentUser $currentUser,
        IndicatorVoter $indicatorVoter
    )
    {
        $this->currentUser = $currentUser;
        $this->indicatorVoter = $indicatorVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('indicatorCanUpdate', [$this, 'indicatorCanUpdate']),
        ];
    }

    public function indicatorCanUpdate(Indicator $indicator)
    {
        if( $indicator->getAction()->getCategory()->getThematique()->getPole()->getAxe()->getIsArchiving()) {
            return false;
        }

        /** @var User $user */
        $user = $this->currentUser->getUser();

        return $this->indicatorVoter->canUpdate($indicator, $user);
    }

}
