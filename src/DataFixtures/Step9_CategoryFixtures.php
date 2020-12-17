<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Helper\FixturesImportData;
use App\Repository\ThematiqueRepository;
use App\Validator\CategoryValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step9_CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_categorie';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var \App\Entity\Thematique[]
     */
    private $thematiques;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CategoryValidator $validator,
        ThematiqueRepository $thematiqueRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->thematiques = $thematiqueRepository->findAll();
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
            $instance = $this->initialise(new Category(), $data[$i]);

            $this->checkAndPersist($instance);
        }
        $this->create_items_test();
        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Category $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Category::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        }
    }

    private function initialise(Category $instance, $data): Category
    {
        $the = $this->getInstance($data['id_thematique'], $this->thematiques);
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setRef($data['code'])
            ->setIsEnable($data['afficher'])
            ->setContent($data['description'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
            ->setThematique($the);

        return $instance;
    }
    private function create_items_test()
    {
        $t1 = $this->getInstance('35', $this->thematiques);
        $t2 = $this->getInstance('36', $this->thematiques);
        $t3 = $this->getInstance('37', $this->thematiques);
        $t4 = $this->getInstance('38', $this->thematiques);
        $datas = [
            [
                'id' => '122',
                'name' => 'TEST Catégorie Manu 1',
                'ref' => 'c1',
                'pole' => $t1,
            ],
            [
                'id' => '123',
                'name' => 'TEST Catégorie Manu 2',
                'ref' => 'c2',
                'pole' => $t2,
            ],
            [
                'id' => '124',
                'name' => 'TEST Catégorie Manu 3',
                'ref' => 'c3',
                'pole' => $t3,
            ],
            [
                'id' => '125',
                'name' => 'TEST Catégorie Manu 4',
                'ref' => 'c4',
                'pole' => $t4,
            ],
            [
                'id' => '126',
                'name' => 'TEST Catégorie Manu 5',
                'ref' => 'c5',
                'pole' => $t1,
            ],
            [
                'id' => '127',
                'name' => 'TEST Catégorie Manu 6',
                'ref' => 'c6',
                'pole' => $t2,
            ],
            [
                'id' => '128',
                'name' => 'TEST Catégorie Manu 7',
                'ref' => 'c7',
                'pole' => $t3,
            ],
            [
                'id' => '129',
                'name' => 'TEST Catégorie Manu 8',
                'ref' => 'c8',
                'pole' => $t4,
            ],
        ];

        foreach ($datas as $data) {
            //création d'une corbeille COTECH
            $item = new Category();
            $item
                ->setId($data['id'])
                ->setName($data['name'])
                ->setIsEnable(true)
                ->setThematique($data['pole'])
                ->setRef($data['ref']);
            $this->checkAndPersist($item);
        }
    }

    public static function getGroups(): array
    {
        return ['step9'];
    }
}
