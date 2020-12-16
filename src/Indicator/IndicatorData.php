<?php

namespace App\Indicator;

class IndicatorData
{
    const INDICATOR_QUALITATIF = 'qualitatif';
    const INDICATOR_QUALITATIF_PALIER_5 = 'qualitatif_palier_5';
    const INDICATOR_QUALITATIF_PALIER_25 = 'qualitatif_palier_25';
    const INDICATOR_QUANTITATIF = 'quantitatif';
    const INDICATOR_QUANTITATIF_GOAL = 'quantitatif_goal';
    const INDICATOR_CONTRIBUTIF = 'contributif';
    const INDICATOR_BINAIRE = 'binaire';
    const INDICATOR_BINAIRE_OUI = 'binaire_oui';
    const INDICATOR_BINAIRE_NON = 'binaire_non';

    const SHORT_NAME ='short_name';
    const FULL_NAME = 'full_name';
    const SHOW_INITIALVALUE='show_initial_value';
    const SHOW_GOAL = 'show_goal';

    private static function getIndicators(): array
    {
        return [
            self::INDICATOR_QUALITATIF =>
            [
                self::SHORT_NAME => 'Indicateur qualitatif (palier 10)',
                self::FULL_NAME => 'Indicateur qualitatif : de 0% à 100% avec des paliers de 10',
                self::SHOW_GOAL=>false,
                self::SHOW_INITIALVALUE => true
            ],
            self::INDICATOR_QUALITATIF_PALIER_5 =>
            [
                self::SHORT_NAME => 'Indicateur qualitatif (palier 5)',
                self::FULL_NAME => 'Indicateur qualitatif : de 0% à 100% avec des paliers de 5',
                self::SHOW_GOAL => false,
                self::SHOW_INITIALVALUE => true
            ],
            self::INDICATOR_QUALITATIF_PALIER_25 =>
            [
                self::SHORT_NAME => 'Indicateur qualitatif (palier 25)',
                self::FULL_NAME => 'Indicateur qualitatif : de 0% à 100% avec des paliers de 25',
                self::SHOW_GOAL => false,
                self::SHOW_INITIALVALUE => true
            ],
            self::INDICATOR_QUANTITATIF =>
            [
                self::SHORT_NAME => 'Indicateur quantitatif',
                self::FULL_NAME => 'Indicateur quantitatif : ce type d\'indicateur permet la sur performance des organismes',
                self::SHOW_GOAL => true,
                self::SHOW_INITIALVALUE => true
        ],
            self::INDICATOR_QUANTITATIF_GOAL =>
            [
                self::SHORT_NAME => 'Indicateur quantitatif (objectif figé)',
                self::FULL_NAME => 'Indicateur quantitatif : L\'objectif est verrouillé. Ce type d\'indicateur permet la sur performance des organismes',
                self::SHOW_GOAL => true,
                self::SHOW_INITIALVALUE => true
            ],
            self::INDICATOR_CONTRIBUTIF =>
            [
                self::SHORT_NAME => 'Indicateur contributif régional',
                self::FULL_NAME => 'Indicateur contributif régional : Les organismes contribuent collectivement à l\'atteinte de l\'objectif',
                self::SHOW_GOAL => true,
                self::SHOW_INITIALVALUE => false
          ],
            self::INDICATOR_BINAIRE =>
            [
                self::SHORT_NAME => 'Indicateur binaire',
                self::FULL_NAME => 'Indicateur binaire : Oui/Non, le taux sera de 100% si un choix est effectué',
                self::SHOW_GOAL => false,
                self::SHOW_INITIALVALUE => false
           ],
            self::INDICATOR_BINAIRE_OUI =>
            [
                self::SHORT_NAME => 'Indicateur binaire (oui)',
                self::FULL_NAME => 'Indicateur binaire (oui) : Oui/Non, le taux sera de 100% si le choix "oui" est effectué',
                self::SHOW_GOAL => false,
                self::SHOW_INITIALVALUE => false
            ],
            self::INDICATOR_BINAIRE_NON =>
            [
                self::SHORT_NAME => 'Indicateur binaire (non)',
                self::FULL_NAME => 'Indicateur binaire (non) : Oui/Non, le taux sera de 100% si le choix "non" est effectué',
                self::SHOW_GOAL => false,
                self::SHOW_INITIALVALUE => false
            ],
        ];
    }

    private static function  getIndicatorsValue($indicator, $data)
    {
        if (!self::hasIndicator($indicator)) {
            throw new \InvalidArgumentException('cet indicateur n\'existe pas : ' . $indicator);
        }
        return self::getIndicators()[$indicator][$data];
    }

    public static function hasIndicator(string $data): bool
    {
        $datas = [
            self::INDICATOR_QUALITATIF,
            self::INDICATOR_QUALITATIF_PALIER_5,
            self::INDICATOR_QUALITATIF_PALIER_25,
            self::INDICATOR_QUANTITATIF,
            self::INDICATOR_QUANTITATIF_GOAL,
            self::INDICATOR_CONTRIBUTIF,
            self::INDICATOR_BINAIRE,
            self::INDICATOR_BINAIRE_OUI,
            self::INDICATOR_BINAIRE_NON
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        throw new \InvalidArgumentException('cet indicateur n\'existe pas : ' . $data);
    }

    public static function getNameOfIndicator(string $state)
    {
        return self::getIndicatorsValue($state, self::SHORT_NAME);
    }

    public static function getFullNameOfIndicator(string $state)
    {
        return self::getIndicatorsValue($state, self::FULL_NAME);
    }

    public static function getShowGoalOfIndicator(string $state)
    {
        return self::getIndicatorsValue($state, self::SHOW_GOAL);
    }

    public static function getShowInitialeValueOfIndicator(string $state)
    {
        return self::getIndicatorsValue($state, self::SHOW_INITIALVALUE);
    }
}
