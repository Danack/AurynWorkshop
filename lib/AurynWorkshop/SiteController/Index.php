<?php

declare(strict_types=1);

namespace AurynWorkshop\SiteController;


use AurynWorkshop\SessionRender;
use AurynWorkshop\Response\HtmlResponse;

class Index
{
    public function get(SessionRender $sessionRender)
    {
        $html = $sessionRender->render('pages/index.html');



        return new HtmlResponse($html);
    }
}
