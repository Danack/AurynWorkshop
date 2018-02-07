<?php

declare(strict_types=1);

namespace AurynWorkshop\SiteController;

use AurynWorkshop\AdminSession;
use AurynWorkshop\SessionRender;
use AurynWorkshop\Response\HtmlResponse;

class Index
{
    public function foo(SessionRender $sessionRender)
    {
        $html = $sessionRender->render('pages/page1.html');
        return new HtmlResponse($html);
    }
}
