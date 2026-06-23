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

    #[ORM\Column(name: 'irlPeriod', length: 7)]
    private string $irlPeriod;

    #[ORM\Column(name: 'irlValue')]
    private float $irlValue;

    #[ORM\Column (name: 'securityDeposit')]
    private float $securityDeposit;

    #[ORM\Column (name: 'dateOfPayment')]
    private int $dateOfPayment;

    #[ORM\OneToOne(inversedBy: 'lease', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Property $property;

    /**
     * @var Collection<int, Tenant>
     */
    #[ORM\OneToMany(targetEntity: Tenant::class, mappedBy: 'lease')]
    private Collection $tenant;

    #[ORM\Column (name: 'chargesAmount')]
    private ?float $chargesAmount = null;

    /**
     * @var Collection<int, Guarantor>
     */
    #[ORM\OneToMany(targetEntity: Guarantor::class, mappedBy: 'lease')]
    private Collection $guarantors;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'lease')]
    private Collection $payments;

    public function __construct()
    {
        $this->tenant = new ArrayCollection();
        $this->guarantors = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function getIrlPeriod(): string
    {
        return $this->irlPeriod;
    }

    public function setIrlPeriod(string $irlPeriod): static
    {
        $this->irlPeriod = $irlPeriod;

        return $this;
    }

    public function getIrlValue(): float
    {
        return $this->irlValue;
    }

    public function setIrlValue(float $irlValue): static
    {
        $this->irlValue = $irlValue;

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

    public function getChargesAmount(): ?float
    {
        return $this->chargesAmount;
    }

    public function setChargesAmount(float $chargesAmount): static
    {
        $this->chargesAmount = $chargesAmount;

        return $this;
    }

    /**
     * @return Collection<int, Guarantor>
     */
    public function getGuarantors(): Collection
    {
        return $this->guarantors;
    }

    public function addGuarantor(Guarantor $guarantor): static
    {
        if (!$this->guarantors->contains($guarantor)) {
            $this->guarantors->add($guarantor);
            $guarantor->setLease($this);
        }

        return $this;
    }

    public function removeGuarantor(Guarantor $guarantor): static
    {
        if ($this->guarantors->removeElement($guarantor)) {
            // set the owning side to null (unless already changed)
            if ($guarantor->getLease() === $this) {
                $guarantor->setLease(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setLease($this);
        }

        return $this;
    }

}
