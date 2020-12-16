<?php

namespace App\Manager;

use App\Entity\Action;
use App\Entity\ActionState;
use App\Entity\EntityInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\ActionStateValidator;
use Doctrine\ORM\EntityManagerInterface;

class ActionStateManager extends AbstractManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $manager,
        ActionStateValidator $validator,
        UserRepository $userRepository
    ) {
        parent::__construct($manager, $validator);
        $this->userRepository = $userRepository;

    }

    public function saveActionInHistory(Action $item,string $initial_state, User $user)
    {

        $actionState = new ActionState();
        $actionState
            ->setUser($user)
            ->setAction($item)
            ->setContent($item->getStateContent())
            ->setChangeAt(new \DateTime())
            ->setStateOld($initial_state)
            ->setStateNew($item->getStateCurrent());

        $this->save($actionState);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
