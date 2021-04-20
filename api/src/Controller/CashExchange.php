<?php

namespace App\Controller;


use App\Dto\ExchangeInput;
use App\Entity\Account;
use App\Entity\Cash;
use App\Entity\Transaction;
use App\Exception\InvalidAmountExchangeException;
use App\Exception\NoExistingCashException;
use App\Service\RateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CashExchange
{
    private $validator;
    private $security;
    private $entityManager;
    private $rateService;

    /**
     * GuideDocumentUpload constructor.
     * @param ValidatorInterface $validator
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param RateService $rateService
     */
    public function __construct(ValidatorInterface $validator, Security $security, EntityManagerInterface $entityManager, RateService $rateService)
    {
        $this->validator = $validator;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->rateService = $rateService;
    }

    /**
     * @return Cash
     * @throws NoExistingCashException
     * @throws InvalidAmountExchangeException
     */
    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $currencyFrom = $content['currencyFrom'];
        $currencyTo = $content['currencyTo'];
        $amountFrom = $content['amount'];

        $user = $this->security->getUser();

        /**
         * @var Account $account
         */
        $account = $this->entityManager->getRepository(Account::class)->findOneBy(['user'=> $user]);

        /**
         * @var Cash $cashFrom
         */
        $cashFrom = $account->findCashbyCurrency($currencyFrom);

        if(!$cashFrom)
            throw new NoExistingCashException(sprintf('The cash with currency "%s" does not exist.', $currencyFrom));

        if(!$cashFrom->hasEnoughCredit($amountFrom))
            throw new InvalidAmountExchangeException(sprintf('The amount in cash "%s" is not enough for exchange.', $currencyFrom));

        //update balance
        $cashFrom->removeAmount($amountFrom);

        // Get cash to
        /**
         * @var Cash $cashTo
         */
        $cashTo = $account->findCashbyCurrency($currencyTo);

        // Get amount converted
        $amountTo = $this->rateService->convert($currencyFrom, $currencyTo, $amountFrom);

        //Get rate conversion
        $rate = $this->rateService->getRate();

        if(!$cashTo)
        {
            $cashTo = new Cash();
            $cashTo->setAccount($account);
            $cashTo->setCurrency($currencyTo);
            $cashTo->setAmount($amountTo);

            $this->entityManager->persist($cashTo);
        } else {
            $cashTo->addAmount($amountTo);
        }

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setAmountFrom($amountFrom);
        $transaction->setAmountTo($amountTo);
        $transaction->setCurrencyFrom($currencyFrom);
        $transaction->setCurrencyTo($currencyTo);
        $transaction->setRate($rate);
        $transaction->setOperation(Transaction::OPERATION_EXCHANGE);
        $this->entityManager->persist($transaction);

        $this->entityManager->flush();

        return $cashFrom;
    }
}
