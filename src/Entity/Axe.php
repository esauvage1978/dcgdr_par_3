<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AxeRepository")
 */
class Axe implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
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
     * @ORM\Column(type="boolean")
     */
    private $isArchiving;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pole", mappedBy="axe", orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $poles;

    public function __construct()
    {
        $this->setIsEnable(true);
        $this->setIsArchiving(false);
        $this->setTaux1('0');
        $this->setTaux2('0');
        $this->poles = new ArrayCollection();
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

    public function getIsArchiving(): ?bool
    {
        return $this->isArchiving;
    }

    public function setIsArchiving(bool $isArchiving): self
    {
        $this->isArchiving = $isArchiving;

        return $this;
    }

    /**
     * @return Collection|Pole[]
     */
    public function getPoles(): Collection
    {
        return $this->poles;
    }

    public function addPole(Pole $pole): self
    {
        if (!$this->poles->contains($pole)) {
            $this->poles[] = $pole;
            $pole->setAxe($this);
        }

        return $this;
    }

    public function removePole(Pole $pole): self
    {
        if ($this->poles->contains($pole)) {
            $this->poles->removeElement($pole);
            // set the owning side to null (unless already changed)
            if ($pole->getAxe() === $this) {
                $pole->setAxe(null);
            }
        }

        return $this;
    }

}
