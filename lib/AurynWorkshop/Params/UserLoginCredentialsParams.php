<?php

declare(strict_types=1);

namespace AurynWorkshop\Params;

use AurynWorkshop\App;
use AurynWorkshop\VariableMap;

class UserLoginCredentialsParams
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

    public static function fromVarMap(VariableMap $variableMap) : UserLoginCredentialsParams
    {
        $appParams = new AppParams($variableMap);

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
            // remember_me
        ];

        list($username, $password) = $appParams->validate($params);

        return new UserLoginCredentialsParams($username, $password);
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
