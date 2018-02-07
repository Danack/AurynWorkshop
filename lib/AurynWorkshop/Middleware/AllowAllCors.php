<?php

namespace AurynWorkshop\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AllowAllCors
 */
class AllowAllCors
{
    public function __invoke(Request $request, ResponseInterface $response, $next)
    {
        /** @var $response ResponseInterface */
        $response = $next($request, $response);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
