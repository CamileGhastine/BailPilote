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
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $area = null;

    #[ORM\Column]
    private ?int $number_of_romms = null;

    #[ORM\Column]
    private ?int $number_of_bedrooms = null;

    #[ORM\Column(length: 255)]
    private ?string $eco_note = null;

    #[ORM\Column(length: 255)]
    private ?string $ges_note = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $criteria = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Owner $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getNumberOfRomms(): ?int
    {
        return $this->number_of_romms;
    }

    public function setNumberOfRomms(int $number_of_romms): static
    {
        $this->number_of_romms = $number_of_romms;

        return $this;
    }

    public function getNumberOfBedrooms(): ?int
    {
        return $this->number_of_bedrooms;
    }

    public function setNumberOfBedrooms(int $number_of_bedrooms): static
    {
        $this->number_of_bedrooms = $number_of_bedrooms;

        return $this;
    }

    public function getEcoNote(): ?string
    {
        return $this->eco_note;
    }

    public function setEcoNote(string $eco_note): static
    {
        $this->eco_note = $eco_note;

        return $this;
    }

    public function getGesNote(): ?string
    {
        return $this->ges_note;
    }

    public function setGesNote(string $ges_note): static
    {
        $this->ges_note = $ges_note;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCriteria(): ?string
    {
        return $this->criteria;
    }

    public function setCriteria(string $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
