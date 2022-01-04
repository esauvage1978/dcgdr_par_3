<?php

namespace App\Manager;

use App\Entity\DeployementFile;

use App\Security\CurrentUser;
use App\Helper\ContentChecker;
use App\Entity\EntityInterface;
use App\History\DeployementHistory;
use App\Manager\DeployementManager;
use App\Validator\DeployementFileValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DeployementFileRepository;


class DeployementFileManager extends AbstractManager
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var DeployementHistory
     */
    private $deployementHistory;

    /**
     * @var DeployementFileRepository
     */
    private $deployementFileRepository;

    /**
     * @var DeployementManager
     */
    private $deployementManager;

    public function __construct(
        EntityManagerInterface $manager,
        DeployementFileValidator $validator,
        CurrentUser $currentUser,
        DeployementHistory $deployementHistory,
        DeployementFileRepository $deployementFileRepository,
        DeployementManager $deployementManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->deployementHistory = $deployementHistory;
        $this->deployementFileRepository = $deployementFileRepository;
        $this->deployementManager = $deployementManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());

        
        $entity->setContent( ContentChecker::run($entity->getContent()) );
    }

    public function historize(DeployementFile $entity, ?DeployementFile $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->deployementHistory->setHistoryRelation($entity->getDeployement(),'Fichier');
            $this->deployementHistory->compareFile($entityOld, $entity);
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

        $this->deployementManager->save($entity->getDeployement());

        return true;
    }

    public function remove(EntityInterface $entity): void
    {
        $this->deployementHistory->setHistoryRelation($entity->getDeployement(),'Fichier');
        $this->deployementHistory->compareFile( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
