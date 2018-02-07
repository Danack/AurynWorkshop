<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\EchoHandler;

$injector = new Injector();

function createLogger() {
    $logger = new Logger('aw'); // anyone know why the name is required?
    $logger->pushHandler(new EchoHandler(Logger::DEBUG));

    return $logger;
}


function foo(Logger $logger) {
    $logger->debug('Hello, this is a debug message, and SHOULD be shown.');
}

function bar(Logger $logger) {
    $logger->debug('Hello, this is a debug message, and should NOT shown.');
    $logger->error('Hello, this is an error message, and SHOULD shown.');
}


$injector->delegate(Logger::class, 'createLogger');
$injector->execute('foo');
$injector->execute('bar');

// Task

// Change the code so that the functions foo and bar get different
// loggers. The one passed to foo should be configured to show DEBUG
// messages. The one passed to bar should be configured to NOT show
// debug messages.


// There is a revealing hint below
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// There are at least two solutions:
// i) extend the logger type to two separate child types
// ii) use the functions be dependent on Logger factories not the logger directly



