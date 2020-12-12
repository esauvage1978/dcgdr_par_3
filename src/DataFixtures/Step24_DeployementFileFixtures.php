<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Helper\Slugger;
use App\Helper\FileTools;
use App\Entity\ActionFile;
use App\Entity\Deployement;
use App\Helper\FileDirectory;
use App\Helper\DirectoryTools;
use App\Entity\DeployementFile;
use App\Helper\SplitNameOfFile;
use App\Helper\ParamsInServices;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DeployementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step24_DeployementFileFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_mb_pj_action_link';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Deployement[]
     */
    private $deployements;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var DirectoryTools
     */
    private $directoryTools;

    /**
     * @var FileTools
     */
    private $fileTools;

    /**
     * @var ParamsInServices
     */
    private $params;

    public function __construct(
        FixturesImportData $fixturesImportData,
        DeployementRepository $deployementRepository,
        EntityManagerInterface $entityManagerI,
        ParamsInServices $params
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->deployements = $deployementRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
        $this->directoryTools = new DirectoryTools();
        $this->fileTools = new FileTools();
        $this->params = $params;
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }

        return null;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new DeployementFile(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(DeployementFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(DeployementFile $instance, $data): ?DeployementFile
    {
        if ('deploiement' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '1' == $data['lien']) {
            return null;
        }

        /** @var Deployement $dep */
        $dep = $this->getInstance($data['obj_num'], $this->deployements);
        $sf = new  SplitNameOfFile($data['adresse']);
        $slugified = Slugger::slugify($sf->getName());
        $filename = $slugified . '.' . $sf->getExtension();
        
        if (is_a($dep, Deployement::class)
           ) {
            $instance
                ->setTitle($data['titre'])
                ->setFileExtension($data['extension'])
                ->setFileName($slugified)
                ->setSize($this->fileTools->size($this->params->get(ParamsInServices::ES_DIRECTORY_FIXTURES_DOC) . '/deploiement', $filename))
                ->setDeployement($dep)
                ;

            $this->moveFile($dep->getId(),$dep->getAction()->getId(), $data['adresse']);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $depId,string $actionId, string $fileName)
    {
        $dirDestination = $this->params->get(ParamsInServices::ES_DIRECTORY_UPLOAD_ACTION);
        $dirSource = $this->params->get(ParamsInServices::ES_DIRECTORY_FIXTURES_DOC) . '/deploiement';

        if (!$this->directoryTools->exist($dirDestination, $actionId)) {
            $this->directoryTools->create($dirDestination, $actionId);
        }
        $dirDestination= $dirDestination . '/' . $actionId;

        if (!$this->directoryTools->exist($dirDestination, $depId)) {
            $this->directoryTools->create($dirDestination, $depId);
        }

        if (!$this->fileTools->exist($dirDestination . '/' . $depId . '/', $fileName) && $this->fileTools->exist($dirSource, $fileName)) {
            $this->fileTools->move($dirSource, $fileName, $dirDestination . '/' . $depId, $fileName);
        }

    }

    public static function getGroups(): array
    {
        return ['step24'];
    }
}
