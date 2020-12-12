<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\IndicatorValidator;
use Doctrine\ORM\EntityManagerInterface;

class IndicatorManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, IndicatorValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
