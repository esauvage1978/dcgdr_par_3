<?php

namespace App\Manager;

use App\Entity\Action;
use App\Entity\EntityInterface;
use App\Validator\ActionValidator;
use Doctrine\ORM\EntityManagerInterface;

class ActionManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, ActionValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Action 
         * */
        $entity=$entity;
        foreach ($entity->getActionFiles() as $actionFile)
        {
            $actionFile->setAction($entity);
        }

        foreach ($entity->getActionLinks() as $actionLink)
        {
            $actionLink->setAction($entity);
        }

        foreach ($entity->getCadrageFiles() as $cadrageFile)
        {
            $cadrageFile->setAction($entity);
        }

        foreach ($entity->getCadrageLinks() as $cadrageLink)
        {
            $cadrageLink->setAction($entity);
        }
    }

}
