<?php


namespace AurynWorkshop;

use Twig_Environment as Twig;
//use AurynWorkshop\App;
 use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use SlimSession\Helper as Session;
use AurynWorkshop\AdminSession;
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

    /** @var AdminSession */
    private $session;

    public function __construct(Twig $twig, AdminSession $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function render($name, array $context = array())
    {
        if ($this->session->hasFlashError() === true) {
            $flashMessageError = $this->session->getFlashError();
            $this->session->deleteFlashError();
            $this->twig->addGlobal('flash_message_error', $flashMessageError);
        }

        if ($this->session->hasFlashSuccess() === true) {
            $flashMessageSuccess = $this->session->getFlashSuccess();
            $this->session->deleteFlashSuccess();
            $this->twig->addGlobal('flash_message_success', $flashMessageSuccess);
        }

        return $this->twig->render($name, $context);
    }
}
