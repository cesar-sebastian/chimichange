<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\AccountInput;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @ApiResource(
 *     input=AccountInput::CLASS,
 *     collectionOperations={
 *          "post",
 *          "get"
 *     },
 *     itemOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"account:read"}},
 *     denormalizationContext={"groups"={"account:write"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "user": "exact",
 *     "user.email": "partial"
 * })
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @Groups({"account:read", "account:write"})
     * @Assert\Valid()
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Cash::class, mappedBy="account")
     * @Groups({"account:read"})
     */
    private $cashes;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="account")
     * @Groups({"account:read"})
     */
    private $transactions;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->cashes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    /**
     * @return Collection|Cash[]
     */
    public function getCashes(): Collection
    {
        return $this->cashes;
    }

    public function addCash(Cash $cash): self
    {
        if (!$this->cashes->contains($cash)) {
            $this->cashes[] = $cash;
            $cash->setAccountId($this);
        }

        return $this;
    }

    public function removeCash(Cash $cash): self
    {
        if ($this->cashes->removeElement($cash)) {
            // set the owning side to null (unless already changed)
            if ($cash->getAccountId() === $this) {
                $cash->setAccountId(null);
            }
        }

        return $this;
    }

    public function findCashbyCurrency($currency)
    {
        if(count($this->cashes) > 0)
        {
            foreach ($this->cashes as $cash)
            {
                if($cash->getCurrency() === $currency)
                {
                    return $cash;
                }
            }
        }
        return false;

//        return $this->cashes->filter(
//            function ($cash) use ($currency) {
//                return ($currency === $cash->getCurrency());
//            }
//        );
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setAccountId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAccountId() === $this) {
                $transaction->setAccountId(null);
            }
        }

        return $this;
    }
}
