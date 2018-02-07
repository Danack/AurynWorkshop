<?php

declare(strict_types=1);

namespace AurynWorkshop\Repo\UserValidateRepo;

use AurynWorkshop\Repo\UserValidateRepo;
use Doctrine\ORM\EntityManager;
use AurynWorkshop\Params\UserLoginCredentialsParams;
use AurynWorkshop\Model\UserPasswordLogin;

class DoctrineUserValidateRepo implements UserValidateRepo
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validateUserFromLoginCredentials(UserLoginCredentialsParams $userLoginCredentialsParams) : ?UserPasswordLogin
    {
        $repo = $this->em->getRepository(\AurynWorkshop\Model\UserPasswordLogin::class);
        $userPasswordLogin = $repo->findOneBy(['username' => mb_strtolower($userLoginCredentialsParams->getUsername())]);

        if ($userPasswordLogin === null) {
            return null;
        }
        /** @var $userPasswordLogin \AurynWorkshop\Model\UserPasswordLogin */

        // Verify stored hash against plain-text password
        $isVerified = password_verify($userLoginCredentialsParams->getPassword(), $userPasswordLogin->getPasswordHash());
        if ($isVerified !== true) {
            return null;
        }

        $options = get_password_options();

        // Check if a newer hashing algorithm is available
        // or the cost has changed
        if (password_needs_rehash($userPasswordLogin->getPasswordHash(), PASSWORD_DEFAULT, $options)) {
            // If so, create a new hash, and replace the old one
            $newHash = password_hash($userLoginCredentialsParams->getPassword(), PASSWORD_DEFAULT, $options);
            $userPasswordLogin->setPasswordHash($newHash);
            $this->em->persist($userPasswordLogin);
            $this->em->flush();
        }

        return $userPasswordLogin;
    }
}
