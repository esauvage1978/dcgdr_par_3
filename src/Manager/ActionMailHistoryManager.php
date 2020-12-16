<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\ActionMailHistoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class ActionMailHistoryManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, ActionMailHistoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {

    }
}
