<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\CibleValidator;
use Doctrine\ORM\EntityManagerInterface;

class CibleManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, CibleValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
