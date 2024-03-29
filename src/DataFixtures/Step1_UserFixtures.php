<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Helper\FixturesImportData;
use App\Manager\UserManager;
use App\Validator\UserValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1_UserFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_utilisateur';
    /**
     * @var FixturesImportData
     */
    private $importData;
    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $importData,
        UserValidator $validator,
        UserManager $userManager,
        EntityManagerInterface $entityManagerI
    ) {
        $this->importData = $importData;
        $this->validator = $validator;
        $this->userManager = $userManager;
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->importData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new User(), $data[$i]);

            $this->checkAndPersist($instance);
        }

        $this->createUser_test();

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(User $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(User::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }

    private function initialise(User $instance, $data): User
    {
        $roles = [];
        if ($data['admin']) {
            array_push($roles, 'ROLE_ADMIN');
        }
        if ($data['afficher']) {
            array_push($roles, 'ROLE_USER');
        }
        if ($data['droit1']) {
            array_push($roles, 'ROLE_GESTIONNAIRE');
        }
        if ($data['droit2']) {
            array_push($roles, 'ROLE_GES_LOCAL');
        }

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEmailValidated($data['afficher'])
            ->setEmailValidatedToken(md5(random_bytes(50)))
            ->setIsEnable($data['afficher'])
            ->setEmail(
                filter_var($data['mail'], FILTER_VALIDATE_EMAIL) ?
                    $data['mail'] :
                    'achanger@live.fr'
            )
            ->setRoles($roles)
            ->setPlainPassword('Fckgwrhqq101')
            ->setAccountValidated(true)
            ->setCreatedAt(new \DateTime())
            ->setAccountValidatedToken(date_format(new \DateTime(), 'Y-m-d H:i:s'));

        $this->userManager->initialise($instance);

        return $instance;
    }

    private function createUser_test()
    {
        $datas = [
            [
                'n0_num' => '154',
                'nom' => 'Manu COTECH',
                'mail' => 'esauvage1978@gmail.com',
                'mdp' => 'Fckgwrhqq101'
            ],
            [
                'n0_num' => '155',
                'nom' => 'Manu CODIR',
                'mail' => 'amz_kergava@outlook.fr',
                'mdp' => 'Fckgwrhqq101'
            ],
            [
                'n0_num' => '156',
                'nom' => 'Manu PILOTE',
                'mail' => 'contact@manuso.fr',
                'mdp' => 'Fckgwrhqq101'
            ],
            [
                'n0_num' => '157',
                'nom' => 'Manu PILOTE deployement',
                'mail' => 'contact@par.manuso.fr',
                'mdp' => 'Fckgwrhqq101'
            ],
        ];

        foreach ($datas as $data) {
            $instance=new user();
            $instance
                ->setId($data['n0_num'])
                ->setName($data['nom'])
                ->setEmailValidated(true)
                ->setEmailValidatedToken(md5(random_bytes(50)))
                ->setIsEnable(true)
                ->setEmail($data['mail'])
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword($data['mdp'])                    
                ->setCreatedAt(new \DateTime());

            $this->userManager->initialise($instance);
            $this->checkAndPersist($instance);
        }
    }

    public static function getGroups(): array
    {
        return ['step1'];
    }
}
