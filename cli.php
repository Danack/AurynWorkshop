#!/usr/bin/env php
<?php

use Danack\Console\Application;
use Danack\Console\Output\BufferedOutput;
use AurynWorkshop\CLIFunction;
use AurynWorkshop\VariableMap\ArrayVariableMap;

error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . "/cli/cli_functions.php";

$injector = new Auryn\Injector();

CLIFunction::setupErrorHandlers();

$cliInjectionParams = require __DIR__ . "/injectionParamsCli.php";
$cliInjectionParams->addToInjector($injector);

$injector->share($injector);

$console = new Application();
add_console_commands($console);

try {
    $parsedCommand = $console->parseCommandLine();
}
catch (\Exception $e) {
    $output = new BufferedOutput();
    $console->renderException($e, $output);
    echo $output->fetch();
    exit(-1);
}

try {
    foreach ($parsedCommand->getParams() as $key => $value) {
        $injector->defineParam($key, $value);
    }

    $variableMap = new ArrayVariableMap($parsedCommand->getParams());
    $injector->alias(\AurynWorkshop\VariableMap::class, \AurynWorkshop\VariableMap\ArrayVariableMap::class);
    $injector->share($variableMap);

    $injector->execute($parsedCommand->getCallable());
    echo "\n";
}
catch (Auryn\InjectionException $ie) {
    echo "time: " . date(\AurynWorkshop\App::DATE_FORMAT) . " ";
    $output = new BufferedOutput();
    $console->renderException($ie, $output);
    echo $output->fetch();

    echo "Stacktrace:\n";
    echo $ie->getTraceAsString() . "\n";

    echo "Dependency chain:\n  ";
    echo implode("\n  ", $ie->getDependencyChain());
    echo "\n";

    exit(-1);
}
catch (\Exception $e) {
    echo "time: " . date(\AurynWorkshop\App::DATE_FORMAT) . " ";
    $output = new BufferedOutput();
    $console->renderException($e, $output);
    echo $output->fetch();

    echo "Stacktrace:\n";
    echo $e->getTraceAsString() . "\n";

    exit(-1);
}


