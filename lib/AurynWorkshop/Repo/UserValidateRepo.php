<?php

declare(strict_types=1);

namespace AurynWorkshop\Repo;

use AurynWorkshop\Params\UserLoginCredentialsParams;
use AurynWorkshop\Model\UserPasswordLogin;

interface UserValidateRepo
{
    public function validateUserFromLoginCredentials(UserLoginCredentialsParams $userLoginCredentialsParams) : ?UserPasswordLogin;
}
