<?php

namespace AurynWorkshop\CliController;

use AurynWorkshop\Exception\ValidationException;
use AurynWorkshop\Model\User;
use AurynWorkshop\Repo\UserChangePasswordRepo;
use AurynWorkshop\VariableMap;
use AurynWorkshop\Repo\UserAddRepo;
use AurynWorkshop\Params\CreateUserWithCredentialsParams;

use AurynWorkshop\Params\ChangeUserPasswordParams;

class UserAccount
{
    public function createUserWithLogin(VariableMap $variableMap, UserAddRepo $userAddRepo)
    {
        try {
            $createUserWithCredentialsParams = CreateUserWithCredentialsParams::fromVarMap($variableMap);
        }
        catch (ValidationException $ve) {
            echo "There are validation problems:\n * ";
            echo implode("\n * ", $ve->getValidationProblems());
            echo "\n";
            exit(-1);
        }

        $userAddRepo->addUserFromCredentials($createUserWithCredentialsParams);
    }

    public function changeUserPassword(VariableMap $variableMap, UserChangePasswordRepo $userChangePasswordRepo)
    {
        try {
            $changeUserPasswordParams = ChangeUserPasswordParams::fromVarMap($variableMap);
        }
        catch (ValidationException $ve) {
            echo "There are validation problems:\n * ";
            echo implode("\n * ", $ve->getValidationProblems());
            echo "\n";
            exit(-1);
        }

        $userChangePasswordRepo->changePassword($changeUserPasswordParams);
    }
}
