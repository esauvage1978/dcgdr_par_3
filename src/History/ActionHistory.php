<?php


namespace App\History;


use App\Entity\Action;
use App\Entity\EntityInterface;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
use Symfony\Component\Security\Core\Security;

class ActionHistory implements HistoryEntityInterface
{

    protected $history;

    public function __construct(
        History $history
    ) {
        $this->history = $history;
    }

    public function setHistoryRelation(EntityInterface $entity, string $domaine)
    {
        $historyEntity = $this->history->getHistoryRelationEntity()
            ->setAction($entity)
            ->setDomaine($domaine);
        $this->history->setHistoryRelationEntity($historyEntity);
    }

    public function compare(EntityInterface $itemOld, EntityInterface $itemNew)
    {
        /**
         * @var Action
         */
        $itemOld = $itemOld;
        /**
         * @var Action
         */
        $itemNew = $itemNew;

        $compare = [
            (new HistoryData())
                ->setTitle("Plan d'action")
                ->setOldData($itemOld->getCategory()->getThematique()->getPole()->getAxe())
                ->setNewData($itemNew->getCategory()->getThematique()->getPole()->getAxe())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Pôle")
                ->setOldData($itemOld->getCategory()->getThematique()->getPole())
                ->setNewData($itemNew->getCategory()->getThematique()->getPole())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Thématique")
                ->setOldData($itemOld->getCategory()->getThematique())
                ->setNewData($itemNew->getCategory()->getThematique())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Catégorie")
                ->setOldData($itemOld->getCategory())
                ->setNewData($itemNew->getCategory())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Nom")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Référence")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Ref"),
            (new HistoryData())
                ->setTitle("Cadrage")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("cadrage"),
            (new HistoryData())
                ->setTitle("Informations complémentaire")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("content"),
            (new HistoryData())
                ->setTitle("Début de déploiement")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("regionStartAt")
                ->setTypeOfData(HistoryData::TYPE_DATE),
            (new HistoryData())
                ->setTitle("Fin de déploiement")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("regionEndAt")
                ->setTypeOfData(HistoryData::TYPE_DATE),
            (new HistoryData())
                ->setTitle("Jalon")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("showAt")
                ->setTypeOfData(HistoryData::TYPE_DATE),
            (new HistoryData())
                ->setTitle("Expérimentation")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("isExperimental")
                ->setTypeOfData(HistoryData::TYPE_BOOL),
            (new HistoryData())
                ->setTitle("Vecteurs")
                ->setOldData($itemOld->getVecteurs())
                ->setNewData($itemNew->getVecteurs())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_MANY)
                ->setField("name"),
            (new HistoryData())
                ->setTitle("Cibles")
                ->setOldData($itemOld->getCibles())
                ->setNewData($itemNew->getCibles())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_MANY)
                ->setField("name"),
        ];

        $this->history->compare($compare);
    }

    public function compareLink($itemOld, $itemNew)
    {

        $compare = [
            (new HistoryData())
                ->setTitle("Nom")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Title")
                ->setTypeOfCompare(($itemNew === null || $itemOld === null) ? HistoryTypeOfCompare::ADD_OR_DELETE : HistoryTypeOfCompare::FIELD),
            (new HistoryData())
                ->setTitle("URL")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Link")
                ->setTypeOfCompare(($itemNew === null || $itemOld === null) ? HistoryTypeOfCompare::ADD_OR_DELETE : HistoryTypeOfCompare::FIELD),
            (new HistoryData())
                ->setTitle("Description du lien")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Content")
                ->setTypeOfCompare(($itemNew === null || $itemOld === null) ? HistoryTypeOfCompare::ADD_OR_DELETE : HistoryTypeOfCompare::FIELD),
        ];

        $this->history->compare($compare);
    }

    public function compareFile($itemOld, $itemNew)
    {

        $compare = [
            (new HistoryData())
                ->setTitle("Nom")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Title")
                ->setTypeOfCompare(($itemNew === null || $itemOld === null) ? HistoryTypeOfCompare::ADD_OR_DELETE : HistoryTypeOfCompare::FIELD),

            (new HistoryData())
                ->setTitle("nom du fichier")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("fileName")
                ->setTypeOfCompare(($itemNew === null || $itemOld === null) ? HistoryTypeOfCompare::ADD_OR_DELETE : HistoryTypeOfCompare::FIELD),

            (new HistoryData())
                ->setTitle("Description du lien")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Content")
                ->setTypeOfCompare(($itemNew === null || $itemOld === null) ? HistoryTypeOfCompare::ADD_OR_DELETE : HistoryTypeOfCompare::FIELD),

        ];

        $this->history->compare($compare);
    }
}
