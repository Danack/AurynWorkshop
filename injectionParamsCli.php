<?php

use AurynConfig\InjectionParams;

if (function_exists('injectionParams') == false) {

    function injectionParams() : InjectionParams
    {
        // These classes will only be created once by the injector.
        $shares = [
            \SlimSession\Helper::class,
            \Doctrine\ORM\EntityManager::class,
            \Twig_Environment::class,
        ];

        // Alias interfaces (or classes) to the actual types that should be used
        // where they are required.
        $aliases = [
            AurynWorkshop\VariableMap::class => AurynWorkshop\VariableMap\Psr7VariableMap::class,
            AurynWorkshop\Repo\UserAddRepo::class => \AurynWorkshop\Repo\AdminUserAddRepo\DoctrineUserAddRepo::class
        ];

        // Delegate the creation of types to callables.
        $delegates = [
            \Psr\Log\LoggerInterface::class => 'createLogger',
            \PDO::class => 'createPDO',
            \Redis::class => 'createRedis',
            \Doctrine\ORM\EntityManager::class => 'createDoctrineEntityManager',
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
}


return injectionParams();
