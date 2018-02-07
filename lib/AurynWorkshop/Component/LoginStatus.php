<?php

declare(strict_types=1);

namespace AurynWorkshop\Component;

use AurynWorkshop\AurynWorkshopSession;

class LoginStatus
{
    /** @var \AurynWorkshop\AurynWorkshopSession */
    private $aurynWorkshopSession;

    public function __construct(AurynWorkshopSession $aitekzSession)
    {
        $this->aurynWorkshopSession = $aitekzSession;
    }

    public function render()
    {
        if ($this->aurynWorkshopSession->isLoggedIn()) {
            $output = <<< HTML
<div>Yay! I am logged in.</div>
<div><a href="/logout">Logout</a></div>
HTML;
            return $output;
        }
        $output = <<< HTML
<div>Not logged in.</div>
<div><a href="/login">Login</a></div>
HTML;

        return $output;
    }
}
