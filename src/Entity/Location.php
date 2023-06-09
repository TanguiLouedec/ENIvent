<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("city_data")]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("city_data")]
    #[Assert\Length(
        min:2,
        max:255,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    public ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min:1,
        max:255,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min:-100,
        max:100,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    private ?string $latitude = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min:-100,
        max:100,
        minMessage: "Minimum {{ limit }} character please !",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    private ?string $longitude = null;

    #[ORM\ManyToOne(inversedBy: 'location')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups("city_data")]

    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Event::class, orphanRemoval: true)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setLocation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getLocation() === $this) {
                $event->setLocation(null);
            }
        }

        return $this;
    }
}
