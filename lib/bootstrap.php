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
        AurynWorkshop\Response::class => 'AurynWorkshopResponseMapper',
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

    $createRoutingMiddlewareFn = function (\AurynWorkshop\AdminSession $adminSession) use ($app, $injector) {
        return new \AurynWorkshop\Middleware\UserTypeRouting($adminSession, $app, $injector);
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



/**
 * @param \Auryn\Injector $injector
 * @param null $language
 * @return Twig_Environment
 */
function createTwigForSite(\Auryn\Injector $injector) {
    $templatePaths = [];

    $templatePaths[] = __DIR__ . '/../template';


//    // The templates are included in order of priority.
//    $templatePaths = [];
//
//    foreach ($baseTemplatePaths as $baseTemplatePath) {
//        // Country + language path
//        $templatePaths[] = sprintf(
//            '%s/%s-%s',
//            $baseTemplatePath,
//            $userInfo->getCountryFromUrl(),
//            $userInfo->getLanguageFromUrl()
//        );
//
//        // url specific language
//        if ($language !== null) {
//            $templatePaths[] = $baseTemplatePath . '/' . $language;
//        }
//
//        // site default language
//        if (($siteLangauge = $siteConfig->getDefaultLanguage()) !== null) {
//            if ($language !== $siteLangauge) {
//                //prevent duplicating site language with user language
//                $templatePaths[] = $baseTemplatePath . '/' . $siteLangauge;
//            }
//        }
//
//        // generic language
//        $templatePaths[] = $baseTemplatePath;
//    }
//
//    $existingTemplatePaths = [];
//    foreach ($templatePaths as $templatePath) {
//        if (is_dir($templatePath) === true) {
//            $existingTemplatePaths[] = $templatePath;
//        }
//    }
//
    $loader = new Twig_Loader_Filesystem($templatePaths);
    $twig = new Twig_Environment($loader, array(
        'cache' => false,
        'strict_variables' => true,
        'debug' => true
    ));
//
//    // Inject function - allows DI in templates.
//    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
//        return $injector->make($type);
//    });
//    $twig->addFunction($function);
//
//
//    $rawParams = ['is_safe' => array('html')];
//
//    // config function - allows keys to be embedded in templates.
//    $function = new Twig_SimpleFunction('config', function (string $keyString) {
//        $keyParts = explode(',', $keyString);
//        return getConfig($keyParts);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    // getAssetVersion function
//    $function = new Twig_SimpleFunction('getAssetVersion', 'getAssetVersionForServer');
//    $twig->addFunction($function);
//
//
//    $function = new Twig_SimpleFunction('print_donor_name', function (string $foundString, string $notFoundString) use ($injector) {
//        $tll = $injector->make('AurynWorkshop\SiteComponent\UserData');
//        return $tll->renderDonorName($foundString, $notFoundString);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//
//    $function = new Twig_SimpleFunction('print_leadgen_name', function (string $foundString, string $notFoundString) use ($injector) {
//        $tll = $injector->make('AurynWorkshop\SiteComponent\UserData');
//        return $tll->renderLeadgenName($foundString, $notFoundString);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('stripeForm', function (string $formName) use ($injector) {
//        $sfr = $injector->make('AurynWorkshop\SiteComponent\StripeFormRender');
//        return $sfr->render($formName);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//
//    $function = new Twig_SimpleFunction('stripeSubscriptionForm', function (string $formName) use ($injector) {
//        $sfr = $injector->make('AurynWorkshop\SiteComponent\StripeFormRender');
//        return $sfr->renderSubscription($formName);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('paypalOneOff', function (string $formName) use ($injector) {
//        $sfr = $injector->make('AurynWorkshop\SiteComponent\StripeFormRender');
//        return $sfr->renderPaypalOneOff($formName);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('stripeFormPublicKey', function (string $formName) use ($injector) {
//        $sfr = $injector->make('AurynWorkshop\SiteComponent\StripeFormRender');
//        return $sfr->renderKey($formName);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('paypalFormPublicKey', function (string $formName) use ($injector) {
//        $sfr = $injector->make('AurynWorkshop\SiteComponent\PaypalFormRender');
//        return $sfr->renderPublicKey($formName);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//
//    // Content block function
//    $function = new Twig_SimpleFunction('content_block', function (string $name) use ($injector) {
//        $cbl = $injector->make('AurynWorkshop\Content\ContentBlockLoader');
//        return $cbl->renderContentBlock($name);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    // Text label function
//    $function = new Twig_SimpleFunction('text_label', function (string $name) use ($injector) {
//        $tll = $injector->make('AurynWorkshop\Content\TextLabelLoader');
//        return $tll->renderTextLabel($name);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('_', function (string $name) use ($injector) {
//        $tll = $injector->make('AurynWorkshop\Content\TextLabelLoader');
//        return $tll->renderTextLabel($name);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('memory_debug', function () {
//        $memoryUsed = memory_get_usage(true);
//        return "<!-- " . number_format($memoryUsed) . " -->";
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $function = new Twig_SimpleFunction('includeJavascript', function (string $jsFilename) use ($injector) {
//        return includeJavascript($jsFilename);
//    }, $rawParams);
//    $twig->addFunction($function);
//
//    $twigFunctions = [
//        'insert_currencies' => 'renderSiteCurrenciesAsJavascript',
//        'render_site_data_layer' => 'AurynWorkshop\Component\DataLayerRender::render',
//        'render_stripe_language' => 'AurynWorkshop\Component\StripeLanguageSetter::render',
//        'render_paypal_language' => 'AurynWorkshop\Component\PaypalLanguageSetter::render',
//    ];
//
//    foreach ($twigFunctions as $functionName => $callable) {
//        $function = new Twig_SimpleFunction($functionName, function () use ($injector, $callable) {
//            return $injector->execute($callable);
//        });
//        $twig->addFunction($function);
//    }

    return $twig;
}