<?php

namespace App\Entity;

use App\Repository\ActionMailHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionMailHistoryRepository::class)
 */
class ActionMailHistory implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Action::class, inversedBy="actionMailHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendAt;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct() {
        $this->getSendAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSendAt(): ?\DateTimeInterface
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeInterface $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
