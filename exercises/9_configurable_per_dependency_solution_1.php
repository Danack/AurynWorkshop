<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;
use Psr\Log\LoggerInterface as Logger;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\EchoHandler;

class DebugLogger extends MonologLogger {}
class ErrorLogger extends MonologLogger {}

function createDebugLogger() {
    $logger = new DebugLogger('aw'); // anyone know why the name is required?
    $logger->pushHandler(new EchoHandler(MonologLogger::DEBUG));

    return $logger;
}

function createErrorLogger() {
    $logger = new ErrorLogger('aw'); // anyone know why the name is required?
    $logger->pushHandler(new EchoHandler(MonologLogger::ERROR));

    return $logger;
}

function foo(DebugLogger $logger) {
    $logger->debug('Hello, this is a debug message, and SHOULD be shown.');
}

function bar(ErrorLogger $logger) {
    $logger->debug('Hello, this is a debug message, and should NOT shown.');
    $logger->error('Hello, this is an error message, and SHOULD shown.');
}


$injector = new Injector();
$injector->delegate(DebugLogger::class, 'createDebugLogger');
$injector->delegate(ErrorLogger::class, 'createErrorLogger');

$injector->execute('foo');
$injector->execute('bar');

echo "There should be two lines above this.";


// DISCUSSION
//
// This solution is pretty terrible.

