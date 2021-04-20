<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Account;
use App\Dto\AccountInput;
use App\Entity\User;

class AccountInputDataTransformer implements DataTransformerInterface
{

    /**
     * @param AccountInput $input
     */
    public function transform($input, string $to, array $context = [])
    {
        $user = new User();
        $user->setEmail($input->email);
        $user->setRoles(['ROLE_CLIENT']);
        $user->setPlainPassword($input->password);

        $account = new Account();
        $account->setUser($user);

        return $account;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if($data instanceof Account)
        {
            //already transform
            return false;
        }

        return $to === Account::class && ($context['input']['class'] ?? null) === AccountInput::class;
    }
}
