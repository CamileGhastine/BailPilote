<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    const STATUS_DISPONIBLE = 'disponible';
    const STATUS_LOUE       = 'loue';
    const STATUS_EN_TRAVAUX = 'en_travaux';

    const TYPE_MAISON      = 'maison';
    const TYPE_APPARTEMENT = 'appartement';
    const TYPE_TERRAIN     = 'terrain';
    const TYPE_PARKING     = 'parking';

    const ECO_A = 'A';
    const ECO_B = 'B';
    const ECO_C = 'C';
    const ECO_D = 'D';
    const ECO_E = 'E';
    const ECO_F = 'F';
    const ECO_G = 'G';

    const CRITERIA_CUISINE_AMENAGEE  = 'cuisine_amenagee';
    const CRITERIA_BALCON_TERRASSE   = 'balcon_terrasse';
    const CRITERIA_GARAGE_BOX        = 'garage_box';
    const CRITERIA_ASCENSEUR         = 'ascenseur';
    const CRITERIA_CHAUFFAGE_CENTRAL = 'chauffage_central';
    const CRITERIA_CLIMATISATION     = 'climatisation';
    const CRITERIA_PARKING           = 'parking';
    const CRITERIA_JARDIN            = 'jardin';
    const CRITERIA_PISCINE           = 'piscine';

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

    #[ORM\Column(name: 'numberOfRooms', nullable: true)]
    private ?int $numberOfRooms = null;

    #[ORM\Column(name: 'numberOfBedrooms', nullable: true)]
    private ?int $numberOfBedrooms = null;

    #[ORM\Column(name: 'ecoNote', length: 255, nullable: true)]
    private ?string $ecoNote = null;

    #[ORM\Column(name: 'gesNote', length: 255, nullable: true)]
    private ?string $gesNote = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::JSON)]
    private array $criteria = [];

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

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Image::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

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

    public function getNumberOfRooms(): ?int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(?int $numberOfRooms): static
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    public function getNumberOfBedrooms(): ?int
    {
        return $this->numberOfBedrooms;
    }

    public function setNumberOfBedrooms(?int $numberOfBedrooms): static
    {
        $this->numberOfBedrooms = $numberOfBedrooms;

        return $this;
    }

    public function getEcoNote(): ?string
    {
        return $this->ecoNote;
    }

    public function setEcoNote(?string $ecoNote): static
    {
        $this->ecoNote = $ecoNote;

        return $this;
    }

    public function getGesNote(): ?string
    {
        return $this->gesNote;
    }

    public function setGesNote(?string $gesNote): static
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

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function setCriteria(array $criteria): static
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

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProperty($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        $this->images->removeElement($image);

        return $this;
    }
}
