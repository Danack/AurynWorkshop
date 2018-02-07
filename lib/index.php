<?php

error_reporting(E_ALL);


require_once __DIR__ . '/functions.php';

set_error_handler('saneErrorHandler');

$injector = new Auryn\Injector();
$injectionParams = injectionParams();
$injectionParams->addToInjector($injector);
$injector->share($injector);

$container = new \Slim\Container;

//$session_save_handler = getConfigWithDefault(['session', 'save_handler'], null);
//if ($session_save_handler !== null) {
//    ini_set('session.save_handler', $session_save_handler);
//    ini_set('session.save_path', getConfig(['session', 'save_path']));
//}

if (strcasecmp(PHP_SAPI, 'cli') == 0) {
    // We only reach CLI here when we are testing, so hard-coded to test particular
    // route.
    require_once __DIR__ . "/../cli_debug.php";
}

try {
    $app = createApp($container, $injector);
    $app->run();
}
catch (\Exception $exception) {
    echo "oops";

    do {
        echo get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        echo nl2br($exception->getTraceAsString());

        echo "<br/><br/>";
    } while (($exception = $exception->getPrevious()) !== null);
}


