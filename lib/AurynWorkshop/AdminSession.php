<?php

namespace AurynWorkshop;

use SlimSession\Helper as Session;
use AurynWorkshop\App;

class AdminSession
{
    const FLASH_ERROR = 'flash_error';
    const FLASH_SUCCESS = 'flash_success';

    private static $FLASH_MESSAGE_TYPES = [
        self::FLASH_ERROR => App::FLASH_MESSAGE_ERROR,
        self::FLASH_SUCCESS => App::FLASH_MESSAGE_SUCCESS,
    ];


    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function setFlashError(string $message)
    {
        $this->setFlashMessage($message, self::FLASH_ERROR);
    }
    public function hasFlashError()
    {
        return $this->hasFlashMessage(self::FLASH_ERROR);
    }

    public function getFlashError()
    {
        return $this->getFlashMessage(self::FLASH_ERROR);
    }

    public function deleteFlashError()
    {
        return $this->deleteFlashMessage(self::FLASH_ERROR);
    }

    public function setFlashSuccess(string $message)
    {
        $this->setFlashMessage($message, self::FLASH_SUCCESS);
    }

    public function getFlashSuccess()
    {
        return $this->getFlashMessage(self::FLASH_SUCCESS);
    }

    public function hasFlashSuccess()
    {
        return $this->hasFlashMessage(self::FLASH_SUCCESS);
    }

    public function deleteFlashSuccess()
    {
        return $this->deleteFlashMessage(self::FLASH_SUCCESS);
    }

    private function deleteFlashMessage($type)
    {
        $sessionFlashType = $this->getFlashSessionType($type);
        return $this->session->delete($sessionFlashType);
    }


    private function hasFlashMessage($type)
    {
        $sessionFlashType = $this->getFlashSessionType($type);
        return $this->session->exists($sessionFlashType);
    }

    private function getFlashMessage($type)
    {
        $sessionFlashType = $this->getFlashSessionType($type);
        return $this->session->get($sessionFlashType);
    }

    private function getFlashSessionType($type)
    {
        if (array_key_exists($type, self::$FLASH_MESSAGE_TYPES) == false) {
            throw new \Exception("Unknown flash type : $type");
        }
        return self::$FLASH_MESSAGE_TYPES[$type];
    }

    private function setFlashMessage(string $value, $type)
    {
        $sessionFlashType = $this->getFlashSessionType($type);

        $this->session->set($sessionFlashType, $value);
    }

    public function isLoggedIn()
    {
        return ($this->session->get(App::ADMIN_USERNAME, null) !== null);
    }

    public function isLoggedInWithPassword()
    {
        $loginType = $this->session->get(App::LOGIN_TYPE);
        if ($loginType === App::LOGIN_WITH_PASSWORD) {
            return true;
        }

        return false;
    }
}
