<?php

namespace App\Entity;

use App\Repository\TripRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private Vehicle|null $Vehicle;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private Driver|null $Driver;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[ORM\JoinColumn(nullable: false)]
    private DateTimeInterface|null $Date;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getVehicle(): Vehicle|null
    {
        return $this->Vehicle;
    }

    public function setVehicle(Vehicle|null $Vehicle): static
    {
        $this->Vehicle = $Vehicle;

        return $this;
    }

    public function getDriver(): Driver|null
    {
        return $this->Driver;
    }

    public function setDriver(Driver|null $Driver): static
    {
        $this->Driver = $Driver;

        return $this;
    }

    public function getDate(): DateTimeInterface|null
    {
        return $this->Date;
    }

    public function setDate(DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }
}
