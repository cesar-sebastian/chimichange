<?php


namespace App\Service;


use App\Entity\Rate;
use Doctrine\ORM\EntityManagerInterface;

class RateService
{
    private EntityManagerInterface $entityManager;
    private $rate;

    /**
     * RateService constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function convert($from, $to, $amount)
    {
        $rateFrom = $this->entityManager->getRepository(Rate::class)->findOneBy(['currency' => $from])->getValue();
        $rateTo = $this->entityManager->getRepository(Rate::class)->findOneBy(['currency' => $to])->getValue();

        /**
         * @var Rate $rateTo
         * @var Rate $rateFrom
         */
        $this->rate = $rateTo / $rateFrom;

        return ($amount / $rateFrom) * $rateTo;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

}
