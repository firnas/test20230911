<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column(length: 255)]
    private string|null $Name = null;

    #[ORM\Column(length: 255)]
    private string|null $Surname = null;

    #[ORM\Column(length: 1)]
    private string|null $License = null;

    #[ORM\OneToMany(mappedBy: 'Driver', targetEntity: Trip::class)]
    private Collection $trips;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string|null
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getSurname(): string|null
    {
        return $this->Surname;
    }

    public function setSurname(string $Surname): static
    {
        $this->Surname = $Surname;

        return $this;
    }

    public function getLicense(): string|null
    {
        return $this->License;
    }

    public function setLicense(string $License): static
    {
        $this->License = $License;

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setDriver($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        // set the owning side to null (unless already changed)
        if ($this->trips->removeElement($trip) && $trip->getDriver() === $this) {
            $trip->setDriver(null);
        }

        return $this;
    }

    public function __toString(): string
    {
     return $this->id;
    }


}
