<?php

declare(strict_types=1);

namespace AurynWorkshop\Repo\AdminUserAddRepo;

use AurynWorkshop\Exception\DuplicateUsernameException;
use AurynWorkshop\Model\User;
use AurynWorkshop\Model\UserPasswordLogin;
use AurynWorkshop\Params\CreateUserWithCredentialsParams;
use AurynWorkshop\Repo\UserAddRepo;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

class DoctrineUserAddRepo implements UserAddRepo
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addUserFromCredentials(CreateUserWithCredentialsParams $userWithCredentialsParams)
    {
        try {
            $user = User::fromPartial();
            $this->em->persist($user);
            $this->em->flush();

            $userPasswordLogin = UserPasswordLogin::fromPartial(
                $userWithCredentialsParams->getUsername(),
                generate_password_hash($userWithCredentialsParams->getPassword()),
                $user
            );

            $user->setUserPasswordLogin($userPasswordLogin);
            $this->em->persist($userPasswordLogin);
            $this->em->flush();
        }
        catch (UniqueConstraintViolationException $ucve) {
            throw new DuplicateUsernameException("username not available", $ucve);
        }
    }
}


