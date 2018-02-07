<?php

declare(strict_types = 1);

namespace AurynWorkshop\Params;

use AurynWorkshop\VariableMap;
use AurynWorkshop\Exception\ValidationException;

class AppParams
{
    /** @var VariableMap  */
    private $variableMap;

    const ERROR_INVALID_DATETIME = 'Value was not a valid date time apparently';

    /**
     * AppParams constructor.
     * @param VariableMap $variableMap
     */
    public function __construct(VariableMap $variableMap)
    {
        $this->variableMap = $variableMap;
    }

    /**
     * @param string $variableName
     * @return \Closure
     */
    public function checkSet(string $variableName)
    {
        $fn = function ($name, $_) use ($variableName) {
            if ($this->variableMap->hasVariable($variableName) !== true) {
                return [null, 'Value not set for ' . $variableName . '.', true];
            }

            return [$this->variableMap->getVariable($variableName), null, false];
        };

        return $fn;
    }

    public function checkSetOrDefault(string $variableName, $default)
    {
        $fn = function ($name, $_) use ($variableName, $default) {
            if ($this->variableMap->hasVariable($variableName) !== true) {
                return [$default, null];
            }

            return [$this->variableMap->getVariable($variableName), null, false];
        };

        return $fn;
    }

    public function skipIfNull()
    {
        $fn = function ($name, $value) {
            if ($value === null) {
                return [$value, null, true];
            }

            return [$value, null, false];
        };

        return $fn;
    }


    public function trim()
    {
        return function ($name, $value) {
            return [trim($value), null, false];
        };
    }


    /**
     * @param int $maxLength
     * @return \Closure
     */
    public function checkMaxLength(int $maxLength)
    {
        return function ($name, $value) use ($maxLength) {
            if (strlen($value) > $maxLength) {
                return [$value, 'text label name too long, max chars is ' . $maxLength, false];
            }
            return [$value, null, false];
        };
    }

    /**
     * @param int $maxLength
     * @return \Closure
     */
    public function checkMaxLengthOrNull(int $maxLength)
    {
        return function ($name, $value) use ($maxLength) {
            if ($value === null) {
                return [$value, null];
            }
            if (strlen($value) > $maxLength) {
                return [$value, 'text label name too long, max chars is ' . $maxLength, false];
            }
            return [$value, null, false];
        };
    }

    /**
     * @param int $minLength
     * @return \Closure
     */
    public function checkMinLength(int $minLength)
    {
        return function ($name, $value) use ($minLength) {
            if (strlen($value) < $minLength) {
                return [$value, "string for '$name' too short, min chars is " . $minLength, false];
            }
            return [$value, null, false];
        };
    }


    public function checkPositiveInt()
    {
        return function ($name, $value) {
            $matches = null;

            $count = preg_match("/[^0-9]+/", $value, $matches, PREG_OFFSET_CAPTURE);

            if ($count) {
                $badCharPosition = $matches[0][1];
                $message = sprintf(
                    "Value must contain only digits. Non-digit found at position %d.",
                    $badCharPosition
                );
                return [$value, $message, false];
            }

            $value = intval($value);
            $maxValue = 1024 * 1014 * 1024 * 1024;
            if ($value >= $maxValue) {
                return [null, "Value too large. Max allowed is $maxValue"];
            }

            return [$value, null, false];
        };
    }

    public function checkMaxIntValue(int $maxValue)
    {
        return function ($name, int $value) use ($maxValue) {
            $value = intval($value);
            if ($value >= $maxValue) {
                return [null, "Value too large. Max allowed is $maxValue", false];
            }

            return [$value, null, false];
        };
    }

    public function checkMaxIntValueOrNull(int $maxValue)
    {
        return function ($name, $value) use ($maxValue) {
            if ($value === null) {
                return [null, null, false];
            }
            $value = intval($value);
            if ($value >= $maxValue) {
                return [null, "Value too large. Max allowed is $maxValue"];
            }

            return [$value, null, false];
        };
    }

    public function checkKnownEnum($allowedValues)
    {
        //todo check $allowedValues is sane
        return function ($name, string $value) use ($allowedValues) {

            if (in_array($value, $allowedValues, true) !== true) {
                return [null, "Value is not known. Please use one of " . implode(', ', $allowedValues), false];
            }

            return [$value, null, false];
        };
    }

    public function checkKnownEnumOrNull($allowedValues)
    {
        //todo check $allowedValues is sane
        return function ($name, $value) use ($allowedValues) {
            if ($value === null) {
                return [null, null, false];
            }
            if (in_array($value, $allowedValues, true) !== true &&  $value != null) {
                return [null, "Interval is not known. Please use one of " . implode(', ', $allowedValues), false];
            }

            return [$value, null, false];
        };
    }
    public function checkValidDatetimeOrNull()
    {
        return function ($name, $value) {
            if ($value === null) {
                return [null, null];
            }
            try {
                $dateTime = new \DateTime($value);
            }
            catch (\Exception $e) {
                return [null, self::ERROR_INVALID_DATETIME, false];
            }
            // TODO - add sanity checks for being less than 10 years in the past or future.
            return [$dateTime, null, false];
        };
    }

    /**
     * @param $patternValidCharacters
     * @return \Closure
     */
    public function checkValidCharacters($patternValidCharacters)
    {
        return function ($name, $value) use ($patternValidCharacters) {
            $patternInvalidCharacters = "/[^" . $patternValidCharacters . "]+/";
            $matches = [];
            $count = preg_match($patternInvalidCharacters, $value, $matches, PREG_OFFSET_CAPTURE);

            if ($count) {
                $badCharPosition = $matches[0][1];
                $message = sprintf(
                    "Invalid character at position %d. Allowed characters are %s",
                    $badCharPosition,
                    $patternValidCharacters
                );
                return [$value, $message, false];
            }
            return [$value, null, false];
        };
    }

    /**
     * @param $keys
     * @return array
     */
    public function validate($keys)
    {
        $values = [];
        $validationProblems = [];

        foreach ($keys as $name => $rules) {
            $value = null;
            foreach ($rules as $rule) {
                list($value, $validationProblem, $useCurrentAsFinal) = $rule($name, $value);
                if ($validationProblem != null) {
                    $validationProblems[] = $validationProblem;
                    break;
                }
                if ($useCurrentAsFinal === true) {
                    break;
                }
            }
            $values[] = $value;
        }

        ValidationException::throwIfProblems("Validation problems", $validationProblems);

        return $values;
    }



//
//    /**
//     * @return TextLabelCreate
//     */
//    public function getTextLabelCreateParams() : TextLabelCreate
//    {
//        $params = [
//            'name' => [
//                $this->checkSet('name'),
//                $this->checkMaxLength(128),
//                $this->checkMinLength(4),
//                $this->checkValidCharacters(self::PATTERN_VALID_NAME_CHARACTERS)
//            ]
//        ];
//
//        list($name) = $this->validate($params);
//
//        return new TextLabelCreate($name);
//    }
}
