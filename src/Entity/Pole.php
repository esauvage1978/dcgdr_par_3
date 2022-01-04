<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoleRepository")
 */
class Pole implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
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
     * @ORM\Column(type="integer")
     */
    private $taux1;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Axe", inversedBy="poles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $axe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Thematique", mappedBy="pole", orphanRemoval=true)
     */
    private $thematiques;


    public function __construct()
    {
        $this->setTaux1(0);
        $this->setTaux2(0);
        $this->thematiques = new ArrayCollection();
        $this->setIsEnable(true);
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

    public function getAxe(): ?Axe
    {
        return $this->axe;
    }

    public function setAxe(?Axe $axe): self
    {
        $this->axe = $axe;

        return $this;
    }

    /**
     * @return Collection|Thematique[]
     */
    public function getThematiques(): Collection
    {
        return $this->thematiques;
    }

    public function addThematique(Thematique $thematique): self
    {
        if (!$this->thematiques->contains($thematique)) {
            $this->thematiques[] = $thematique;
            $thematique->setPole($this);
        }

        return $this;
    }

    public function removeThematique(Thematique $thematique): self
    {
        if ($this->thematiques->contains($thematique)) {
            $this->thematiques->removeElement($thematique);
            // set the owning side to null (unless already changed)
            if ($thematique->getPole() === $this) {
                $thematique->setPole(null);
            }
        }

        return $this;
    }

}
