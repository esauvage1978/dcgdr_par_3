<?php

namespace App\DataFixtures;

use App\Entity\Axe;
use App\Helper\FixturesImportData;
use App\Repository\AxeRepository;
use App\Validator\AxeValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step6_AxeFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_axe';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var AxeValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        AxeValidator $validator,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface=$entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new Axe(), $data[$i]);

            $this->checkAndPersist($instance);
        }
        $this->create_items_test();
        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(Axe $instance)
    {
        if(empty($instance)) {
            dump("instance vide");
        }

        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Axe::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }

    private function create_items_test()
    {
        $datas = [
            [
                'id' => '4',
                'name' => 'TEST PA Manu',
            ],
        ];
        foreach ($datas as $data) {
            //création d'une corbeille COTECH
            $item = new Axe();
            $item
                ->setId($data['id'])
                ->setName($data['name'])
                ->setIsEnable(true)
                ->setIsArchiving(false);
            $this->checkAndPersist($item);
        }
    }

    private function initialise(Axe $instance, $data): Axe
    {
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setContent($data['description'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
        ;

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step6'];
    }
}
