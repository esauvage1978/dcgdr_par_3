<?php

namespace App\Workflow;

use App\Entity\Action;
use App\Repository\ActionRepository;
use App\Service\ActionRefControllator;
use App\Service\ActionRefGenerator;

class ActionCheck
{
    /**
     * var Action
     */
    private $action;

    /**
     * @var ActionCheckMessage
     */
    private $actionCheckMessage;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    public function __construct(Action $action, ActionRepository $actionRepository)
    {
        $this->action = $action;
        $this->actionCheckMessage = new  ActionCheckMessage();
        $this->actionRepository= $actionRepository;
    }

    public function hasMessageError(): bool
    {
        return $this->actionCheckMessage->hasMessageError();
    }

    public function getMessages(): array
    {
        return $this->actionCheckMessage->getMessages();
    }

    public function checkName()
    {
        if (empty($this->action->getName())) {
            $this->actionCheckMessage->addMessageError('Nom non renseigné');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Nom');
        }
    }

    public function checkReference()
    {
        if (empty($this->action->getRef())) {
            $this->actionCheckMessage->addMessageError('Référence non renseigné');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Référence');
        }
    }

    public function checkCadrage()
    {
        if (empty($this->action->getCadrage())) {
            $this->actionCheckMessage->addMessageError('Cadrage non renseigné');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Cadrage');
        }
    }

    public function checkRegionStartAt()
    {
        if (empty($this->action->getRegionStartAt())) {
            $this->actionCheckMessage->addMessageError('Date de début de déploiement absente');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Date de début de déploiement');
        }
    }



    public function getDiffRegionStartAtAfterNow()
    {
        return $this->getNbrDayBeetwenDates(new \DateTime(), $this->action->getRegionStartAt());
    }
    public function getDiffRegionEndAtBeforeOrEqualNow()
    {
        return $this->getNbrDayBeetwenDates(new \DateTime(), $this->action->getRegionEndAt());
    }
    public function getDiffRegionStartAtBeforeOrEqualNow()
    {
        return $this->getNbrDayBeetwenDates($this->action->getRegionStartAt(), new \DateTime());
    }
    public function getDiffRegionEndAtAfterNow()
    {
        return $this->getNbrDayBeetwenDates($this->action->getRegionEndAt(), new \DateTime());
    }

    public function checkRegionStartAtBeforeOrEqualNow()
    {
        if (!empty($this->action->getRegionStartAt())) {
            if (new \DateTime() >= $this->action->getRegionStartAt()) {
                $this->actionCheckMessage->addMessageError('La date de début est passée (<=).');
                return true;
            }
        }
        return false;
    }
    public function checkRegionEndAtBeforeOrEqualNow()
    {
        if (!empty($this->action->getRegionEndAt())) {
            if (new \DateTime() >= $this->action->getRegionEndAt()) {
                $this->actionCheckMessage->addMessageError('La date de fin est passée (<=).');
                return true;
            }
        }
        return false;
    }

    public function checkRegionStartAtAfterNow()
    {
        if (!empty($this->action->getRegionStartAt())) {
            if (new \DateTime() < $this->action->getRegionStartAt()) {
                $this->actionCheckMessage->addMessageError('La date de début est à venir.');
                return true;
            }
        }
        return false;
    }
    public function checkRegionEndAtAfterNow(): bool
    {
        if (!empty($this->action->getRegionEndAt())) {
            if (new \DateTime() < $this->action->getRegionEndAt()) {
                $this->actionCheckMessage->addMessageError('La date de fin n\'est pas passée.');
                return true;
            }
        }
        return false;
    }

    public function getDiffDateOfState()
    {
        return $this->getNbrDayBeetwenDates(new \DateTime(), $this->action->getStateAt());
    }



    private function getNbrDayBeetwenDates(\DateTime $date1, \DateTime $date2)
    {

        $nbJoursTimestamp = $date1->getTimestamp() - $date2->getTimestamp();

        return round($nbJoursTimestamp / 86400);
    }

    public function checkRegionEndAt()
    {
        if (empty($this->action->getRegionEndAt())) {
            $this->actionCheckMessage->addMessageError('Date de fin de déploiement absente');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Date de fin de déploiement');
        }
    }

    public function checkRegionStartAtBeforeRegionEnAt()
    {
        if (!empty($this->action->getRegionEndAt()) and  !empty($this->action->getRegionStartAt())) {
            if ($this->action->getRegionEndAt() < $this->action->getRegionStartAt()) {
                $this->actionCheckMessage->addMessageError('La date de fin est antérieure à la date de début ');
            }
        }
    }

    public function checkCorbeillePilotage()
    {
        if ($this->action->getWriters()->count() > 0) {
            $this->actionCheckMessage->addMessageSuccess('Corbeille de pilotage');
        } else {
            $this->actionCheckMessage->addMessageError('Corbeille(s) de pilotage non présente(s)');
        }
    }

    public function checkCorbeilleCOTECHValidation()
    {
        if ($this->action->getCOTECHValiders()->count() > 0) {
            $this->actionCheckMessage->addMessageSuccess('Corbeille de validation COTECH');
        } else {
            $this->actionCheckMessage->addMessageError('Corbeille(s) de validation COTECH non présente(s)');
        }
    }

    public function checkCorbeilleCODIRValidation()
    {
        if ($this->action->getCODIRValiders()->count() > 0) {
            $this->actionCheckMessage->addMessageSuccess('Corbeille de validation CODIR');
        } else {
            $this->actionCheckMessage->addMessageError('Corbeille(s) de validation CODIR non présente(s)');
        }
    }

    public function checkIndicators()
    {
        $nbr = 0;
        foreach ($this->action->getIndicators() as $indicator) {
            if (true == $indicator->getIsEnable()) {
                $nbr = $nbr + 1;
            }
        }

        if ($nbr == 0) {
            $this->actionCheckMessage->addMessageError('Indicateur(s) non présent(s)');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Indicateur');
        }
    }

    public function checkOrganismes()
    {
        if ($this->action->getDeployements()->count() == 0) {
            $this->actionCheckMessage->addMessageError('Organisme(s) concerné(s) non présente(s)');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Organisme concerné');
        }
    }

    public function checkDeploiement()
    {
        $nbr = 0;
        foreach ($this->action->getIndicators() as $indicator) {
            foreach ($indicator->getIndicatorValues() as $indicatorValue) {
                if (true == $indicatorValue->getIsEnable()) {
                    $nbr = $nbr + 1;
                }
            }
        }

        if ($nbr == 0) {
            $this->actionCheckMessage->addMessageError('Déploiement(s) non présent(s)');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Déploiement');
        }
    }

    public function checkMeasureValue()
    {
        if ($this->action->getMeasureValue() === null) {
            $this->actionCheckMessage->addMessageError('Valeur de la mesure d\'efficacité non renseignée');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Valeur de la mesure d\'efficacité');
        }
    }

    public function checkMeasureContent()
    {
        if (empty($this->action->getMeasureContent())) {
            $this->actionCheckMessage->addMessageError('Argumentaire de la mesure d\'efficacité non renseigné');
        } else {
            $this->actionCheckMessage->addMessageSuccess('Argumentaire');
        }
    }
}
