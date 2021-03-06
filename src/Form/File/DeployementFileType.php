<?php

namespace App\Form\File;

use App\Entity\ActionFile;
use App\Form\AppTypeAbstract;
use App\Entity\DeployementFile;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DeployementFileType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file',
                FileType::class,
                [
                    self::LABEL            => 'Choisir le fichier',
                    self::REQUIRED => false
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    self::LABEL            => 'titre',
                    self::REQUIRED => false
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'required' => false,
                    self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
                ]
            )
            ->add(
                'updatedAt',
                DateTimeType::class,
                [
                    'label' => ' ',
                    'required' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeployementFile::class,
        ]);
    }
}
