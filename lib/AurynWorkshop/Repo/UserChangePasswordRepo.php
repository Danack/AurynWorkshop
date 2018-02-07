<?php

declare(strict_types=1);

namespace AurynWorkshop\Repo;

use AurynWorkshop\Params\ChangeUserPasswordParams;

interface UserChangePasswordRepo
{
    public function changePassword(ChangeUserPasswordParams $changeUserPasswordParams);
}
