<?php


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


final class AccountDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Account;
    }

    public function persist($data, array $context = [])
    {
        if ($data->getUser()->getPlainPassword()) {
            $data->getUser()->setPassword(
                $this->userPasswordEncoder->encodePassword($data->getUser(), $data->getUser()->getPlainPassword())
            );
            $data->getUser()->eraseCredentials();
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}
