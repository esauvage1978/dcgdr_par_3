<?php

namespace App\Listener;

use App\Helper\Slugger;
use App\Helper\FileTools;
use App\Service\Uploader;
use App\Entity\ActionFile;
use App\Helper\FileDirectory;
use App\Helper\DirectoryTools;
use App\Entity\DeployementFile;
use App\Helper\SplitNameOfFile;
use Doctrine\ORM\Mapping as ORM;

class DeployementFileUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $pathTarget;

    public function __construct(Uploader $uploader, string $path)
    {
        $this->uploader = $uploader;
        $this->path = $path;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(DeployementFile $entityFile)
    {
        $file = $entityFile->getFile();
        if (!empty($file)) {

            $splitNameFile = new SplitNameOfFile($file->getClientOriginalName());
            $extension = $splitNameFile->getExtension();

            if (empty($entityFile->getFileName())) {
                $entityFile->setFileName(Slugger::slugify($splitNameFile->getName()));
            }

            if (empty($entityFile->getTitle())) {
                $entityFile->setTitle('Nouveau fichier');
            }
            $entityFile->setFileExtension($extension);
            $entityFile->setSize($this->uploader->getSize($file));
        }
        $entityFile->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(DeployementFile $entityFile)
    {
        if (!empty($entityFile->getFile())) {

            $fileTools = new FileTools();

            $this->createPathDirectory($entityFile);

            if (null !== $entityFile->getFullName()) {
                $fileTools->remove($this->pathTarget, $entityFile->getFullName());
            }

            $this->uploader->setTargetDir($this->pathTarget);
            $this->uploader->upload($entityFile->getFile(), $entityFile->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(DeployementFile $entityFile)
    {
        $this->createPathDirectory($entityFile);
        $fileTools = new FileTools();

        $fileTools->remove($this->pathTarget, $entityFile->getFullName());
    }


    private function createPathDirectory(DeployementFile $entityFile)
    {
        $DirectoryTools = new DirectoryTools();
        $directoryTarget = $entityFile->getDeployement()->getAction()->getId();

        if (!$DirectoryTools->exist($this->path, $directoryTarget)) {
            $DirectoryTools->create($this->path, $directoryTarget);
        }

        $directoryTargetLast = $entityFile->getDeployement()->getId();

        if (!$DirectoryTools->exist($this->directory . '/' . $directoryTarget, $directoryTargetLast)) {
            $DirectoryTools->create($this->directory . '/' . $directoryTarget, $directoryTargetLast);
        }

        $this->pathTarget = $this->directory . '/' . $directoryTarget;
    }




}
