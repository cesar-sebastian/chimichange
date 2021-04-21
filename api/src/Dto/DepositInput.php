<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


class DepositInput
{
    /**
     * @var float
     * @Groups({"cash:write", "account:read"})
     * @Assert\Type(type="float")
     * @Assert\NotBlank()
     */
    public $amount;
}
