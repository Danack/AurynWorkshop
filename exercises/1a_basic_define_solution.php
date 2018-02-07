<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

function sayHelloToSubject($subject) {
    printf(
        "Hello %s !",
        $subject
    );
}

$injector = new Injector();



// Task

// Define a parameter called 'subject' with a greeting to
// the group, and then use the injector to execute the function
// called 'sayHelloToSubject'


// Solution
$injector->defineParam('subject', 'PHP Training Bristol');
$injector->execute('sayHelloToSubject');






