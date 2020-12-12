<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\VecteurValidator;
use Doctrine\ORM\EntityManagerInterface;

class VecteurManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, VecteurValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
