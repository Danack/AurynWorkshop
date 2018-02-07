<?php

namespace AurynWorkshop\VariableMap;

use AurynWorkshop\VariableMap;
use AurynWorkshop\Exception\ParamMissingException;

class ArrayVariableMap implements VariableMap
{
    private $variables;

    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    public function getVariable($variableName)
    {
        if (!array_key_exists($variableName, $this->variables)) {
            $message = "Parameter [$variableName] is not available";
            throw new ParamMissingException($message);
        }

        return $this->variables[$variableName];
    }

    public function hasVariable($variableName)
    {
        if (!array_key_exists($variableName, $this->variables)) {
            return false;
        }

        return true;
    }

    public function getVariableWithDefault($variableName, $defaultValue)
    {
        if (!array_key_exists($variableName, $this->variables)) {
            return $defaultValue;
        }

        return $this->variables[$variableName];
    }

    public function getVariableNames()
    {
        return $this->variables;
    }
}
