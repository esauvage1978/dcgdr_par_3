<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Entity\Corbeille;
use App\Entity\Organisme;
use App\Form\AppTypeAbstract;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CorbeilleGesLocType extends AppTypeAbstract
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormGenerique($builder,$options);
    }

    public function buildFormGenerique(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $id=$options['extra_fields_message'];

        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);
        $this->buildFormUsers($builder);
        $builder
            ->add('isUseByDefault', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('isShowRead', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('isShowWrite', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,

            ])
            ->add('isShowValidate', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('organisme', EntityType::class, [
                'class' => Organisme::class,
                self::CHOICE_LABEL => 'fullname',
                self::LABEL=> 'Organisme',
                self::MULTIPLE => false,
                self::ATTR => ['class' => 'select2'],
                self::REQUIRED => true,
                self::QUERY_BUILDER => function (EntityRepository $er) use($id)  {
                    return $er->createQueryBuilder('o')
                        ->innerJoin('o.users',UserRepository::ALIAS)
                        ->where(UserRepository::ALIAS.'.id = :id')
                        ->setParameter('id',$id)
                        ->orderBy('o.ref', 'ASC')
                        ->addOrderBy('o.name', 'ASC');
                },
            ]);

        return $builder;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Corbeille::class,
        ]);
    }
}
