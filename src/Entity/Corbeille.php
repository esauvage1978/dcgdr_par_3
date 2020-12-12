<?php

namespace App\Entity;

use App\Repository\CorbeilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CorbeilleRepository::class)
 */
class Corbeille implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isUseByDefault;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowRead;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowWrite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowValidate;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="corbeilles")
     * @ORM\OrderBy({"name" = "ASC"})
     *
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Organisme::class, inversedBy="corbeilles")
     */
    private $organisme;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Action", mappedBy="readers")
     * @ORM\JoinTable("actionreader_corbeille")
     */
    private $actionReaders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Action", mappedBy="writers")
     * @ORM\JoinTable("actionwriter_corbeille")
     */
    private $actionWriters;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Action", mappedBy="validers")
     * @ORM\JoinTable("actionvalider_corbeille")
     */
    private $actionValiders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Deployement", mappedBy="writers")
     * @ORM\JoinTable("deployementwriter_corbeille")
     */
    private $deployementWriters;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Deployement", mappedBy="readers")
     * @ORM\JoinTable("deployementreader_corbeille")
     */
    private $deployementReaders;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->actionReaders = new ArrayCollection();
        $this->actionWriters = new ArrayCollection();
        $this->actionValiders = new ArrayCollection();
        $this->deployementWriters = new ArrayCollection();
        $this->deployementReaders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFullName(): ?string
    {
        return (null !== $this->organisme) ?
            $this->getOrganisme()->getRef().' - '.$this->getName() :
            $this->getName();
    }

    public function getIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

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

    public function getIsUseByDefault(): ?bool
    {
        return $this->isUseByDefault;
    }

    public function setIsUseByDefault(bool $isUseByDefault): self
    {
        $this->isUseByDefault = $isUseByDefault;

        return $this;
    }


    public function getIsShowRead(): ?bool
    {
        return $this->isShowRead;
    }

    public function setIsShowRead(bool $isShowRead): self
    {
        $this->isShowRead = $isShowRead;

        return $this;
    }

    public function setIsShowWrite(bool $isShowWrite): self
    {
        $this->isShowWrite = $isShowWrite;

        return $this;
    }

    public function getIsShowWrite(): ?bool
    {
        return $this->isShowWrite;
    }

    public function setIsShowValidate(bool $isShowValidate): self
    {
        $this->isShowValidate = $isShowValidate;

        return $this;
    }


    public function getIsShowValidate(): ?bool
    {
        return $this->isShowValidate;
    }



    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getOrganisme(): ?Organisme
    {
        return $this->organisme;
    }

    public function setOrganisme(?Organisme $organisme): self
    {
        $this->organisme = $organisme;

        return $this;
    }

    /**
     * @return Collection|Action[]
     */
    public function getActionReaders(): Collection
    {
        return $this->actionReaders;
    }

    public function addActionReader(Action $actionReader): self
    {
        if (!$this->actionReaders->contains($actionReader)) {
            $this->actionReaders[] = $actionReader;
            $actionReader->addReader($this);
        }

        return $this;
    }

    public function removeActionReader(Action $actionReader): self
    {
        if ($this->actionReaders->contains($actionReader)) {
            $this->actionReaders->removeElement($actionReader);
            $actionReader->removeReader($this);
        }

        return $this;
    }

    /**
     * @return Collection|Action[]
     */
    public function getActionWriters(): Collection
    {
        return $this->actionWriters;
    }

    public function addActionWriter(Action $actionWriter): self
    {
        if (!$this->actionWriters->contains($actionWriter)) {
            $this->actionWriters[] = $actionWriter;
            $actionWriter->addWriter($this);
        }

        return $this;
    }

    public function removeActionWriter(Action $actionWriter): self
    {
        if ($this->actionWriters->contains($actionWriter)) {
            $this->actionWriters->removeElement($actionWriter);
            $actionWriter->removeWriter($this);
        }

        return $this;
    }

    /**
     * @return Collection|Action[]
     */
    public function getActionValiders(): Collection
    {
        return $this->actionValiders;
    }

    public function addActionValider(Action $actionValider): self
    {
        if (!$this->actionValiders->contains($actionValider)) {
            $this->actionValiders[] = $actionValider;
            $actionValider->addValider($this);
        }

        return $this;
    }

    public function removeActionValider(Action $actionValider): self
    {
        if ($this->actionValiders->contains($actionValider)) {
            $this->actionValiders->removeElement($actionValider);
            $actionValider->removeValider($this);
        }

        return $this;
    }

    /**
     * @return Collection|Deployement[]
     */
    public function getDeployementWriters(): Collection
    {
        return $this->deployementWriters;
    }

    public function addDeployementWriter(Deployement $deployementWriter): self
    {
        if (!$this->deployementWriters->contains($deployementWriter)) {
            $this->deployementWriters[] = $deployementWriter;
            $deployementWriter->addWriter($this);
        }

        return $this;
    }

    public function removeDeployementWriter(Deployement $deployementWriter): self
    {
        if ($this->deployementWriters->contains($deployementWriter)) {
            $this->deployementWriters->removeElement($deployementWriter);
            $deployementWriter->removeWriter($this);
        }

        return $this;
    }
    /**
     * @return Collection|Deployement[]
     */
    public function getDeployementReaders(): Collection
    {
        return $this->deployementReaders;
    }

    public function addDeployementReader(Deployement $deployementReader): self
    {
        if (!$this->deployementReaders->contains($deployementReader)) {
            $this->deployementReaders[] = $deployementReader;
            $deployementReader->addReader($this);
        }

        return $this;
    }

    public function removeDeployementReader(Deployement $deployementReader): self
    {
        if ($this->deployementReaders->contains($deployementReader)) {
            $this->deployementReaders->removeElement($deployementReader);
            $deployementReader->removeReader($this);
        }

        return $this;
    }
}
