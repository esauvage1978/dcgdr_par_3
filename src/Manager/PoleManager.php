<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\PoleValidator;
use Doctrine\ORM\EntityManagerInterface;

class PoleManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, PoleValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
