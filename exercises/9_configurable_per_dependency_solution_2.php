<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;
use Psr\Log\LoggerInterface as Logger;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\EchoHandler;

class LoggerFactory {

    const FOO = 'foo';
    const BAR = 'bar';

    /** @var Injector */
    private $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function getLogger(string $type) : Logger {

        $loggerTypes = [
            self::FOO => 'createDebugLogger',
            self::BAR => 'createErrorLogger',
        ];

        if (array_key_exists($type, $loggerTypes) === false) {
            // In a real app, probably would want exception in dev,
            // but default logger in production.
            throw new \Exception("Unknown logger type [$type]");
        }

        // @TODO - put some caching here.
        return $this->injector->execute($loggerTypes[$type]);
    }
}

function createDebugLogger() {
    $logger = new MonologLogger('aw'); // anyone know why the name is required?
    $logger->pushHandler(new EchoHandler(MonologLogger::DEBUG));

    return $logger;
}

function createErrorLogger() {
    $logger = new MonologLogger('aw'); // anyone know why the name is required?
    $logger->pushHandler(new EchoHandler(MonologLogger::ERROR));

    return $logger;
}

function foo(LoggerFactory $loggerFactory) {
    $logger = $loggerFactory->getLogger(LoggerFactory::FOO);
    $logger->debug('Hello, this is a debug message, and SHOULD be shown.');
}

function bar(LoggerFactory $loggerFactory) {
    $logger = $loggerFactory->getLogger(LoggerFactory::BAR);
    $logger->debug('Hello, this is a debug message, and should NOT shown.');
    $logger->error('Hello, this is an error message, and SHOULD shown.');
}


$injector = new Injector();

$injector->execute('foo');
$injector->execute('bar');

echo "There should be two lines above this.";


// DISCUSSION
//
// This solution has much better trades offs. It will scale much more
// easily large applications. Not only is there not an explosion
// of different types, but you can add more logic to the LoggerFactory code
// to do clever things.
//
// Obvious things - be able to configure exactly what logger get used dynamically.
// e.g. if a devops person wants to investigate some behaviour in some code, they
// can turn on more verbose
//
// There are trade-offs, including now having to manage the names of things
// and also it would be possible for the code to fail in the factory.