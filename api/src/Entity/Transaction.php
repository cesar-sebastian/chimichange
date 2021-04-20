<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get"
 *     },
 *     itemOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"transaction:read"}},
 *     denormalizationContext={"groups"={"transaction:write"}}
 * )
 */
class Transaction
{

    const OPERATION_DEPOSIT     = 'DEPOSIT';
    const OPERATION_EXCHANGE    = 'EXCHANGE';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"transaction:read", "account:read"})
     */
    private $operation;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"transaction:read", "account:read"})
     */
    private $currencyFrom;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"transaction:read", "account:read"})
     */
    private $currencyTo;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Groups({"transaction:read", "account:read"})
     */
    private $rate;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Groups({"transaction:read", "account:read"})
     */
    private $amountFrom;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Groups({"transaction:read", "account:read"})
     */
    private $amountTo;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="transactions")
     */
    private $account;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"transaction:read", "account:read"})
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(string $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currencyFrom;
    }

    public function setCurrencyFrom(string $currencyFrom): self
    {
        $this->currencyFrom = $currencyFrom;

        return $this;
    }

    public function getCurrencyTo(): ?string
    {
        return $this->currencyTo;
    }

    public function setCurrencyTo(string $currencyTo): self
    {
        $this->currencyTo = $currencyTo;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getAmountFrom(): ?string
    {
        return $this->amountFrom;
    }

    public function setAmountFrom(string $amountFrom): self
    {
        $this->amountFrom = $amountFrom;

        return $this;
    }

    public function getAmountTo(): ?string
    {
        return $this->amountTo;
    }

    public function setAmountTo(string $amountTo): self
    {
        $this->amountTo = $amountTo;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
