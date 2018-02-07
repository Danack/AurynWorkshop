<?php

namespace AurynWorkshop;

class App
{
    const DATE_FORMAT = 'Y_m_d_H_i_s';

    const ADMIN_USERNAME = 'admin_username';

    const ADMIN_TYPE = 'admin_type';
    const ADMIN_INFO = 'admin_info';

    const FLASH_MESSAGE_ERROR = 'flash_message_error';
    const FLASH_MESSAGE_SUCCESS = 'flash_message_success';

    const LOGIN_TYPE = 'login_type';

    const LOGIN_WITH_PASSWORD = 'login_with_password';
    const LOGIN_WITH_REMEMBER_ME = 'remembered_by_cookie';

    // A pattern that matches invalid name characters.
    // Allowed chars are 0-9a-zA-Z_-
    const PATTERN_VALID_IDENTIFIER_CHARACTERS = '0-9a-zA-Z_';

    // @TODO - allow utf8 word chars
    const PATTERN_VALID_USERNAME_CHARACTERS = '0-9a-zA-Z_\.@';

    // A pattern that matches invalid name characters.
    // Allowed chars are 0-9a-zA-Z_-
    const PATTERN_VALID_NAME_CHARACTERS = '0-9a-zA-Z_\- ';

    const PATTERN_VALID_DESCRIPTION_CHARACTERS = '0-9a-zA-Z_\- \.';


    public static function date()
    {
        return \date(self::DATE_FORMAT);
    }
}
