<?php

namespace App\Form\Action;

use App\Entity\Action;
use App\Entity\Cible;
use App\Entity\Vecteur;
use App\Form\AppTypeAbstract;
use App\Form\File\ActionFileType;
use App\Form\File\ActionLinkType;
use App\Form\File\CadrageFileType;
use App\Form\File\CadrageLinkType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionEditType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormName($builder);
        $this->buildFormContent($builder, 'Informations complémentaires');
        $this->buildFormCategory($builder, false);
        $this->buildFormReaders($builder);
        $this->buildFormWriters($builder);
        $this->buildFormCOTECHValiders($builder);
        $this->buildFormCODIRValiders($builder);

        return $builder
            ->add('regionstartat', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => true,
            ])
            ->add('measureValue', ChoiceType::class, [
                self::LABEL => 'Score',
                self::REQUIRED => true,
                'choices' => [
                    '0 %' => 0,
                    '10 %' => 10,
                    '20 %' => 20,
                    '30 %' => 30,
                    '40 %' => 40,
                    '50 %' => 50,
                    '60 %' => 60,
                    '70 %' => 70,
                    '80 %' => 80,
                    '90 %' => 90,
                    '100 %' => 100,
                    ],
            ])
            ->add('measureContent', TextareaType::class, [
                self::LABEL => 'Argumentaire',
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 10, self::CSS_CLASS => 'textarea'],
            ])
            ->add('showAt', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ->add('isExperimental', CheckboxType::class,
                [
                    self::LABEL => '  Expérimentation',
                    self::REQUIRED => false,
                    self::ROW_ATTR => [self::CSS_CLASS => 'test'],
                ])
            ->add('regionendat', DateType::class, [
                self::REQUIRED => false,
                'widget' => 'single_text',
            ])
            ->add('cadrage', TextareaType::class, [
                self::LABEL => 'Cadrage',
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 10, self::CSS_CLASS => 'textarea'],
            ])
            ->add('isShowall', CheckboxType::class,
                [
                    self::LABEL => '  Visible par tous',
                    self::REQUIRED => false,
                ])
            ->add('cibles', EntityType::class, [
                'class' => Cible::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('vecteurs', EntityType::class, [
                'class' => Vecteur::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.name', 'ASC');
                },
            ])
            ->add('actionFiles', CollectionType::class, [
                'entry_type' => ActionFileType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('actionLinks', CollectionType::class, [
                'entry_type' => ActionLinkType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('cadrageFiles', CollectionType::class, [
                'entry_type' => CadrageFileType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('cadrageLinks', CollectionType::class, [
                'entry_type' => CadrageLinkType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
