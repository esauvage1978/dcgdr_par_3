<?php

namespace App\DataFixtures;

use App\Entity\Thematique;
use App\Helper\FixturesImportData;
use App\Repository\PoleRepository;
use App\Validator\ThematiqueValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step8_ThematiqueFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_thematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var ThematiqueValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var \App\Entity\Pole[]
     */
    private $poles;

    public function __construct(
        FixturesImportData $fixturesImportData,
        ThematiqueValidator $validator,
        PoleRepository $poleRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->poles = $poleRepository->findAll();
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
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new Thematique(), $data[$i]);

            $this->checkAndPersist($instance);
        }
        $this->create_items_test();
        $this->entityManagerInterface->flush();
    }



    private function checkAndPersist(Thematique $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Thematique::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }

    private function initialise(Thematique $instance, $data): Thematique
    {
        $pole = $this->getInstance($data['id_pole'], $this->poles);

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setRef($data['code'])
            ->setIsEnable($data['afficher'])
            ->setContent($data['description'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
            ->setPole($pole);

        return $instance;
    }


    private function create_items_test()
    {
        $pole1 = $this->getInstance('16', $this->poles);
        $pole2 = $this->getInstance('17', $this->poles);
        $datas = [
            [
                'id' => '35',
                'name' => 'TEST thematique Manu 1',
                'ref' => 't1',
                'pole' => $pole1,
            ],
            [
                'id' => '36',
                'name' => 'TEST thematique Manu 2',
                'ref' => 't2',
                'pole' => $pole2,
            ],
            [
                'id' => '37',
                'name' => 'TEST thematique Manu 3',
                'ref' => 't3',
                'pole' => $pole1,
            ],
            [
                'id' => '38',
                'name' => 'TEST thematique Manu 4',
                'ref' => 't4',
                'pole' => $pole2,
            ],
        ];

        foreach ($datas as $data) {
            //création d'une corbeille COTECH
            $item = new Thematique();
            $item
                ->setId($data['id'])
                ->setName($data['name'])
                ->setIsEnable(true)
                ->setPole($data['pole'])
                ->setRef($data['ref']);
            $this->checkAndPersist($item);
        }
    }

    public static function getGroups(): array
    {
        return ['step8'];
    }
}
