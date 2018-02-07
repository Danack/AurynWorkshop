<?php

namespace AurynWorkshop\VariableMap;

use AurynWorkshop\Exception\ParamMissingException;
use Psr\Http\Message\ServerRequestInterface;
use AurynWorkshop\VariableMap;

class Psr7VariableMap implements VariableMap
{
    /** @var ServerRequestInterface */
    private $serverRequest;

    public function __construct(ServerRequestInterface $serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }

    public function getVariableWithDefault($variableName, $defaultValue)
    {
        $queryParams = $this->serverRequest->getQueryParams();
        if (array_key_exists($variableName, $queryParams) === true) {
            return $queryParams[$variableName];
        }

        $bodyParams = $this->serverRequest->getParsedBody();
        if (is_array($bodyParams) && array_key_exists($variableName, $bodyParams) === true) {
            return $bodyParams[$variableName];
        }

        return $defaultValue;
    }

    public function hasVariable($variableName)
    {
        $queryParams = $this->serverRequest->getQueryParams();
        if (array_key_exists($variableName, $queryParams) === true) {
            return true;
        }

        $bodyParams = $this->serverRequest->getParsedBody();
        if (is_array($bodyParams) && array_key_exists($variableName, $bodyParams) === true) {
            return true;
        }

        return false;
    }

    public function getVariable($variableName)
    {
        $queryParams = $this->serverRequest->getQueryParams();
        if (array_key_exists($variableName, $queryParams) === true) {
            return $queryParams[$variableName];
        }

        $bodyParams = $this->serverRequest->getParsedBody();
        if (is_array($bodyParams) && array_key_exists($variableName, $bodyParams) === true) {
            return $bodyParams[$variableName];
        }

        $message = "Parameter [$variableName] is not available";
        throw new ParamMissingException($message);
    }

    public function getVariableNames()
    {
        return $this->serverRequest->getParsedBody();
    }
}
