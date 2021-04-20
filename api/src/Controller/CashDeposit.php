<?php

namespace App\Controller;


use App\Entity\Account;
use App\Entity\Cash;
use App\Entity\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CashDeposit
{
    private $validator;
    private $security;
    private $entityManager;

    const CURRENCY_DEFAULTS = 'ARS';

    /**
     * GuideDocumentUpload constructor.
     * @param ValidatorInterface $validator
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ValidatorInterface $validator, Security $security, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Cash
     */
    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $amount = $content['amount'];

        $user = $this->security->getUser();

        /**
         * @var Account $account
         */
        $account = $this->entityManager->getRepository(Account::class)->findOneBy(['user'=> $user]);
        $cash = $account->findCashbyCurrency(self::CURRENCY_DEFAULTS);

        if(!$cash)
        {
            $cash = new Cash();
            $cash->setAccount($account);
            $cash->setCurrency(self::CURRENCY_DEFAULTS);
            $cash->setAmount($amount);

            $this->entityManager->persist($cash);
        } else {
            $cash->addAmount($amount);
        }

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setAmountFrom(0);
        $transaction->setAmountTo($amount);
        $transaction->setCurrencyFrom(self::CURRENCY_DEFAULTS);
        $transaction->setCurrencyTo(self::CURRENCY_DEFAULTS);
        $transaction->setRate(1);
        $transaction->setOperation(Transaction::OPERATION_DEPOSIT);
        $this->entityManager->persist($transaction);

        $this->entityManager->flush();

        return $cash;
    }
}
