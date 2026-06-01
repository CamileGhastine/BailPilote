<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $type;

    #[ORM\Column]
    private float $area;

    #[ORM\Column(name: 'numberOfRooms')]
    private int $numberOfRooms;

    #[ORM\Column(name: 'numberOfBedrooms')]
    private int $numberOfBedrooms;

    #[ORM\Column(name: 'ecoNote', length: 255)]
    private string $ecoNote;

    #[ORM\Column(name: 'gesNote', length: 255)]
    private string $gesNote;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::TEXT)]
    private string $criteria;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Address $address;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Owner $owner;

    #[ORM\OneToOne(mappedBy: 'property', cascade: ['persist', 'remove'])]
    private ?Lease $lease = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getArea(): float
    {
        return $this->area;
    }

    public function setArea(float $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getNumberOfRooms(): int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(int $numberOfRooms): static
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    public function getNumberOfBedrooms(): int
    {
        return $this->numberOfBedrooms;
    }

    public function setNumberOfBedrooms(int $numberOfBedrooms): static
    {
        $this->numberOfBedrooms = $numberOfBedrooms;

        return $this;
    }

    public function getEcoNote(): string
    {
        return $this->ecoNote;
    }

    public function setEcoNote(string $ecoNote): static
    {
        $this->ecoNote = $ecoNote;

        return $this;
    }

    public function getGesNote(): string
    {
        return $this->gesNote;
    }

    public function setGesNote(string $gesNote): static
    {
        $this->gesNote = $gesNote;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCriteria(): string
    {
        return $this->criteria;
    }

    public function setCriteria(string $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }

    public function setOwner(Owner $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getLease(): ?Lease
    {
        return $this->lease;
    }

    public function setLease(Lease $lease): static
    {
        // set the owning side of the relation if necessary
        if ($lease->getProperty() !== $this) {
            $lease->setProperty($this);
        }

        $this->lease = $lease;

        return $this;
    }
}
