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
        $this->session = $session;
    }

    public function setUserLoggedInViaCredentials(UserPasswordLogin $userPasswordLogin)
    {
        // $this->session->set(App::ADMIN_INFO, $adminUser->toArray());
        $this->session->set(App::ADMIN_USERNAME, $userPasswordLogin->getUsername());
        // $this->session->set(App::ADMIN_TYPE, $adminUser->getType());
        $this->session->set(App::LOGIN_TYPE, App::LOGIN_WITH_PASSWORD);
    }


//    public function isSiteSelected() : bool
//    {
//        return ($this->session->get(App::ADMIN_SITE_SELECTED) !== null);
//    }
//
//    public function setSelectedSite(Site $site)
//    {
//        $json = $site->serialize();
//        $this->session->set(App::ADMIN_SITE_SELECTED, $json);
//    }
//
//    public function getUsername()
//    {
//        return $this->session->get(App::ADMIN_USERNAME);
//    }
//
//    public function getAdminInfo()
//    {
//        return $this->session->get(App::ADMIN_INFO);
//    }
//
//    /**
//     * @return Site|null
//     */
//    public function getSelectedSite()
//    {
//        $siteSelectedJson = $this->session->get(App::ADMIN_SITE_SELECTED);
//
//        if ($siteSelectedJson === null) {
//            return null;
//        }
//
//        return Site::unserialize($siteSelectedJson);
//    }
//
//    public function hasSelectedSite() : bool
//    {
//        if ($this->session->get(App::ADMIN_SITE_SELECTED) === null) {
//            return false;
//        }
//
//        return true;
//    }
//
//
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

        $this->session->set($types[$type], $value);
    }

//    public function setContentImportInProgress($value)
//    {
//        $this->session->set(App::CONTENT_IMPORT_IN_PROGRESS, $value);
//    }
//
//    public function getContentImportInProgress()
//    {
//        return $this->session->get(App::CONTENT_IMPORT_IN_PROGRESS, null);
//    }
//
//    public function clearContentImportInProgress()
//    {
//        $this->session->delete(App::CONTENT_IMPORT_IN_PROGRESS);
//    }

    public function isLoggedIn()
    {
        return ($this->session->get(App::ADMIN_USERNAME, null) !== null);
    }

//    public function isSuperUser()
//    {
////        $type = $this->session->get(App::ADMIN_TYPE);
////        if ($type === \AurynWorkshop\Model\AdminUser::TYPE_SITE_SUPER_USER) {
////            return true;
////        }
//        return false;
//    }
//
//    public function isSiteCreator()
//    {
//        $type = $this->session->get(App::ADMIN_TYPE);
//        if ($type === \AurynWorkshop\Model\AdminUser::TYPE_SITE_CREATOR) {
//            return true;
//        }
//
//        return false;
//    }

    public function isLoggedInWithPassword()
    {
//        $loginType = $this->session->get(App::LOGIN_TYPE);
//        if ($loginType === App::LOGIN_WITH_PASSWORD) {
//            return true;
//        }

        return true;
    }

//    public function setUseSpoofAdminData(bool $active)
//    {
//        $this->session->set(self::IS_USE_SPOOF_DATA, $active);
//    }
//
//    public function shouldUseSpoofAdminData() : bool
//    {
//        if ($this->session->get(self::IS_USE_SPOOF_DATA) === true) {
//            return true;
//        }
//
//        return false;
//    }
//
//    public function setSpoofAdminData(SpoofAdminData $spoofAdminData)
//    {
//        $this->session->set(self::SPOOF_DATA, $spoofAdminData->toArray());
//    }
//
//    public function getSpoofAdminData() : SpoofAdminData
//    {
//        $existingData = $this->session->get(self::SPOOF_DATA);
//
//        if ($existingData === null) {
//            return SpoofAdminData::fromDefault();
//        }
//
//        try {
//            return SpoofAdminData::fromArray($existingData);
//        }
//        catch (\Exception $e) {
//            $this->setFlashError("Failed to decode existing spoof data, resetting: " . $e->getMessage());
//            return SpoofAdminData::fromDefault();
//        }
//    }

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
