<?php

declare(strict_types=1);

namespace AurynWorkshop\Params;

use AurynWorkshop\App;
use AurynWorkshop\VariableMap;

class ChangeUserPasswordParams
{
    /** @var  string */
    private $username;

    /** @var  string */
    private $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public static function fromVarMap(VariableMap $varMap) : ChangeUserPasswordParams
    {
        $appParams = new AppParams($varMap);

        $params = [
            'username' => [
                $appParams->checkSet('username'),
                $appParams->trim(),
                $appParams->checkMaxLength(128),
                $appParams->checkMinLength(4),
                $appParams->checkValidCharacters(App::PATTERN_VALID_USERNAME_CHARACTERS)
            ],
            'password' => [
                $appParams->checkSet('password'),
                $appParams->trim(),
                $appParams->checkMaxLength(128),
                $appParams->checkMinLength(8)
            ],
        ];

        list($username, $password) = $appParams->validate($params);

        return new ChangeUserPasswordParams($username, $password);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
