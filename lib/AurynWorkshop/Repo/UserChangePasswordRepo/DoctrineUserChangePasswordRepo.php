<?php

declare(strict_types=1);

namespace AurynWorkshop\Repo\UserValidateRepo;

use AurynWorkshop\Params\ChangeUserPasswordParams;
use Doctrine\ORM\EntityManager;
use AurynWorkshop\Params\UserLoginCredentialsParams;
use AurynWorkshop\Model\UserPasswordLogin;
use AurynWorkshop\Repo\UserChangePasswordRepo;


class DoctrineUserChangePasswordRepo implements UserChangePasswordRepo
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function changePassword(ChangeUserPasswordParams $changeUserPasswordParams)
    {
        $repo = $this->em->getRepository(\AurynWorkshop\Model\UserPasswordLogin::class);

        $userPasswordLogin = $repo->findOneBy(['username' => mb_strtolower($changeUserPasswordParams->getUsername())]);
        if ($userPasswordLogin === null) {
            return false;
        }

        $options = get_password_options();

        $newHash = password_hash($changeUserPasswordParams->getPassword(), PASSWORD_DEFAULT, $options);
        $userPasswordLogin->setPasswordHash($newHash);

        return true;
    }
}



