<?php

namespace App\Manager;

use App\Entity\CadrageFile;

use App\Security\CurrentUser;
use App\Helper\ContentChecker;
use App\Entity\EntityInterface;
use App\History\ActionHistory;
use App\Manager\ActionManager;
use App\Validator\CadrageFileValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CadrageFileRepository;


class CadrageFileManager extends AbstractManager
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
     * @var CadrageFileRepository
     */
    private $cadrageFileRepository;

    /**
     * @var ActionManager
     */
    private $actionManager;

    public function __construct(
        EntityManagerInterface $manager,
        CadrageFileValidator $validator,
        CurrentUser $currentUser,
        ActionHistory $actionHistory,
        CadrageFileRepository $cadrageFileRepository,
        ActionManager $actionManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->actionHistory = $actionHistory;
        $this->cadrageFileRepository = $cadrageFileRepository;
        $this->actionManager = $actionManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());

        
        $entity->setContent( ContentChecker::run($entity->getContent()) );
    }

    public function historize(CadrageFile $entity, ?CadrageFile $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->actionHistory->setHistoryRelation($entity->getAction(),'Fichier de cadrage');
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
        $this->actionHistory->setHistoryRelation($entity->getAction(),'Fichier de cadrage');
        $this->actionHistory->compareFile( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
