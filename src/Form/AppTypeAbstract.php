<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\MProcess;
use App\Entity\Corbeille;
use App\Entity\Organisme;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

abstract class AppTypeAbstract extends AbstractType
{
    public const LABEL = 'label';
    public const DATA = 'data';
    public const REQUIRED = 'required';
    public const ROW_ATTR = 'row_attr';
    public const ATTR = 'attr';
    public const CHOICE_LABEL = 'choice_label';
    public const MULTIPLE = 'multiple';
    public const CSS_CLASS = 'class';
    public const ROWS = 'rows';
    public const GROUP_BY = 'group_by';
    public const QUERY_BUILDER = 'query_builder';
    public const DISABLED = 'disabled';
    public const MAXLENGTH = 'maxlength';
    public const PLACEHOLDER = 'placeholder';

    public function buildFormName(FormBuilderInterface $builder,$label='Nom'): void
    {
        $builder
            ->add('name', TextType::class, [
                self::LABEL => $label,
                self::REQUIRED => true,
                self::ATTR => [self::MAXLENGTH => 255],
            ]);
    }

    public function buildFormCategory(FormBuilderInterface $builder, bool $addselect): FormBuilderInterface
    {
        return $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                self::LABEL => 'Catégorie',
                self::CHOICE_LABEL => 'fullname',
                self::MULTIPLE => false,
                self::ATTR => ['class' => $addselect ? 'select2' : ''],
                self::REQUIRED => true,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }
    
    public function buildFormIsEnable(FormBuilderInterface $builder, $label=' '): void
    {
        $builder
            ->add(
                'isEnable',
                CheckboxType::class,
                [
                    self::LABEL => $label,
                    self::REQUIRED => false,
                ]
            );
    }

    public function buildFormContent(FormBuilderInterface $builder, $label= 'Description'): void
    {
        $builder
            ->add('content', TextareaType::class, [
                self::LABEL => $label,
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
            ]);
    }

    public function buildFormOrganismes(FormBuilderInterface $builder)
    {
        $builder
            ->add('organismes', EntityType::class, [
                'class' => Organisme::class,
                self::CHOICE_LABEL => 'fullname',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.ref', 'ASC')
                        ->addOrderBy('o.name', 'ASC');
                },
            ]);
    }


    public function buildFormOrganisme(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('organisme', EntityType::class, [
                'class' => Organisme::class,
                self::CHOICE_LABEL => 'fullname',
                self::MULTIPLE => false,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => true,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.ref', 'ASC');
                },
            ]);
    }

    public function buildFormCorbeilles(FormBuilderInterface $builder)
    {
        $builder
            ->add('corbeilles', EntityType::class, [
                'class' => Corbeille::class,
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.name', 'ASC');
                },
            ]);
    }

    public function buildFormUsers(FormBuilderInterface $builder)
    {
        $builder
            ->add('users', EntityType::class, [
                'class' => User::class,
                self::LABEL => 'Utilisateurs',
                self::CHOICE_LABEL => 'name',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ]);
    }


    public function buildFormReaders(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('readers', EntityType::class, [
                'class' => Corbeille::class,
                self::CHOICE_LABEL => 'fullname',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c', 'o')
                        ->leftJoin('c.organisme', 'o')
                        ->where('o.isEnable = true')
                        ->andWhere('c.isEnable = true')
                        ->andWhere('c.isShowRead = true')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }

    public function buildFormWriters(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('writers', EntityType::class, [
                'class' => Corbeille::class,
                self::CHOICE_LABEL => 'fullname',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c', 'o')
                        ->leftJoin('c.organisme', 'o')
                        ->where('o.isEnable = true')
                        ->andWhere('c.isEnable = true')
                        ->andWhere('c.isShowWrite = true')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }

    public function buildFormValiders(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('validers', EntityType::class, [
                'class' => Corbeille::class,
                self::CHOICE_LABEL => 'fullname',
                self::MULTIPLE => true,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c', 'o')
                        ->leftJoin('c.organisme', 'o')
                        ->where('o.isEnable = true')
                        ->andWhere('c.isEnable = true')
                        ->andWhere('c.isShowValidate = true')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
    }

}
