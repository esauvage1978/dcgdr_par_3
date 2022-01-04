<?php

namespace App\Manager;

use App\Entity\ActionFile;

use App\Security\CurrentUser;
use App\Helper\ContentChecker;
use App\Entity\EntityInterface;
use App\History\ActionHistory;
use App\Manager\ActionManager;
use App\Validator\ActionFileValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ActionFileRepository;


class ActionFileManager extends AbstractManager
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var ActionHistory
     */
    private $actionHistory;

    /**
     * @var ActionFileRepository
     */
    private $actionFileRepository;

    /**
     * @var ActionManager
     */
    private $actionManager;

    public function __construct(
        EntityManagerInterface $manager,
        ActionFileValidator $validator,
        CurrentUser $currentUser,
        ActionHistory $actionHistory,
        ActionFileRepository $actionFileRepository,
        ActionManager $actionManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->actionHistory = $actionHistory;
        $this->actionFileRepository = $actionFileRepository;
        $this->actionManager = $actionManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());

        
        $entity->setContent( ContentChecker::run($entity->getContent()) );
    }

    public function historize(ActionFile $entity, ?ActionFile $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->actionHistory->setHistoryRelation($entity->getAction(),'Fichier');
            $this->actionHistory->compareFile($entityOld, $entity);
        }
    }

    public function save(EntityInterface $entity): bool
    {
        $this->initialise($entity);

        if (!$this->validator->isValid($entity)) {
            return false;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        $this->actionManager->save($entity->getAction());

        return true;
    }

    public function remove(EntityInterface $entity): void
    {
        $this->actionHistory->setHistoryRelation($entity->getAction(),'Fichier');
        $this->actionHistory->compareFile( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
