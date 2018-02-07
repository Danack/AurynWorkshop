<?php

declare(strict_types = 1);

namespace AurynWorkshop\Exception;

class ValidationException extends \Exception
{
    private $validationProblems;

    /**
     * ValidationException constructor.
     * @param string $message
     * @param array $validationProblems
     * @param \Exception|null $previous
     */
    public function __construct($message, array $validationProblems, \Exception $previous = null)
    {
        $actualMessage = $message . " ";
        $actualMessage .= implode(", ", $validationProblems);

        $this->validationProblems = $validationProblems;

        parent::__construct($actualMessage, $code = 0, $previous);
    }

    /**
     * @return array
     */
    public function getValidationProblems(): array
    {
        return $this->validationProblems;
    }

    public static function throwIfProblems($message, array $validationProblems)
    {
        if (count($validationProblems) > 0) {
            throw new ValidationException($message, $validationProblems);
        }
    }
}
