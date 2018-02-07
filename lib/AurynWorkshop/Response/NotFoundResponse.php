<?php


namespace AurynWorkshop\Response;

use AurynWorkshop\Response;

class NotFoundResponse implements Response
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getStatus()
    {
        return 404;
    }

    public function getBody()
    {
        return $this->message;
    }

    public function getHeaders()
    {
        return [];
    }
}
