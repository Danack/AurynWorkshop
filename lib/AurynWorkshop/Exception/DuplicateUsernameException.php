<?php

declare(strict_types=1);

namespace AurynWorkshop\Exception;

use Throwable;

class DuplicateUsernameException extends \Exception
{
    public function __construct(string $message, Throwable $previous)
    {
        parent::__construct($message, 0, $previous);
    }
}
