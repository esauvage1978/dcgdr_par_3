<?php

namespace App\Form\Indicator;

use App\Entity\Indicator;
use App\Form\AppTypeAbstract;
use App\Indicator\IndicatorData;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndicatorType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);

        return $builder
            ->add('goal', TextType::class, [
                self::LABEL => 'Objectif ',
                self::REQUIRED => false,
            ])
            ->add('value', TextType::class, [
                self::LABEL => 'Valeur initiale ',
                self::REQUIRED => false,
            ])
            ->add(
                'indicatortype',
                ChoiceType::class,
                [
                    self::LABEL => 'Type d\'indicateur ',
                    'choices' => [
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_BINAIRE) => IndicatorData::INDICATOR_BINAIRE,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_BINAIRE_OUI) => IndicatorData::INDICATOR_BINAIRE_OUI,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_BINAIRE_NON) => IndicatorData::INDICATOR_BINAIRE_NON,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_QUALITATIF_PALIER_5) => IndicatorData::INDICATOR_QUALITATIF_PALIER_5,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_QUALITATIF) => IndicatorData::INDICATOR_QUALITATIF,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_QUALITATIF_PALIER_25) => IndicatorData::INDICATOR_QUALITATIF_PALIER_25,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_QUANTITATIF) => IndicatorData::INDICATOR_QUANTITATIF,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_QUANTITATIF_GOAL) => IndicatorData::INDICATOR_QUANTITATIF_GOAL,
                        IndicatorData::getFullNameOfIndicator(IndicatorData::INDICATOR_CONTRIBUTIF) => IndicatorData::INDICATOR_CONTRIBUTIF,
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Indicator::class,
        ]);
    }
}
