<?php

namespace App\Form\Admin;

use App\Entity\Vecteur;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VecteurType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                self::LABEL => 'Nom',
                self::REQUIRED => true,
            ])
            ->add('isEnable', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vecteur::class,
        ]);
    }
}
