<?php

use AurynConfig\InjectionParams;

function injectionParams()
{
    // These classes will only be created once by the injector.
    $shares = [
        \SlimSession\Helper::class,
        \Twig_Environment::class,
        \Auryn\Injector::class,
        \Doctrine\ORM\EntityManager::class,
        \AurynWorkshop\SessionRender::class,
        \SlimSession\Helper::class,
        \AurynWorkshop\AurynWorkshopSession::class,
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
        \AurynWorkshop\SessionRender::class => \AurynWorkshop\TwigSessionRender::class,
        \AurynWorkshop\VariableMap::class => AurynWorkshop\VariableMap\Psr7VariableMap::class,

        \AurynWorkshop\Repo\UserValidateRepo::class => \AurynWorkshop\Repo\UserValidateRepo\DoctrineUserValidateRepo::class
    ];


    if (getConfigWithDefault(['dijon', 'direct_sending_no_queue'], false) === true) {

    }

    // Delegate the creation of types to callables.
    $delegates = [
        //\Psr\Log\LoggerInterface::class => 'createLogger',
        \Twig_Environment::class => 'createTwigForSite',
        \PDO::class => 'createPDO',
//        \Dijon\Tracker\PageTracker::class => 'createTracker',
//        \Dijon\Tracker\ComponentTracker::class => 'createTracker',
        \Doctrine\ORM\EntityManager::class => 'createDoctrineEntityManager',
//        \Dijon\SiteConfig::class => 'createSiteConfig',
//        \Birke\Rememberme\Authenticator::class => 'createRememberMeAuthenticator',
//        \Redis::class => 'createRedis',
//        \Dijon\TwigRender::class => ['Dijon\TwigRender', 'createTwigRender'],
//        \Google\Cloud\PubSub\PubSubClient::class => 'createGooglePubSubClient',
//        \Google\Cloud\Storage\StorageClient::class => 'createGcloudStorageForSite',
        // \Birke\Rememberme\Authenticator::class => 'createRememberMeAuthenticator',
    ];

    // Define some params that can be injected purely by name.
    $params = [];

    $prepares = [
    ];

    $defines = [];

    $injectionParams = new InjectionParams(
        $shares,
        $aliases,
        $delegates,
        $params,
        $prepares,
        $defines
    );

    return $injectionParams;
}
