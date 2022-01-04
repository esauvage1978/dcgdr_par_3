<?php

namespace App\Manager;

use App\Entity\CadrageLink;

use App\Security\CurrentUser;
use App\Helper\ContentChecker;
use App\Entity\EntityInterface;
use App\History\ActionHistory;
use App\Validator\CadrageLinkValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CadrageLinkRepository;


class CadrageLinkManager extends AbstractManager
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
     * @var CadrageLinkRepository
     */
    private $actionLinkRepository;


    /**
     * @var ActionManager
     */
    private $actionManager;

    public function __construct(
        EntityManagerInterface $manager,
        CadrageLinkValidator $validator,
        CurrentUser $currentUser,
        ActionHistory $actionHistory,
        CadrageLinkRepository $actionLinkRepository,
        ActionManager $actionManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->actionHistory = $actionHistory;
        $this->actionLinkRepository = $actionLinkRepository;
        $this->actionManager = $actionManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());

        $entity->setContent( ContentChecker::run($entity->getContent()) );
    }

    public function historize(CadrageLink $entity, ?CadrageLink $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->actionHistory->setHistoryRelation($entity->getAction(),'Lien du cadrage');
            $this->actionHistory->compareLink($entityOld, $entity);
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
        $this->actionHistory->setHistoryRelation($entity->getAction(),'Lien du cadrage');
        $this->actionHistory->compareLink( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
