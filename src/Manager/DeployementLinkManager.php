<?php

namespace App\Manager;

use App\Entity\DeployementLink;

use App\Security\CurrentUser;
use App\Helper\ContentChecker;
use App\Entity\EntityInterface;
use App\History\DeployementHistory;
use App\Validator\DeployementLinkValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DeployementLinkRepository;


class DeployementLinkManager extends AbstractManager
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
     * @var DeployementLinkRepository
     */
    private $deployementLinkRepository;


    /**
     * @var DeployementManager
     */
    private $deployementManager;

    public function __construct(
        EntityManagerInterface $manager,
        DeployementLinkValidator $validator,
        CurrentUser $currentUser,
        DeployementHistory $deployementHistory,
        DeployementLinkRepository $deployementLinkRepository,
        DeployementManager $deployementManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->deployementHistory = $deployementHistory;
        $this->deployementLinkRepository = $deployementLinkRepository;
        $this->deployementManager = $deployementManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());

        $entity->setContent( ContentChecker::run($entity->getContent()) );
    }

    public function historize(DeployementLink $entity, ?DeployementLink $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->deployementHistory->setHistoryRelation($entity->getDeployement(),'Lien');
            $this->deployementHistory->compareLink($entityOld, $entity);
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
        $this->deployementHistory->setHistoryRelation($entity->getDeployement(),'Lien');
        $this->deployementHistory->compareLink( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
