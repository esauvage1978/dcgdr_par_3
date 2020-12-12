<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Helper\Slugger;
use App\Helper\FileTools;
use App\Entity\CadrageFile;
use App\Helper\FileDirectory;
use App\Helper\DirectoryTools;
use App\Helper\SplitNameOfFile;
use App\Helper\ParamsInServices;
use App\Repository\ActionRepository;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step25_CadrageFileFixtures extends Fixture implements FixtureGroupInterface
{
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
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI,
        ParamsInServices $params
    ) {
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
        $finder = new Finder();
        $dirSource = $this->params->get(ParamsInServices::ES_DIRECTORY_FIXTURES_DOC) . '/pa_cadrage';

        $finder->files()->in($dirSource);
        foreach ($finder as $file) {
            if ($file->isFile()) {

                $actionId = substr(
                    $file->getPath(),
                    strlen($dirSource) + 1,
                    strlen($file->getPath()) - strlen($dirSource)
                );

                $extension = $file->getExtension();

                $filename = substr(
                        $file->getFilename(),
                    0,
                    strlen($file->getFilename()) -
                    strlen($extension) - 1);

                    
                $instance = $this->initialise(
                    new CadrageFile(),
                    $actionId,
                    $extension,
                    $filename,
                    $file->getPath());

                if(!empty( $instance)) {
                    $this->checkAndPersist($instance);
                }
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(CadrageFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(CadrageFile $instance, $actionid, $extension, $filename, $dirSource): ?CadrageFile
    {
        /** @var Action $action */
        $action = $this->getInstance($actionid, $this->actions);

        if (is_a($action, Action::class)
           ) {

            if(strlen($filename)>50) {
                return null;
            }

            $instance
                ->setTitle($filename)
                ->setFileExtension($extension)
                ->setFileName($filename)
                ->setSize($this->fileTools->size($this->params->get(ParamsInServices::ES_DIRECTORY_FIXTURES_DOC) . '/pa_cadrage', $filename))
                ->setAction($action)
                ;

            $this->moveFile($dirSource, $actionid, $filename.'.' . $extension);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $dirSource, string $actionId, string $fileName)
    {

        $dirDestination = $this->params->get(ParamsInServices::ES_DIRECTORY_UPLOAD_ACTION);

        if (!$this->directoryTools->exist($dirDestination, $actionId)) {
            $this->directoryTools->create($dirDestination, $actionId);
        }
        $dirDestination=$dirDestination.'/'. $actionId;

        if (!$this->directoryTools->exist($dirDestination, 'pa_cadrage')) {
            $this->directoryTools->create($dirDestination, 'pa_cadrage');
        }

        $dirDestination=$dirDestination . '/pa_cadrage';

        $this->fileTools->move($dirSource, $fileName, $dirDestination, $fileName);
    }

    public static function getGroups(): array
    {
        return ['step25'];
    }
}
