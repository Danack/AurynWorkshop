<?php

function get_common_routes()
{
    $routes = [
        ['/', 'GET', 'AurynWorkshop\SiteController\Index::foo'],
        ['/login', 'POST', 'AurynWorkshop\SiteController::loginPost'],

    ];

    return $routes;
}

function get_site_creator_routes()
{
    $routes = [
        ['/site_select', 'GET', 'Dijon\AdminController\Admin::getSiteSelectPage'],
        ['/site_select', 'POST', 'Dijon\AdminController\Admin::postSiteSelectPage'],
    ];


    return $routes;
}

function get_super_user_routes()
{
    $superUserSpecificRoutes = [
    ];

    return $superUserSpecificRoutes;
}


function redirectRoutesToLogin(array $routes)
{
    $newRoutes = [];
    foreach ($routes as $route) {
        $newRoutes[] = [
            $route[0],
            $route[1],
            'Dijon\AdminController\Admin::redirectToLogin'
        ];
    }

    return $newRoutes;
}


function dijon_common_routes()
{
    $routes = get_common_routes();
    $routes = array_merge($routes, redirectRoutesToLogin(get_site_creator_routes()));
    $routes = array_merge($routes, redirectRoutesToLogin(get_super_user_routes()));

    return $routes;
}


/**
 * List the routes that site_creator's and above should be able to see.
 * @return array
 */
function dijon_site_creator_routes()
{
    $routes = get_common_routes();
    $routes = array_merge($routes, get_site_creator_routes());
    $routes = array_merge($routes, redirectRoutesToLogin(get_super_user_routes()));

    return $routes;
}

/**
 * List the routes that only super users should be able to see
 * @return array
 */
function dijon_super_user_routes()
{
    $routes = get_common_routes();
    $routes = array_merge($routes, get_site_creator_routes());
    $routes = array_merge($routes, get_super_user_routes());

    return $routes;
}
