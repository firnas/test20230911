<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column(length: 255)]
    private string|null $Brand = null;

    #[ORM\Column(length: 255)]
    private string|null $Model = null;

    #[ORM\Column(length: 255)]
    private string|null $Plate = null;

    #[ORM\Column(length: 1)]
    private string|null $LicenseRequired = null;

    #[ORM\OneToMany(mappedBy: 'Vehicle', targetEntity: Trip::class)]
    private Collection $trips;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getBrand(): string|null
    {
        return $this->Brand;
    }

    public function setBrand(string $Brand): static
    {
        $this->Brand = $Brand;

        return $this;
    }

    public function getModel(): string|null
    {
        return $this->Model;
    }

    public function setModel(string $Model): static
    {
        $this->Model = $Model;

        return $this;
    }

    public function getPlate(): string|null
    {
        return $this->Plate;
    }

    public function setPlate(string $Plate): static
    {
        $this->Plate = $Plate;

        return $this;
    }

    public function getLicenseRequired(): string|null
    {
        return $this->LicenseRequired;
    }

    public function setLicenseRequired(string $LicenseRequired): static
    {
        $this->LicenseRequired = $LicenseRequired;

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
            $trip->setVehicle($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        // set the owning side to null (unless already changed)
        if ($this->trips->removeElement($trip) && $trip->getVehicle() === $this) {
            $trip->setVehicle(null);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->id;
    }


}
