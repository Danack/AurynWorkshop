<?php

namespace AurynWorkshop\Response;

use AurynWorkshop\Response;

class EmptyResponse implements Response
{

    private $statusCode;

    public function __construct(array $headers = [], int $statusCode = 200)
    {
        $standardHeaders = [];

        $this->headers = array_merge($standardHeaders, $headers);
        $this->statusCode = $statusCode;
    }

    public function getStatus()
    {
        return $this->statusCode;
    }

    public function getBody()
    {
        return "";
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
