<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class AccountInput
{
    /**
     * @var string
     * @Groups({"account:write"})
     */
    public $email;

    /**
     * @var string
     * @Groups({"account:write"})
     */
    public $password;
}
