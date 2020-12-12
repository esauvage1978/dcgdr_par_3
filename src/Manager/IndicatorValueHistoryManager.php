<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Validator\IndicatorValueHistoryValidator;

class IndicatorValueHistoryManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, IndicatorValueHistoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
