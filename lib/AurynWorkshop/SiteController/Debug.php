<?php

declare(strict_types=1);

namespace Aitekz\FrontendController;

use Aitekz\Response\DataResponse;
use Aitekz\Response\HtmlResponse;
use Aitekz\TwigRender;
use Elasticsearch\Client as EsClient;

class Debug
{
    public function getIndex(TwigRender $twig)
    {
        $html = $twig->render("test.html");
        return new HtmlResponse($html);
    }

    public function testElastic(EsClient $esClient)
    {
        return new DataResponse($esClient->info());
    }
}
