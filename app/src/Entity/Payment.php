<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\UniqueConstraint(name: 'lease_period_unique', columns: ['lease_id', 'period'])]
class Payment
{
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private Lease $lease;

    #[ORM\Column]
    private \DateTimeImmutable $period;

    #[ORM\Column]
    private float $amount;

    #[ORM\Column(length: 255)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $receiptPath = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $receiptGeneratedAt = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLease(): Lease
    {
        return $this->lease;
    }

    public function setLease(Lease $lease): static
    {
        $this->lease = $lease;

        return $this;
    }

    public function getPeriod(): \DateTimeImmutable
    {
        return $this->period;
    }

    public function setPeriod(\DateTimeImmutable $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

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

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getReceiptPath(): ?string
    {
        return $this->receiptPath;
    }

    public function setReceiptPath(?string $receiptPath): static
    {
        $this->receiptPath = $receiptPath;

        return $this;
    }

    public function getReceiptGeneratedAt(): ?\DateTimeImmutable
    {
        return $this->receiptGeneratedAt;
    }

    public function setReceiptGeneratedAt(?\DateTimeImmutable $receiptGeneratedAt): static
    {
        $this->receiptGeneratedAt = $receiptGeneratedAt;

        return $this;
    }
}
