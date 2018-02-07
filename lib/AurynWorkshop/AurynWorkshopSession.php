<?php

namespace AurynWorkshop;

use SlimSession\Helper as Session;

use AurynWorkshop\Model\User;
use AurynWorkshop\Model\UserPasswordLogin;

class AurynWorkshopSession
{
    const FLASH_ERROR = 'flash_error';
    const FLASH_SUCCESS = 'flash_success';
    const IS_USE_SPOOF_DATA = 'use_spoof_data';
    const SPOOF_DATA = 'spoof_data';

    private $session;

    public function __construct(Session $session)
    {
        static $count = 0;

        $count += 1;
        $this->session = $session;

        $this->setFlashError("omg wahat " . $count);
    }

    public function setUserLoggedInViaCredentials(UserPasswordLogin $userPasswordLogin)
    {
        $this->session->set(App::ADMIN_USERNAME, $userPasswordLogin->getUsername());
        $this->session->set(App::LOGIN_TYPE, App::LOGIN_WITH_PASSWORD);
    }

    public function setFlashError(string $message)
    {
        $this->setFlashMessage($message, self::FLASH_ERROR);
    }

    public function setFlashSuccess(string $message)
    {
        $this->setFlashMessage($message, self::FLASH_SUCCESS);
    }

    protected function setFlashMessage(string $value, $type)
    {
        $types = [
            self::FLASH_ERROR => App::FLASH_MESSAGE_ERROR,
            self::FLASH_SUCCESS => App::FLASH_MESSAGE_SUCCESS,
        ];

        if (array_key_exists($type, $types) == false) {
            throw new \Exception("Unknown flash type : $type");
        }
    }

    public function isLoggedIn()
    {
        return ($this->session->get(App::ADMIN_USERNAME, null) !== null);
    }

    public function isLoggedInWithPassword()
    {
        return true;
    }


    public function useFlashError(callable $fn)
    {
        if ($this->session->offsetExists(App::FLASH_MESSAGE_ERROR) === true) {
            $flashMessageError = $this->session->get(App::FLASH_MESSAGE_ERROR, null);
            $this->session->delete(App::FLASH_MESSAGE_ERROR);
            $fn($flashMessageError);
        }
    }

    public function useFlashSuccess(callable $fn)
    {
        if ($this->session->offsetExists(App::FLASH_MESSAGE_SUCCESS) === true) {
            $flashMessageSuccess = $this->session->get(App::FLASH_MESSAGE_SUCCESS, null);
            $this->session->delete(App::FLASH_MESSAGE_SUCCESS);
            $fn($flashMessageSuccess);
        }
    }

    public function logout()
    {
        $this->session->set(App::ADMIN_USERNAME, null);
    }
}
