<?php

namespace App\Entity;

use App\Workflow\WorkflowData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action implements EntityInterface
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $regionStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $regionEndAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux1;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExperimental;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowAll;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cadrage;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $ref;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $measureValue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $measureContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="actions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cible", inversedBy="actions")
     */
    private $cibles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vecteur", inversedBy="actions")
     */
    private $vecteurs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionReaders")
     * @ORM\JoinTable("actionreader_corbeille")
     */
    private $readers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionWriters")
     * @ORM\JoinTable("actionwriter_corbeille")
     */
    private $writers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionCOTECHValiders")
     * @ORM\JoinTable("actioncotechvalider_corbeille")
     */
    private $COTECHValiders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionCODIRValiders")
     * @ORM\JoinTable("actioncodirvalider_corbeille")
     */
    private $CODIRValiders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deployement", mappedBy="action", orphanRemoval=true)
     */
    private $deployements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Indicator", mappedBy="action", orphanRemoval=true)
     */
    private $indicators;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionFile", mappedBy="action", orphanRemoval=true,cascade={"persist"})
     */
    private $actionFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionLink", mappedBy="action", orphanRemoval=true,cascade={"persist"})
     */
    private $actionLinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CadrageLink", mappedBy="action", orphanRemoval=true,cascade={"persist"})
     */
    private $cadrageLinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CadrageFile", mappedBy="action", orphanRemoval=true,cascade={"persist"})
     */
    private $cadrageFiles;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $stateCurrent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stateAt;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $stateContent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionState", mappedBy="action", orphanRemoval=true)
     */
    private $actionStates;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $showAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mailer", mappedBy="action", orphanRemoval=true)
     */
    private $mailers;

    /**
     * @ORM\OneToMany(targetEntity=ActionMailHistory::class, mappedBy="action", orphanRemoval=true)
     */
    private $actionMailHistories;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="action", orphanRemoval=true)
     */
    private $histories;

    public function __construct()
    {
        $this->setStateCurrent(WorkflowData::STATE_STARTED);
        $this->setStateAt(new \DateTime());

        $this->setTaux1('0');
        $this->setTaux2('0');
        $this->setIsShowAll(true);
        $this->setIsExperimental(false);
        $this->cibles = new ArrayCollection();
        $this->vecteurs = new ArrayCollection();
        $this->readers = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->COTECHValiders = new ArrayCollection();
        $this->CODIRValiders = new ArrayCollection();
        $this->deployements = new ArrayCollection();
        $this->indicators = new ArrayCollection();
        $this->actionFiles = new ArrayCollection();
        $this->actionLinks = new ArrayCollection();
        $this->cadrageLinks = new ArrayCollection();
        $this->cadrageFiles = new ArrayCollection();
        $this->actionStates = new ArrayCollection();
        $this->mailers = new ArrayCollection();
        $this->actionMailHistories = new ArrayCollection();
        $this->histories = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRegionStartAt(): ?\DateTimeInterface
    {
        return $this->regionStartAt;
    }

    public function setRegionStartAt(?\DateTimeInterface $regionStartAt): self
    {
        $this->regionStartAt = $regionStartAt;

        return $this;
    }

    public function getRegionEndAt(): ?\DateTimeInterface
    {
        return $this->regionEndAt;
    }

    public function setRegionEndAt(?\DateTimeInterface $regionEndAt): self
    {
        $this->regionEndAt = $regionEndAt;

        return $this;
    }

    public function getTaux1(): ?int
    {
        return $this->taux1;
    }

    public function setTaux1(int $taux1): self
    {
        $this->taux1 = $taux1;

        return $this;
    }

    public function getTaux2(): ?int
    {
        return $this->taux2;
    }

    public function setTaux2(int $taux2): self
    {
        $this->taux2 = $taux2;

        return $this;
    }

    public function getIsExperimental(): ?bool
    {
        return $this->isExperimental;
    }

    public function setIsExperimental(bool $isExperimental): self
    {
        $this->isExperimental = $isExperimental;

        return $this;
    }

    public function getIsShowAll(): ?bool
    {
        return $this->isShowAll;
    }

    public function setIsShowAll(bool $isShowAll): self
    {
        $this->isShowAll = $isShowAll;

        return $this;
    }

    public function getCadrage(): ?string
    {
        return $this->cadrage;
    }

    public function setCadrage(?string $cadrage): self
    {
        $this->cadrage = $cadrage;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->getRef().' - '.$this->getName();
    }

    public function getMeasureValue(): ?int
    {
        return $this->measureValue;
    }

    public function setMeasureValue(?int $measureValue): self
    {
        $this->measureValue = $measureValue;

        return $this;
    }

    public function getMeasureContent(): ?string
    {
        return $this->measureContent;
    }

    public function setMeasureContent(?string $measureContent): self
    {
        $this->measureContent = $measureContent;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Cible[]
     */
    public function getCibles(): Collection
    {
        return $this->cibles;
    }

    public function addCible(Cible $cible): self
    {
        if (!$this->cibles->contains($cible)) {
            $this->cibles[] = $cible;
        }

        return $this;
    }

    public function removeCible(Cible $cible): self
    {
        if ($this->cibles->contains($cible)) {
            $this->cibles->removeElement($cible);
        }

        return $this;
    }

    /**
     * @return Collection|Vecteur[]
     */
    public function getVecteurs(): Collection
    {
        return $this->vecteurs;
    }

    public function addVecteur(Vecteur $vecteur): self
    {
        if (!$this->vecteurs->contains($vecteur)) {
            $this->vecteurs[] = $vecteur;
        }

        return $this;
    }

    public function removeVecteur(Vecteur $vecteur): self
    {
        if ($this->vecteurs->contains($vecteur)) {
            $this->vecteurs->removeElement($vecteur);
        }

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Corbeille $reader): self
    {
        if (!$this->readers->contains($reader)) {
            $this->readers[] = $reader;
        }

        return $this;
    }

    public function removeReader(Corbeille $reader): self
    {
        if ($this->readers->contains($reader)) {
            $this->readers->removeElement($reader);
        }

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Corbeille $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
        }

        return $this;
    }

    public function removeWriter(Corbeille $writer): self
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
        }

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getCOTECHValiders(): Collection
    {
        return $this->COTECHValiders;
    }

    public function addCOTECHValider(Corbeille $COTECHValider): self
    {
        if (!$this->COTECHValiders->contains($COTECHValider)) {
            $this->COTECHValiders[] = $COTECHValider;
        }

        return $this;
    }

    public function removeCOTECHValider(Corbeille $COTECHValider): self
    {
        if ($this->COTECHValiders->contains($COTECHValider)) {
            $this->COTECHValiders->removeElement($COTECHValider);
        }

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getCODIRValiders(): Collection
    {
        return $this->CODIRValiders;
    }

    public function addCODIRValider(Corbeille $CODIRValider): self
    {
        if (!$this->CODIRValiders->contains($CODIRValider)) {
            $this->CODIRValiders[] = $CODIRValider;
        }

        return $this;
    }

    public function removeCODIRValider(Corbeille $CODIRValider): self
    {
        if ($this->CODIRValiders->contains($CODIRValider)) {
            $this->CODIRValiders->removeElement($CODIRValider);
        }

        return $this;
    }

    /**
     * @return Collection|Deployement[]
     */
    public function getDeployements(): Collection
    {
        return $this->deployements;
    }

    public function addDeployement(Deployement $deployement): self
    {
        if (!$this->deployements->contains($deployement)) {
            $this->deployements[] = $deployement;
            $deployement->setAction($this);
        }

        return $this;
    }

    public function removeDeployement(Deployement $deployement): self
    {
        if ($this->deployements->contains($deployement)) {
            $this->deployements->removeElement($deployement);
            // set the owning side to null (unless already changed)
            if ($deployement->getAction() === $this) {
                $deployement->setAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Indicator[]
     */
    public function getIndicators(): Collection
    {
        return $this->indicators;
    }

    public function addIndicator(Indicator $indicator): self
    {
        if (!$this->indicators->contains($indicator)) {
            $this->indicators[] = $indicator;
            $indicator->setAction($this);
        }

        return $this;
    }

    public function removeIndicator(Indicator $indicator): self
    {
        if ($this->indicators->contains($indicator)) {
            $this->indicators->removeElement($indicator);
            // set the owning side to null (unless already changed)
            if ($indicator->getAction() === $this) {
                $indicator->setAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ActionFile[]
     */
    public function getActionFiles(): Collection
    {
        return $this->actionFiles;
    }

    public function addActionFile(ActionFile $actionFile): self
    {
        if (!$this->actionFiles->contains($actionFile)) {
            $this->actionFiles[] = $actionFile;
            $actionFile->setAction($this);
        }

        return $this;
    }



    /**
     * @return Collection|ActionLink[]
     */
    public function getActionLinks(): Collection
    {
        return $this->actionLinks;
    }

    public function addActionLink(ActionLink $actionLink): self
    {
        if (!$this->actionLinks->contains($actionLink)) {
            $this->actionLinks[] = $actionLink;
            $actionLink->setAction($this);
        }

        return $this;
    }

    public function removeActionLink(ActionLink $actionLink): self
    {
        if ($this->actionLinks->contains($actionLink)) {
            $this->actionLinks->removeElement($actionLink);
            // set the owning side to null (unless already changed)
            if ($actionLink->getAction() === $this) {
                $actionLink->setAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CadrageLink[]
     */
    public function getCadrageLinks(): Collection
    {
        return $this->cadrageLinks;
    }

    public function addCadrageLink(CadrageLink $cadrageLink): self
    {
        if (!$this->cadrageLinks->contains($cadrageLink)) {
            $this->cadrageLinks[] = $cadrageLink;
            $cadrageLink->setAction($this);
        }

        return $this;
    }

    public function removeCadrageLink(CadrageLink $cadrageLink): self
    {
        if ($this->cadrageLinks->contains($cadrageLink)) {
            $this->cadrageLinks->removeElement($cadrageLink);
            // set the owning side to null (unless already changed)
            if ($cadrageLink->getAction() === $this) {
                $cadrageLink->setAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CadrageFile[]
     */
    public function getCadrageFiles(): Collection
    {
        return $this->cadrageFiles;
    }

    public function addCadrageFile(CadrageFile $cadrageFile): self
    {
        if (!$this->cadrageFiles->contains($cadrageFile)) {
            $this->cadrageFiles[] = $cadrageFile;
            $cadrageFile->setAction($this);
        }

        return $this;
    }

    public function removeCadrageFile(CadrageFile $cadrageFile): self
    {
        if ($this->cadrageFiles->contains($cadrageFile)) {
            $this->cadrageFiles->removeElement($cadrageFile);
            // set the owning side to null (unless already changed)
            if ($cadrageFile->getAction() === $this) {
                $cadrageFile->setAction(null);
            }
        }

        return $this;
    }

    public function removeActionFile(ActionFile $actionFile): self
    {
        if ($this->actionFiles->contains($actionFile)) {
            $this->actionFiles->removeElement($actionFile);
            // set the owning side to null (unless already changed)
            if ($actionFile->getAction() === $this) {
                $actionFile->setAction(null);
            }
        }

        return $this;
    }

    public function getStateCurrent(): ?string
    {
        return $this->stateCurrent;
    }

    public function setStateCurrent(string $stateCurrent): self
    {
        $this->stateCurrent = $stateCurrent;

        return $this;
    }

    public function getStateAt(): ?\DateTimeInterface
    {
        return $this->stateAt;
    }

    public function setStateAt(\DateTimeInterface $stateAt): self
    {
        $this->stateAt = $stateAt;

        return $this;
    }

    public function getStateContent(): ?string
    {
        return $this->stateContent;
    }

    public function setStateContent(?string $stateContent): self
    {
        $this->stateContent = $stateContent;

        return $this;
    }

    /**
     * @return Collection|ActionState[]
     */
    public function getActionStates(): Collection
    {
        return $this->actionStates;
    }

    public function addActionState(ActionState $actionState): self
    {
        if (!$this->actionStates->contains($actionState)) {
            $this->actionStates[] = $actionState;
            $actionState->setAction($this);
        }

        return $this;
    }

    public function removeActionState(ActionState $actionState): self
    {
        if ($this->actionStates->contains($actionState)) {
            $this->actionStates->removeElement($actionState);
            // set the owning side to null (unless already changed)
            if ($actionState->getAction() === $this) {
                $actionState->setAction(null);
            }
        }

        return $this;
    }

    public function getShowAt(): ?\DateTimeInterface
    {
        return $this->showAt;
    }

    public function setShowAt(?\DateTimeInterface $showAt): self
    {
        $this->showAt = $showAt;

        return $this;
    }

    /**
     * @return Collection|Mailer[]
     */
    public function getMailers(): Collection
    {
        return $this->mailers;
    }

    public function addMailer(Mailer $mailer): self
    {
        if (!$this->mailers->contains($mailer)) {
            $this->mailers[] = $mailer;
            $mailer->setAction($this);
        }

        return $this;
    }

    public function removeMailer(Mailer $mailer): self
    {
        if ($this->mailers->contains($mailer)) {
            $this->mailers->removeElement($mailer);
            // set the owning side to null (unless already changed)
            if ($mailer->getAction() === $this) {
                $mailer->setAction(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|ActionMailHistory[]
     */
    public function getActionMailHistories(): Collection
    {
        return $this->actionMailHistories;
    }

    public function addActionMailHistory(ActionMailHistory $actionMailHistory): self
    {
        if (!$this->actionMailHistories->contains($actionMailHistory)) {
            $this->actionMailHistories[] = $actionMailHistory;
            $actionMailHistory->setAction($this);
        }

        return $this;
    }

    public function removeActionMailHistory(ActionMailHistory $actionMailHistory): self
    {
        if ($this->actionMailHistories->removeElement($actionMailHistory)) {
            // set the owning side to null (unless already changed)
            if ($actionMailHistory->getAction() === $this) {
                $actionMailHistory->setAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setAction($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getAction() === $this) {
                $history->setAction(null);
            }
        }

        return $this;
    }

}
