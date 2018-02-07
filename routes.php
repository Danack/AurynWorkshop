<?php




function auryn_workshop_routes()
{
    $routes = [
        ['/login', 'GET', 'AurynWorkshop\SiteController\Login::getLogin'],
        ['/login', 'POST', 'AurynWorkshop\SiteController\Login::postLogin'],
        ['/logout', 'GET', 'AurynWorkshop\SiteController\Login::logout'],

        ['/test', 'GET', 'AurynWorkshop\SiteController\Debug::getIndex'],
        ['/', 'GET', 'AurynWorkshop\SiteController\Index::get'],

        ['[{path:.*}]', 'GET', 'AurynWorkshop\SiteController\Index::get'],
    ];

    return $routes;
}
