<?php

declare(strict_types=1);

namespace AurynWorkshop\Repo;

use AurynWorkshop\Params\CreateUserWithCredentialsParams;

interface UserAddRepo
{
    public function addUserFromCredentials(CreateUserWithCredentialsParams $createAdminUserParams);
}
