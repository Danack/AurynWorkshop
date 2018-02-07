<?php


namespace AurynWorkshop;

use Twig_Environment as Twig;
use AurynWorkshop\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use AurynWorkshop\AurynWorkshopSession;
use AurynWorkshop\SessionRender;

/**
 * Class TwigRender
 * @package AurynWorkshop
 * @extends Twig_Environment
 */
class TwigSessionRender implements SessionRender
{
    /** @var Twig  */
    private $twig;

    /** @var AurynWorkshopSession */
    private $session;

    public function __construct(Twig $twig, AurynWorkshopSession $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function render($name, array $context = array())
    {
        $fn = function ($message) {
            $this->twig->addGlobal(App::FLASH_MESSAGE_ERROR, $message);
        };
        $this->session->useFlashError($fn);

        $fn = function ($message) {
            $this->twig->addGlobal(App::FLASH_MESSAGE_SUCCESS, $message);
        };
        $this->session->useFlashSuccess($fn);



        return $this->twig->render($name, $context);
    }
}
