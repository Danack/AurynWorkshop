<?php

/**
 * This file contains creation functions for creating objects.
 */
use AurynWorkshop\App;
use SlimSession\Helper as Session;
use Danack\SlimAurynInvoker\SlimAurynInvoker;
use Danack\SlimAurynInvoker\SlimAurynInvokerFactory;
use Danack\Response\StubResponse;
use Danack\Response\StubResponseMapper;
use Psr\Http\Message\ResponseInterface;

function createApp(\Slim\Container $container, \Auryn\Injector $injector)
{
    $resultMappers = [
        StubResponse::class => [StubResponseMapper::class, 'mapToPsr7Response'],
        AurynWorkshop\Response::class => 'aurynWorkshopResponseMapper',
        ResponseInterface::class => function (
            ResponseInterface $controllerResult,
            ResponseInterface $originalResponse
        ) {
            return $controllerResult;
        }
    ];

    $container['foundHandler'] = new SlimAurynInvokerFactory(
        $injector,
        $resultMappers,
        'setupSlimAurynInvoker'
    );


    //Override the default Not Found Handler
    $container['notFoundHandler'] = function ($c) {
        return function ($request, $response) use ($c) {
            return $c['response']
                ->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->write('Page not found');
        };
    };

    $app = new \Slim\App($container);

    $container['errorHandler'] = function ($c) {
        return function ($request, $response, $exception) use ($c) {
            switch (get_class($exception)) {
                case 'AurynWorkshop\Exception\SiteNotSelectedException':
                    return $c['response']->withStatus(302)->withHeader('location', '/site_select');
            }

            $text = "";
            /** @var $exception \Exception */
            do {
                $text .= get_class($exception) . ":" . $exception->getMessage() . "\n\n";
                $text .= nl2br($exception->getTraceAsString());

                $text .= "<br/><br/>";
            } while (($exception = $exception->getPrevious()) !== null);

            error_log($text);

            return $c['response']->withStatus(503)
                ->withHeader('Content-Type', 'text/html')
                ->write($text);
        };
    };

    //$app->add($injector->make(\AurynWorkshop\Middleware\LoginCheck::class));

    $createRoutingMiddlewareFn = function (\AurynWorkshop\AurynWorkshopSession $awSession) use ($app, $injector) {
        return new \AurynWorkshop\Middleware\UserTypeRouting($awSession, $app, $injector);
    };

    $routingMiddleware = $injector->execute($createRoutingMiddlewareFn);
    $app->add($routingMiddleware);

    // $app->add($injector->make(\AurynWorkshop\Middleware\RememberMeMiddleware::class));
    // $app->add($injector->make(\AurynWorkshop\Middleware\AdminSiteSelectCheck::class));
    // $app->add($injector->make(\AurynWorkshop\Middleware\NoCacheMiddleware::class));

    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };

    $secure = false;
    $env = getConfigWithDefault(['AurynWorkshop', 'env'], null);
    $env = strtolower($env);
    if (strpos($env, 'local') !== 0) {
        //Not running in local dev, always have https available.
        $secure = true;
    }

    $app->add(new \AurynWorkshop\Middleware\Session([
        'name' => 'AurynWorkshop_session',
        'autorefresh' => true,
        'lifetime' => 3630,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true
    ]));

    return $app;
}


/**
 * @return PDO
 */
function createPDO()
{
    $config = getConfig(['auryn_workshop', 'database']);
    $string = sprintf(
        'mysql:host=%s;dbname=%s',
        $config['host'],
        $config['schema']
    );

    $pdo = new PDO($string, $config['username'], $config['password'], array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ));

    return $pdo;
}



function createSqlitePDO()
{
    $dsn = sprintf("sqlite:%s", getConfig(['auryn_workshop', 'file_database', 'path']));
    $pdo = new \PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $createSQL = 'CREATE TABLE IF NOT EXISTS example_data (
                        foo VARCHAR (255),
                        bar VARCHAR (255)
                      )';
    $pdo->exec($createSQL);

    return $pdo;
}

/**
 * @param \Auryn\Injector $injector
 * @param null $language
 * @return Twig_Environment
 */
function createTwigForSite(\Auryn\Injector $injector) {
    $templatePaths = [];

    $templatePaths[] = __DIR__ . '/../template';

    $loader = new Twig_Loader_Filesystem($templatePaths);
    $twig = new Twig_Environment($loader, array(
        'cache' => false,
        'strict_variables' => true,
        'debug' => true
    ));

    // Inject function - allows DI in templates.
    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
        return $injector->make($type);
    });

    $twig->addFunction($function);

    return $twig;
}


/**
 * @return \Doctrine\ORM\EntityManager
 */
function createDoctrineEntityManager()
{
    $config = getConfig(['auryn_workshop', 'database']);

    $connectionParams = array(
        'dbname' => $config['schema'],
        'user' => $config['username'],
        'password' => $config['password'],
        'host' => $config['host'],
        'driver' => 'pdo_mysql',
    );

    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        [__DIR__ . "/Aitekz/Model"],
        true,
        __DIR__ . "/../var/doctrine"
    );

    // TODO - precompile these in the build step.
    // $config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS);

    // obtaining the entity manager
    return \Doctrine\ORM\EntityManager::create($connectionParams, $config);
}


/**
 * @param Redis $redis
 * @return \Birke\Rememberme\Authenticator
 */
function createRememberMeAuthenticator(Redis $redis)
{
    $storage = new \Birke\Rememberme\Storage\RedisStorage($redis, 'rememberme_');

    return new \Birke\Rememberme\Authenticator($storage);
}
