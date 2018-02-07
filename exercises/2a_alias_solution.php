<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

interface Formatter
{
    public function formatMessage(string $message): string;
}

class UpperCaseFormatter implements Formatter
{
    public function formatMessage(string $message): string
    {
        return strtoupper($message);
    }
}

class LowerCaseFormatter implements Formatter
{
    public function formatMessage(string $message): string
    {
        return strtolower($message);
    }
}



class MessageOutput
{
    /** @var Formatter */
    private $formatter;

    /**
     * MessageOutput constructor.
     * @param Formatter $formatter
     */
    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function sendMessage($message)
    {
        echo $this->formatter->formatMessage($message);
    }
}


$injector = new Injector();

// Task
//
// Choose whether you want output to be formatted to lower-case or upper-case
// then alias the Formatter class to the implementation you have chosen.
// 'Make' a message output object, and then send it a 'Hello world' message.

$messageOutput = $injector->alias(Formatter::class, LowerCaseFormatter::class);
$messageOutput = $injector->make(MessageOutput::class);
$messageOutput->sendMessage("Hello world.");




