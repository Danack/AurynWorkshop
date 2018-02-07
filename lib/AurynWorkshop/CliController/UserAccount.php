<?php

namespace AurynWorkshop\CliController;

use AurynWorkshop\Exception\ValidationException;
use AurynWorkshop\Model\User;
use AurynWorkshop\Model\UserPasswordLogin;
use Doctrine\ORM\EntityManager;
use AurynWorkshop\Params\AppParams;
//use Dijon\Repo\AdminUserAddRepo;
use AurynWorkshop\VariableMap;
use AurynWorkshop\Repo\UserAddRepo;
use AurynWorkshop\Params\CreateUserWithCredentialsParams;

class UserAccount
{
    public function createUserWithLogin(AppParams $appParams, UserAddRepo $userAddRepo)
    {
        try {
            $createUserWithCredentialsParams = CreateUserWithCredentialsParams::fromAppParams($appParams);
        }
        catch (ValidationException $ve) {
            echo "There are validation problems:\n * ";
            echo implode("\n * ", $ve->getValidationProblems());
            echo "\n";
            exit(-1);
        }

        $userAddRepo->addUserFromCredentials($createUserWithCredentialsParams);
    }

//    public function changeAdminType(EntityManager $em, $username, $type)
//    {
//        $type = trim($type);
//
//        if (AdminUser::isKnownType($type) !== true) {
//            echo "Type [] is not a valid type. Need one of " . implode(", ", AdminUser::getKnownTypes());
//            exit(-1);
//        }
//
//        $repo = $em->getRepository('Dijon\Model\AdminUser');
//        $adminUser = $repo->findOneBy(['username' => mb_strtolower($username)]);
//
//        if ($adminUser === null) {
//            echo "Could not find user [$username]\n";
//            exit(-1);
//        }
//
//        /** @var $adminUser \Dijon\Model\AdminUser */
//        $adminUser->setType($type);
//        $em->persist($adminUser);
//        $em->flush();
//    }
//
//
//
//    public function changeAdminPassword(EntityManager $em, $username, $password)
//    {
//        $repo = $em->getRepository('Dijon\Model\AdminUser');
//        $adminUser = $repo->findOneBy(['username' => mb_strtolower($username)]);
//
//        if ($adminUser === null) {
//            echo "Could not find user [$username]\n";
//            exit(-1);
//        }
//
//        $adminUser->setPasswordHash(generate_password_hash($password));
//        $em->persist($adminUser);
//        $em->flush();
//    }
}
