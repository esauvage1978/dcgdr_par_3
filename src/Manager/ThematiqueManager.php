<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\ThematiqueValidator;
use Doctrine\ORM\EntityManagerInterface;

class ThematiqueManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, ThematiqueValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
