<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use function array_unique;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */class User implements UserInterface, EntityInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    private $username;

    /** @ORM\Column(type="string", length=180, unique=true) */
    private $email;

    /** @ORM\Column(type="json") */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @var string The hashed password
     */
    private $password;

    /** @ORM\Column(type="string", length=100) */
    private $name;

    /** @ORM\Column(type="boolean") */
    private $emailValidated;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $emailValidatedToken;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    private $forget_token;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $loginAt;

    /** @var ?string */
    private $plainPassword;

    /** @var ?string */
    private $plainPasswordConfirmation;
    
    /** @ORM\Column(type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $modifiedAt;


    /** @ORM\Column(type="boolean") */
    private $isEnable;

    /** @ORM\Column(type="text", nullable=true) */
    private $content;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=UserParam::class, cascade={"persist", "remove"})
     */
    private $userParam;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organisme", mappedBy="users")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $organismes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", mappedBy="users")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $corbeilles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IndicatorValueHistory", mappedBy="user")
     */
    private $indicatorValueHistories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionState", mappedBy="user")
     */
    private $actionStates;

    /**
     * @ORM\OneToMany(targetEntity="History", mappedBy="user", orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accountValidated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accountValidatedToken;


    public function __construct()
    {
        $this->setIsEnable(true);
        $this->setEmailValidated(false);
        $this->organismes = new ArrayCollection();
        $this->corbeilles = new ArrayCollection();
        $this->indicatorValueHistories = new ArrayCollection();
        $this->actionStates = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->setAccountValidated(false);
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPasswordConfirmation(string $plainPasswordConfirmation): self
    {
        $this->plainPasswordConfirmation = $plainPasswordConfirmation;

        return $this;
    }

    public function getPlainPasswordConfirmation(): ?string
    {
        return $this->plainPasswordConfirmation;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): void
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
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

    public function getEmailValidated(): ?bool
    {
        return $this->emailValidated;
    }

    public function setEmailValidated(bool $emailValidated): self
    {
        $this->emailValidated = $emailValidated;

        return $this;
    }

    public function getEmailValidatedToken(): ?string
    {
        return $this->emailValidatedToken;
    }

    public function setEmailValidatedToken(?string $emailValidatedToken): self
    {
        $this->emailValidatedToken = $emailValidatedToken;

        return $this;
    }

    public function getLoginAt(): ?DateTimeInterface
    {
        return $this->loginAt;
    }

    public function setLoginAt(?DateTimeInterface $loginAt): self
    {
        $this->loginAt = $loginAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

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

    public function getForgetToken(): ?string
    {
        return $this->forget_token;
    }

    public function setForgetToken(?string $forget_token): self
    {
        $this->forget_token = $forget_token;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar(): string
    {
        return 'avatar/' . $this->getId() . '.png';
    }

    public function getUserParam(): ?UserParam
    {
        return $this->userParam;
    }

    public function setUserParam(?UserParam $userParam): self
    {
        $this->userParam = $userParam;

        return $this;
    }


    /**
     * @return Collection|Organisme[]
     */
    public function getOrganismes(): Collection
    {
        return $this->organismes;
    }

    public function addOrganisme(Organisme $organisme): self
    {
        if (!$this->organismes->contains($organisme)) {
            $this->organismes[] = $organisme;
            $organisme->addUser($this);
        }

        return $this;
    }

    public function removeOrganisme(Organisme $organisme): self
    {
        if ($this->organismes->contains($organisme)) {
            $this->organismes->removeElement($organisme);
            $organisme->removeUser($this);
        }

        return $this;
    }


    /**
     * @return Collection|Corbeille[]
     */
    public function getCorbeilles(): Collection
    {
        return $this->corbeilles;
    }

    public function addCorbeille(Corbeille $corbeille): self
    {
        if (!$this->corbeilles->contains($corbeille)) {
            $this->corbeilles[] = $corbeille;
            $corbeille->addUser($this);
        }

        return $this;
    }

    public function removeCorbeille(Corbeille $corbeille): self
    {
        if ($this->corbeilles->contains($corbeille)) {
            $this->corbeilles->removeElement($corbeille);
            $corbeille->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|IndicatorValueHistory[]
     */
    public function getIndicatorValueHistories(): Collection
    {
        return $this->indicatorValueHistories;
    }

    public function addIndicatorValueHistory(IndicatorValueHistory $indicatorValueHistory): self
    {
        if (!$this->indicatorValueHistories->contains($indicatorValueHistory)) {
            $this->indicatorValueHistories[] = $indicatorValueHistory;
            $indicatorValueHistory->setUser($this);
        }

        return $this;
    }

    public function removeIndicatorValueHistory(IndicatorValueHistory $indicatorValueHistory): self
    {
        if ($this->indicatorValueHistories->contains($indicatorValueHistory)) {
            $this->indicatorValueHistories->removeElement($indicatorValueHistory);
            // set the owning side to null (unless already changed)
            if ($indicatorValueHistory->getUser() === $this) {
                $indicatorValueHistory->setUser(null);
            }
        }

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
            $actionState->setUser($this);
        }

        return $this;
    }

    public function removeActionState(ActionState $actionState): self
    {
        if ($this->actionStates->contains($actionState)) {
            $this->actionStates->removeElement($actionState);
            // set the owning side to null (unless already changed)
            if ($actionState->getUser() === $this) {
                $actionState->setUser(null);
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
            $history->setUser($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getUser() === $this) {
                $history->setUser(null);
            }
        }

        return $this;
    }
        /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getAccountValidated(): ?bool
    {
        return $this->accountValidated;
    }

    public function setAccountValidated(bool $accountValidated): self
    {
        $this->accountValidated = $accountValidated;

        return $this;
    }

    public function getAccountValidatedToken(): ?string
    {
        return $this->accountValidatedToken;
    }

    public function setAccountValidatedToken(?string $accountValidatedToken): self
    {
        $this->accountValidatedToken = $accountValidatedToken;

        return $this;
    }

}
