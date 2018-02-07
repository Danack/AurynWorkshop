<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

class Filename {

    /** @var string */
    private $filename;

    /**
     * Filename constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
}

function writeOutput(Filename $filename)
{
    $fileHandle = fopen($filename, 'a');
    fwrite($fileHandle, "Hello, I am some output");
    fclose($fileHandle);
}

$injector = new Injector();



// Solution
$filename = new Filename(__DIR__ . '/../tmp/phpsw.txt');
$injector->share($filename);
$injector->execute('writeOutput');






