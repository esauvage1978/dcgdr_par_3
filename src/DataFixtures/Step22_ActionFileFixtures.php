<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Helper\Slugger;
use App\Helper\FileTools;
use App\Entity\ActionFile;
use App\Helper\FileDirectory;
use App\Helper\DirectoryTools;
use App\Helper\SplitNameOfFile;
use App\Helper\ParamsInServices;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Validator\IndicatorValidator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step22_ActionFileFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_mb_pj_action_link';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var IndicatorValidator
     */
    private $validator;

    /**
     * @var Action[]
     */
    private $actions;

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
        IndicatorValidator $validator,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI,
        ParamsInServices $params
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->actions = $actionRepository->findAll();
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
            $instance = $this->initialise(new ActionFile(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(ActionFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(ActionFile $instance, $data): ?ActionFile
    {
        if ('pa_actions' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '1' == $data['lien']) {
            return null;
        }

        /** @var Action $action */
        $action = $this->getInstance($data['obj_num'], $this->actions);
        $sf = new  SplitNameOfFile($data['adresse']);
        $slugified = Slugger::slugify($sf->getName());
        $filename = $slugified . '.' . $sf->getExtension();
        
        if (is_a($action, Action::class)
           ) {
            $instance
                ->setTitle($data['titre'])
                ->setFileExtension($data['extension'])
                ->setFileName($slugified)
                ->setSize($this->fileTools->size($this->params->get(ParamsInServices::ES_DIRECTORY_FIXTURES_DOC) . '/pa_actions', $filename))
                ->setAction($action)
                ;

            $this->moveFile($action->getId(), $data['adresse']);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $actionId, string $fileName)
    {
        $dirDestination=$this->params->get(ParamsInServices::ES_DIRECTORY_UPLOAD_ACTION);
        $dirSource=$this->params->get(ParamsInServices::ES_DIRECTORY_FIXTURES_DOC) . '/pa_actions';

        if (!$this->directoryTools->exist($dirDestination, $actionId)) {
            $this->directoryTools->create($dirDestination, $actionId);
        }

        if (!$this->fileTools->exist($dirDestination . '/' . $actionId . '/', $fileName) && $this->fileTools->exist($dirSource, $fileName)) {
            $this->fileTools->move($dirSource, $fileName, $dirDestination . '/' . $actionId, $fileName);
        }
    }

    public static function getGroups(): array
    {
        return ['step22'];
    }
}
