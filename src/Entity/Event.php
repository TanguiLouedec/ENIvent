<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The event name is mandatory !")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeInterface $dateTimeStart = null;

    #[ORM\Column]
    #[Assert\Range(notInRangeMessage: "Not in  (range : 0 to 1440)", min: 0, max: 1440)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(propertyPath: "dateTimeStart")]
    private ?\DateTimeInterface $dateLimitRegistration = null;

    #[ORM\Column]
    #[Assert\Range(notInRangeMessage: "Not in range (range : 0 to 200)", min: 0, max: 200)]
    private ?int $numMaxRegistration = null;

    #[ORM\Column(length: 6000)]
    #[Assert\Length(
        min: 10,
        max: 6000,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    private ?string $infoEvent = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'events')]
    private ?State $state = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $users;

    #[ORM\Column(length: 6000, nullable: true)]
    #[Assert\Length(
        min: 10,
        max: 6000,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    private ?string $deleteComment = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateTimeStart(): ?\DateTimeInterface
    {
        return $this->dateTimeStart;
    }

    public function setDateTimeStart(\DateTimeInterface $dateTimeStart): self
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        return $this->dateLimitRegistration;
    }

    public function setDateLimitRegistration(\DateTimeInterface $dateLimitRegistration): self
    {
        $this->dateLimitRegistration = $dateLimitRegistration;

        return $this;
    }

    public function getNumMaxRegistration(): ?int
    {
        return $this->numMaxRegistration;
    }

    public function setNumMaxRegistration(int $numMaxRegistration): self
    {
        $this->numMaxRegistration = $numMaxRegistration;

        return $this;
    }

    public function getInfoEvent(): ?string
    {
        return $this->infoEvent;
    }

    public function setInfoEvent(string $infoEvent): self
    {
        $this->infoEvent = $infoEvent;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getDeleteComment(): ?string
    {
        return $this->deleteComment;
    }

    public function setDeleteComment(?string $deleteComment): self
    {
        $this->deleteComment = $deleteComment;

        return $this;
    }

    public function UserCount(?int $id): int
    {
        $eventUsers = $this->getUsers();
        return count($eventUsers);
    }
}