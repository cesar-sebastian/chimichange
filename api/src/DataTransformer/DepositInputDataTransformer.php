<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DepositInput;
use App\Entity\Cash;
use ApiPlatform\Core\Validator\ValidatorInterface;

class DepositInputDataTransformer implements DataTransformerInterface
{

    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param DepositInput $input
     */
    public function transform($input, string $to, array $context = [])
    {
        $this->validator->validate($input);

        return $input;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if($data instanceof Cash)
        {
            //already transform
            return false;
        }

        return $to === Cash::class && ($context['input']['class'] ?? null) === DepositInput::class;
    }
}
