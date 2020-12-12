<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\AxeValidator;
use Doctrine\ORM\EntityManagerInterface;

class AxeManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, AxeValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
