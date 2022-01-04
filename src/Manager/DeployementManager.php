<?php

namespace App\Manager;

use App\Entity\Action;
use App\Entity\Organisme;
use App\Entity\Deployement;
use App\Entity\EntityInterface;
use App\History\DeployementHistory;
use App\Validator\DeployementValidator;
use Doctrine\ORM\EntityManagerInterface;

class DeployementManager extends AbstractManager
{
    /**
     * @var DeployementHistory
     */
    private $deployementHistory;


    public function __construct(
        EntityManagerInterface $manager,
        DeployementValidator $validator,
        DeployementHistory $deployementHistory
    ) {
        parent::__construct($manager, $validator);
        $this->deployementHistory = $deployementHistory;
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Deployement
         */
        $entity = $entity;
        foreach ($entity->getDeployementFiles() as $deployementFile) {
            $deployementFile->setDeployement($entity);
        }

        foreach ($entity->getDeployementLinks() as $deployementLink) {
            $deployementLink->setDeployement($entity);
        }
    }

    public function createDeployement(
        Deployement $deployement,
        Action $action,
        Organisme $organisme,
        array $corbeilles
    ) {
        /** @var Organisme $organisme */
        $deployement
            ->setAction($action)
            ->setOrganisme($organisme);

        foreach ($corbeilles as $corbeille) {
            $deployement->addWriter($corbeille);
        }

        $this->save($deployement);

        return $deployement;
    }

    public function historize(Deployement $entity, ?Deployement $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->deployementHistory->setHistoryRelation($entity, 'Déploiement');
            $this->deployementHistory->compare($entityOld, $entity);
        }
    }
}
