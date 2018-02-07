<?php

namespace AurynWorkshop\Middleware;

use Slim\App;
use Auryn\Injector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use AurynWorkshop\AurynWorkshopSession;

class UserTypeRouting
{
    /** @var App  */
    private $app;

    /** @var AurynWorkshopSession */
    private $session;

    /** @var Injector */
    private $injector;

    public function __construct(AurynWorkshopSession $session, App $app, Injector $injector)
    {
        $this->session = $session;
        $this->app = $app;
        $this->injector = $injector;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $routes = auryn_workshop_routes();

        foreach ($routes as $route) {
            list($path, $method, $callable) = $route;
            $route = $this->app->{$method}($path, $callable);
        }

        $response = $next($request, $response);
        return $response;
    }
}
