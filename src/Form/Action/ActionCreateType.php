<?php

namespace App\Form\Action;

use App\Entity\Action;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionCreateType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormName($builder);
        $this->buildFormContent($builder,'Informations complémentaires');
        $this->buildFormCategory($builder, false);
        $builder
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => true,
            ]) ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
