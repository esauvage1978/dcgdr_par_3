<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeployementFileRepository")
 * @ORM\EntityListeners({"App\Listener\DeployementFileUploadListener"})
 */
class DeployementFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $fileExtension;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrView;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Deployement", inversedBy="deployementFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deployement;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    private $file;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $size;


    public function __construct()
    {
        $this->setNbrView(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): ?string
    {
        return $this->fileName.'.'.$this->fileExtension;
    }

    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        return 'uploads/action/' .
            $this->getDeployement()->getAction()->getId() . '/' .
            $this->getDeployement()->getId();
    }

    public function getHref(): string
    {
        return $this->getUploadDir() . '/' .
            $this->getFileName() . '.' .  $this->getFileExtension() ;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $actionsFile): DeployementFile
    {
        $this->file = $actionsFile;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getNbrView(): ?int
    {
        return $this->nbrView;
    }

    public function setNbrView(int $nbrView): self
    {
        $this->nbrView = $nbrView;

        return $this;
    }

    public function getDeployement(): ?Deployement
    {
        return $this->deployement;
    }

    public function setDeployement(?Deployement $deployement): self
    {
        $this->deployement = $deployement;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }


    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
