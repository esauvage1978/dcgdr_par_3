<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\CategoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, CategoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
