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

class MailerFormDeployementType extends MailerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormSubjectContent($builder);

        $builder
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
                            OrganismeRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
                        ->leftJoin(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS . '.deployementWriters', DeployementRepository::ALIAS)
                        ->leftJoin(DeployementRepository::ALIAS . '.organisme', OrganismeRepository::ALIAS)
                        ->where(DeployementRepository::ALIAS . '.id = :depid')
                        ->setParameter('depid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ]);
    }
}
