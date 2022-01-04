<?php


namespace App\History;


use App\Entity\Deployement;
use App\Entity\EntityInterface;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
use Symfony\Component\Security\Core\Security;

class DeployementHistory implements HistoryEntityInterface
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
            ->setDeployement($entity)
            ->setDomaine($domaine);
        $this->history->setHistoryRelationEntity($historyEntity);
    }

    public function compare(EntityInterface $itemOld, EntityInterface $itemNew)
    {
        /**
         * @var Deployement
         */
        $itemOld = $itemOld;
        /**
         * @var Deployement
         */
        $itemNew = $itemNew;

        $compare = [
           

            (new HistoryData())
                ->setTitle("Jalon")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("showAt")
                ->setTypeOfData(HistoryData::TYPE_DATE),

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
