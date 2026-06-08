<?php

namespace App\Entity;

use App\Repository\LeaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: LeaseRepository::class)]
class Lease
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $type;

    #[ORM\Column (name: 'rentingAmount')]
    private float $rentingAmount;

    #[ORM\Column (name: 'leasedAt')]
    private \DateTimeImmutable $leasedAt;

    #[ORM\Column]
    private int $duration;

    #[ORM\Column(name: 'irlDate')]
    private \DateTimeImmutable $irlDate;

    #[ORM\Column]
    private float $irl;

    #[ORM\Column (name: 'securityDeposit')]
    private float $securityDeposit;

    #[ORM\Column (name: 'dateOfPayment')]
    private int $dateOfPayment;

    #[ORM\Column(type: Types::TEXT)]
    private string $guarantor;

    #[ORM\OneToOne(inversedBy: 'lease', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Property $property;

    /**
     * @var Collection<int, Tenant>
     */
    #[ORM\OneToMany(targetEntity: Tenant::class, mappedBy: 'lease')]
    private Collection $tenant;

    public function __construct()
    {
        $this->tenant = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getRentingAmount(): float
    {
        return $this->rentingAmount;
    }

    public function setRentingAmount(float $rentingAmount): static
    {
        $this->rentingAmount = $rentingAmount;

        return $this;
    }

    public function getLeasedAt(): \DateTimeImmutable
    {
        return $this->leasedAt;
    }

    public function setLeasedAt(\DateTimeImmutable $leasedAt): static
    {
        $this->leasedAt = $leasedAt;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getIrlDate(): \DateTimeImmutable
    {
        return $this->irlDate;
    }

    public function setIrlDate(\DateTimeImmutable $irlDate): static
    {
        $this->irlDate = $irlDate;

        return $this;
    }

    public function getIrl(): float
    {
        return $this->irl;
    }

    public function setIrl(float $irl): static
    {
        $this->irl = $irl;

        return $this;
    }

    public function getSecurityDeposit(): float
    {
        return $this->securityDeposit;
    }

    public function setSecurityDeposit(float $securityDeposit): static
    {
        $this->securityDeposit = $securityDeposit;

        return $this;
    }

    public function getDateOfPayment(): int
    {
        return $this->dateOfPayment;
    }

    public function setDateOfPayment(int $dateOfPayment): static
    {
        $this->dateOfPayment = $dateOfPayment;

        return $this;
    }

    public function getGuarantor(): string
    {
        return $this->guarantor;
    }

    public function setGuarantor(string $guarantor): static
    {
        $this->guarantor = $guarantor;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): static
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return Collection<int, Tenant>
     */
    public function getTenant(): Collection
    {
        return $this->tenant;
    }

    public function addTenant(Tenant $tenant): static
    {
        if (!$this->tenant->contains($tenant)) {
            $this->tenant->add($tenant);
            $tenant->setLease($this);
        }

        return $this;
    }

    public function removeTenant(Tenant $tenant): static
    {
        if ($this->tenant->removeElement($tenant)) {
            // set the owning side to null (unless already changed)
            if ($tenant->getLease() === $this) {
                $tenant->setLease(null);
            }
        }

        return $this;
    }

}
