<?php

namespace App\Manager;

use App\Entity\Deployement;
use App\Entity\EntityInterface;
use App\Entity\Indicator;
use App\Entity\IndicatorValue;
use App\Indicator\IndicatorData;
use App\Repository\IndicatorValueRepository;
use App\Validator\IndicatorValueValidator;
use Doctrine\ORM\EntityManagerInterface;

class IndicatorValueManager 
{
    /**
     * @var IndicatorValueRepository
     */
    private $repo;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserValidator */
    private $validator;

    /**
     * IndicatorValueManager constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        IndicatorValueValidator $validator,
        IndicatorValueRepository $repo
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->repo = $repo;
    }


    public function save(EntityInterface $entity): bool
    {
        if (!$this->validator->isValid($entity)) {
            return false;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        return true;
    }

    public function initialiseEntity(
        Indicator $indicator,
        Deployement $deployement,
        IndicatorValue $indicatorValue = null): IndicatorValue
    {
        if (null === $indicatorValue) {
            $indicatorValue = new IndicatorValue();

            $indicatorValue
                ->setDeployement($deployement)
                ->setIndicator($indicator)
                ->setTaux1(0)
                ->setTaux2(0)
                ->setIsEnable(true);

            $indicatorValue->setGoal(
                $this->initialiseGoal($indicator)
            );
            $indicatorValue->setValue(
                $this->initialiseValue($indicator)
            );
        } else {
            $indicatorValue->setIsEnable(!$indicatorValue->getIsEnable());
        }

        $indicatorValue->setTaux1($this->calculTaux($indicatorValue, true));
        $indicatorValue->setTaux2($this->calculTaux($indicatorValue, false));

        return $indicatorValue;
    }

    private function initialiseGoal(Indicator $indicator)
    {
        $keepGoal = [
            IndicatorData::INDICATOR_QUANTITATIF,
            IndicatorData::INDICATOR_QUANTITATIF_GOAL,
            IndicatorData::INDICATOR_CONTRIBUTIF,
        ];

        if (in_array($indicator->getIndicatortype(), $keepGoal)) {
            return $indicator->getGoal();
        } else {
            return '100';
        }
    }

    private function initialiseValue(Indicator $indicator)
    {
        $keepValue = [
            IndicatorData::INDICATOR_QUANTITATIF,
            IndicatorData::INDICATOR_QUANTITATIF_GOAL,
            IndicatorData::INDICATOR_QUALITATIF,
            IndicatorData::INDICATOR_QUALITATIF_PALIER_5,
            IndicatorData::INDICATOR_QUALITATIF_PALIER_25,
            IndicatorData::INDICATOR_CONTRIBUTIF,
        ];

        if (in_array($indicator->getIndicatortype(), $keepValue)) {
            return $indicator->getValue();
        } else {
            return '';
        }
    }

    public function calculTaux(IndicatorValue $indicatorValue, $taux1 = true)
    {
        $taux = 0;

        $calculTauxUnitaire = [
            IndicatorData::INDICATOR_QUALITATIF,
            IndicatorData::INDICATOR_QUALITATIF_PALIER_5,
            IndicatorData::INDICATOR_QUALITATIF_PALIER_25,
            IndicatorData::INDICATOR_QUANTITATIF,
        ];

        if (in_array($indicatorValue->getIndicator()->getIndicatortype(), $calculTauxUnitaire)) {
            return $this->calculTauxUnitaire(
                $indicatorValue->getGoal(),
                $indicatorValue->getValue(),
                $taux1
            );
        }

        switch ($indicatorValue->getIndicator()->getIndicatortype()) {
            case IndicatorData::INDICATOR_CONTRIBUTIF:
                $taux = $this->calculTauxUnitaire(
                    $indicatorValue->getIndicator()->getGoal(),
                    $this->getSumValue($indicatorValue->getIndicator()),
                    $taux1
                );
                $this->repo->initialiseTaux($indicatorValue->getIndicator()->getId(), $taux1, $taux);
                break;
            case IndicatorData::INDICATOR_QUANTITATIF_GOAL:
                $taux = $this->calculTauxUnitaire(
                    $indicatorValue->getIndicator()->getGoal(),
                    $indicatorValue->getValue(),
                    $taux1
                );
                break;
            case IndicatorData::INDICATOR_BINAIRE_OUI:
                $taux = $this->calculTauxBinaire(
                    $indicatorValue->getValue(),
                    ['oui']
                );
                break;
            case IndicatorData::INDICATOR_BINAIRE_NON:
                $taux = $this->calculTauxBinaire(
                    $indicatorValue->getValue(),
                    ['non']
                );
                break;

            case IndicatorData::INDICATOR_BINAIRE:
                $taux = $this->calculTauxBinaire(
                    $indicatorValue->getValue(),
                    ['non', 'oui']
                );
                break;
        }

        return $taux;
    }

    private function getSumValue(Indicator $indicator)
    {
        $cumul = 0;
        foreach ($indicator->getIndicatorValues() as $indicatorValue) {
            $cumul += $indicatorValue->getValue();
        }

        return $cumul;
    }

    private function calculTauxUnitaire($total, $nombre, $limiteA100 = true)
    {
        try {
            $total = preg_replace('`[^0-9]`', '', $total);
            $nombre = preg_replace('`[^0-9]`', '', $nombre);

            if ('0' == $total) {
                return 0;
            }

            $pourcentage = 100;
            $resultat = round(($nombre / $total) * $pourcentage);

            if ($limiteA100 and $resultat > 100) {
                return 100;
            } else {
                return $resultat;
            }
        } catch (\Exception $e) {
            dump($e.' - '.$total.' - '.$nombre);
        }
    }

    private function calculTauxBinaire($valeur, $listeValeurGoal)
    {
        if (in_array($valeur, $listeValeurGoal)) {
            return 100;
        } else {
            return 0;
        }
    }
}
