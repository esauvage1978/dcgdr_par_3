<?php

namespace App\Form\Mailer;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use App\Repository\ActionRepository;
use App\Repository\CorbeilleRepository;
use App\Repository\DeployementRepository;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MailerFormActionType extends MailerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormSubjectContent($builder);

        $builder

            ->add('actionValiderCOTECH', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Valideurs au COTECH de l\'action',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            CorbeilleRepository::ALIAS_ACTION_COTECH,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS_ACTION_COTECH)
                        ->leftJoin(CorbeilleRepository::ALIAS_ACTION_COTECH . '.actionCOTECHValiders', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])
            ->add('actionValiderCODIR', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Valideurs au CODIR de l\'action',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            CorbeilleRepository::ALIAS_ACTION_CODIR,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS_ACTION_CODIR)
                        ->leftJoin(CorbeilleRepository::ALIAS_ACTION_CODIR . '.actionCODIRValiders', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])
            ->add('actionWriter', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Pilotes de l\'action',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            CorbeilleRepository::ALIAS_ACTION_WRITERS,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS_ACTION_WRITERS)
                        ->leftJoin(CorbeilleRepository::ALIAS_ACTION_WRITERS . '.actionWriters', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])
            ->add('deployementWriter', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Pilotes des déploiements',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS,
                            DeployementRepository::ALIAS,
                            OrganismeRepository::ALIAS,
                            ActionRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
                        ->leftJoin(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS . '.deployementWriters', DeployementRepository::ALIAS)
                        ->leftJoin(DeployementRepository::ALIAS . '.organisme', OrganismeRepository::ALIAS)
                        ->leftJoin(DeployementRepository::ALIAS . '.action', ActionRepository::ALIAS)
                        ->where(ActionRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ]);
    }
}
