<?php


namespace AurynWorkshop;

interface VariableMap
{
    /**
     * @throws \AurynWorkshop\Exception\ParamMissingException
     */
    public function getVariable($variableName);

    public function hasVariable($variableName);

    public function getVariableWithDefault($variableName, $defaultValue);
}
