<?php


namespace App\History;


use App\Entity\Action;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
use Symfony\Component\Security\Core\Security;

class ActionHistory extends HistoryAbstract
{
    public function __construct(
        HistoryManager $manager,
        CurrentUser $currentUser
    )
    {
        parent::__construct($manager, $currentUser);
    }

    public function compare(Action $actionOld, Action $actionNew)
    {
        $this->history->setAction($actionNew);
        $diffPresent = false;

        $this->compareField('Plan d\'actions', $actionOld->getCategory()->getThematique()->getPole()->getAxe()->getName(), $actionNew->getCategory()->getThematique()->getPole()->getAxe()->getName()) && $diffPresent = true;
        $this->compareField('Pôle', $actionOld->getCategory()->getThematique()->getPole()->getName(), $actionNew->getCategory()->getThematique()->getPole()->getName()) && $diffPresent = true;
        $this->compareField('Thématique', $actionOld->getCategory()->getThematique()->getName(), $actionNew->getCategory()->getThematique()->getName()) && $diffPresent = true;
        $this->compareField('Catégorie', $actionOld->getCategory()->getName(), $actionNew->getCategory()->getName()) && $diffPresent = true;
        $this->compareField('Nom', $actionOld->getName(), $actionNew->getName()) && $diffPresent = true;
        $this->compareField('Référence', $actionOld->getRef(), $actionNew->getRef()) && $diffPresent = true;
        $this->compareField('Cadrage', $actionOld->getCadrage(), $actionNew->getCadrage()) && $diffPresent = true;
        $this->compareField('Informations complémentaires', $actionOld->getContent(), $actionNew->getContent()) && $diffPresent = true;
        $this->compareField('Début de déploiement', $actionOld->getRegionStartAt(), $actionNew->getRegionStartAt(), self::TYPE_DATE) && $diffPresent = true;
        $this->compareField('Fin de déploiement', $actionOld->getRegionEndAt(), $actionNew->getRegionEndAt(), self::TYPE_DATE) && $diffPresent = true;
        $this->compareField('Jalon', $actionOld->getShowAt(), $actionNew->getShowAt(), self::TYPE_DATE) && $diffPresent = true;
        $this->compareField('Expérimentation', $actionOld->getIsExperimental(), $actionNew->getIsExperimental(), self::TYPE_BOOL) && $diffPresent = true;


        
        if ($diffPresent) {
            $this->save();
        }
    }
}