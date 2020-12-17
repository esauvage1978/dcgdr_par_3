<?php

namespace App\DataFixtures;

use App\Entity\Corbeille;
use App\Helper\FixturesImportData;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use App\Validator\CorbeilleValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step4_CorbeilleFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_corbeille';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var CorbeilleValidator
     */
    private $validator;

    /**
     * @var \App\Entity\Organisme[]
     */
    private $organismes;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CorbeilleValidator $validator,
        OrganismeRepository $organismeRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->organismes = $organismeRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . ".json");

        for ($i = 0; $i < \count($data); $i++) {

            $instance = $this->initialise(new Corbeille(), $data[$i]);

            $this->checkAndPersist($instance);
        }

        $this->create_corbeille_test();

        $this->entityManagerInterface->flush();
    }

    private function create_corbeille_test()
    {
        $organismeDcgdr = $this->getInstance(2, $this->organismes);
        $organismeFlandres = $this->getInstance(1, $this->organismes);
        $datas = [
            [
                'id' => '114',
                'name' => 'TEST COTECH DCGDR',
                'organisme' => $organismeDcgdr,
                'isDefault' => false,
                'isWriter' => false,
                'isValidate' => true,
            ],
            [
                'id' => '115',
                'name' => 'TEST CODIR DCGDR',
                'organisme' => $organismeDcgdr,
                'isDefault' => false,
                'isWriter'=> false,
                'isValidate' => true,
            ],
            [
                'id' => '116',
                'name' => 'TEST Action pilotage',
                'organisme' => $organismeDcgdr,
                'isDefault' => false,
                'isWriter' => true,
                'isValidate' => false,
            ],
            [
                'id' => '117',
                'name' => 'TEST déploiement pilotage',
                'organisme' => $organismeFlandres,
                'isDefault' => true,
                'isWriter'=> true,
                'isValidate' => false,
            ],
        ];
        foreach ($datas as $data) {
            //création d'une corbeille COTECH
            $corbeille = new Corbeille();
            $corbeille
                ->setId($data['id'])
                ->setName($data['name'])
                ->setOrganisme($data['organisme'])
                ->setIsEnable(true)
                ->setIsUseByDefault($data['isDefault'])
                ->setIsShowWrite($data['isWriter'])
                ->setIsShowValidate($data['isValidate']);
            $this->checkAndPersist($corbeille);
        }
    }


    private function checkAndPersist(Corbeille $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Corbeille::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }

    private function initialise(Corbeille $instance, $data): Corbeille
    {
        $organisme = $this->getInstance($data['id_organisme'], $this->organismes);

        $instance
            ->setId($data['n0_num'])
            ->setName(
                strlen($data['nom']) > 3 ?
                    $data['nom'] :
                    $data['nom'] . '_fixtures'
            )
            ->setIsEnable($data['afficher'])
            ->setContent($data['description'])
            ->setIsUseByDefault($data['pa_defaut'])
            ->setIsShowRead($data['pa_lecture'])
            ->setIsShowWrite($data['pa_ecriture'])
            ->setIsShowValidate($data['pa_validation'])
            ->setOrganisme($organisme);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step4'];
    }
}
