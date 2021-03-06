<?php

use Auryn\Injector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;
use Danack\SlimAurynInvoker\RouteParams as InvokerRouteParams;



function getConfig(array $indexes)
{
    static $options = [];
    require __DIR__ . '/../config.php';

    $data = $options;

    foreach ($indexes as $index) {
        if (array_key_exists($index, $data) === false) {
            throw new \Exception("Config doesn't contain an element for $index, for indexes [" . implode('|', $indexes) . "]");
        }

        $data = $data[$index];
    }

    return $data;
}


function getConfigWithDefault(array $indexes, $default)
{
    static $options = [];
    require __DIR__ . '/../config.php';

    $data = $options;
    foreach ($indexes as $index) {
        if (array_key_exists($index, $data) === false) {
            return $default;
        }

        $data = $data[$index];
    }

    return $data;
}

/**
 * @param $errorNumber
 * @param $errorMessage
 * @param $errorFile
 * @param $errorLine
 * @return bool
 * @throws Exception
 */
function saneErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine)
{
    if (error_reporting() === 0) {
        // Error reporting has been silenced
        if ($errorNumber !== E_USER_DEPRECATED) {
            // Check it isn't this value, as this is used by twig, with error suppression. :-/
            return true;
        }
    }
    if ($errorNumber === E_DEPRECATED) {
        return false;
    }
    if ($errorNumber === E_CORE_ERROR || $errorNumber === E_ERROR) {
        // For these two types, PHP is shutting down anyway. Return false
        // to allow shutdown to continue
        return false;
    }
    $message = "Error: [$errorNumber] $errorMessage in file $errorFile on line $errorLine.";
    throw new \Exception($message);
}


function setupSlimAurynInvoker(
    Injector $injector,
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $routeArguments
) {
    $injector->alias(ServerRequestInterface::class, get_class($request));
    $injector->share($request);
    $injector->alias(ResponseInterface::class, get_class($response));
    $injector->share($response);
    foreach ($routeArguments as $key => $value) {
        $injector->defineParam($key, $value);
    }

    $invokerRouteParams = new InvokerRouteParams($routeArguments);
    $injector->share($invokerRouteParams);
}


function get_password_options()
{
    $options = [
        'cost' => 12,
    ];

    return $options;
}

/**
 * @param $password
 * @return bool|string
 */
function generate_password_hash($password)
{
    $options = get_password_options();
    return password_hash($password, PASSWORD_BCRYPT, $options);
}


function aurynWorkshopResponseMapper(\AurynWorkshop\Response $builtResponse, ResponseInterface $response)
{
    $response = $response->withStatus($builtResponse->getStatus());
    foreach ($builtResponse->getHeaders() as $key => $value) {
        /** @var $response \Psr\Http\Message\ResponseInterface */
        $response = $response->withAddedHeader($key, $value);
    }
    $response->getBody()->write($builtResponse->getBody());

    return $response;
}