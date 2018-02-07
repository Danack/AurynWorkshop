<?php

namespace AurynWorkshop\Response;

use AurynWorkshop\Response;

class StringResponse implements Response
{
    private $body;

    private $headers = [];

    public function getStatus()
    {
        return 200;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * JsonResponse constructor.
     * @param $data
     * @param array $headers
     */
    public function __construct($string, array $headers = [])
    {
        $standardHeaders = [
            'Content-Type' => 'text/plain'
        ];

        $this->headers = array_merge($standardHeaders, $headers);
        $this->body = $string;
    }

    public function getBody()
    {
        return $this->body;
    }
}
