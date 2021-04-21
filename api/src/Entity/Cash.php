<?php

namespace App\Entity;

use App\Repository\CashRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\CashDeposit;
use App\Controller\CashExchange;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\ExchangeInput;
use App\Dto\DepositInput;

/**
 * @ORM\Entity(repositoryClass=CashRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "post",
 *          "get",
 *          "deposit"={
 *              "method"="POST",
 *              "path"="/deposit",
 *              "controller"=CashDeposit::class,
 *              "security"="is_granted('ROLE_CLIENT')",
 *              "input"=DepositInput::CLASS
 *          },
 *          "exchange"={
 *              "method"="POST",
 *              "path"="/exchange",
 *              "controller"=CashExchange::class,
 *              "security"="is_granted('ROLE_CLIENT')",
 *              "input"=ExchangeInput::CLASS
 *          }
 *     },
 *     itemOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"cash:read"}},
 *     denormalizationContext={"groups"={"cash:write"}}
 * )
 */
class Cash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"cash:read", "account:read"})
     * @Assert\Length(min=3, max=3)
     */
    private $currency;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Groups({"cash:read", "account:read"})
     * @Assert\NotBlank
     * @Assert\Type(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="cashes")
     */
    private $account;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"cash:read", "account:read"})
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

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function hasEnoughCredit($amount): bool
    {
        return $amount <= $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function addAmount($amount): self
    {
        $this->amount = $this->amount + $amount;
        return $this;
    }

    public function removeAmount($amount): self
    {
        $this->amount = $this->amount - $amount;
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
