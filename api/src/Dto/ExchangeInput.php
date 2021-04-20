<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


class ExchangeInput
{
    /**
     * @var string
     * @Groups({"cash:write"})
     * @Assert\Length(min=3, max=3)
     */
    public $currencyFrom;

    /**
     * @var string
     * @Groups({"cash:write"})
     * @Assert\Length(min=3, max=3)
     */
    public $currencyTo;

    /**
     * @var float
     * @Groups({"cash:write"})
     * @Assert\NotBlank
     * @Assert\Type(type="float")
     */
    public $amount;
}
