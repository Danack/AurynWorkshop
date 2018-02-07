<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;


interface ContactUs
{
    public function getMessage() : string;
}

class DuringOfficeHoursContactUs implements ContactUs
{
    public function getMessage(): string
    {
        return "Please ping us on slack.";
    }
}

class OutOfOfficeHoursContactUs implements ContactUs
{
    public function getMessage(): string
    {
        return "Please open a ticket and we'll look at it in the morning.";
    }
}

function renderContactUsButton(ContactUs $contactUs)
{
    echo $contactUs->getMessage();
}


$injector = new Injector();

function createContactUs()
{
    $hourOfDay = date('H');

    // Discussion point, how can we test this?
    if ($hourOfDay > 9 && $hourOfDay < 17) {
        return new DuringOfficeHoursContactUs();
    }

    return new OutOfOfficeHoursContactUs();
}


// Task
//
// i) Fill out the createContactUs function so that it returns a 'DuringOfficeHoursContactUs'
// object during office hours, and a 'OutOfOfficeHoursContactUs' object out of office hours.
//
// ii) Connec the 'createContactUs' function as the 'delegate function' to be used when something
// requires a ContactUs object.

// Solution

$injector->delegate(ContactUs::class, 'createContactUs');
$injector->execute('renderContactUsButton');






