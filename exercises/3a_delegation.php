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

function createContactUs()
{
    $hourOfDay = date('H');

    // Discussion point, how can we test this?
    if ($hourOfDay > 9 && $hourOfDay < 17) {
        return new DuringOfficeHoursContactUs();
    }

    return new OutOfOfficeHoursContactUs();
}


$injector = new Injector();
$injector->delegate(ContactUs::class, 'createContactUs');

function renderContactUsButton(ContactUs $contactUs)
{
    echo $contactUs->getMessage();
}

$injector->execute('renderContactUsButton');






