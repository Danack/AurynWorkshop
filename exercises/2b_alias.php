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
$messageOutput = $injector->alias(Formatter::class, LowerCaseFormatter::class);
$messageOutput = $injector->make(MessageOutput::class);
$messageOutput->sendMessage("Hello world.");




