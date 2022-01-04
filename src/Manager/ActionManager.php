<?php

namespace App\Manager;

use App\Entity\Action;
use App\Security\CurrentUser;
use App\History\ActionHistory;
use App\Entity\EntityInterface;
use App\Validator\ActionValidator;
use Doctrine\ORM\EntityManagerInterface;

class ActionManager extends AbstractManager
{
    /**
     * @var ActionHistory
     */
    private $actionHistory;

    public function __construct(
        EntityManagerInterface $manager,
        CurrentUser $currentUser,
        ActionHistory $actionHistory,
        ActionValidator $validator
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->actionHistory = $actionHistory;
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Action 
         * */
        $entity = $entity;
        foreach ($entity->getActionFiles() as $actionFile) {
            $actionFile->setAction($entity);
        }

        foreach ($entity->getActionLinks() as $actionLink) {
            $actionLink->setAction($entity);
        }

        foreach ($entity->getCadrageFiles() as $cadrageFile) {
            $cadrageFile->setAction($entity);
        }

        foreach ($entity->getCadrageLinks() as $cadrageLink) {
            $cadrageLink->setAction($entity);
        }
    }

    public function historize(Action $entity, ?Action $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->actionHistory->setHistoryRelation($entity, 'Action');
            $this->actionHistory->compare($entityOld, $entity);
        }
    }
}
