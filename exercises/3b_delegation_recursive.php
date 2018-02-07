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
        return "We're online, please ping us on slack.";
    }
}

class OutOfOfficeHoursContactUs implements ContactUs
{
    public function getMessage(): string
    {
        return "Please open a ticket and we'll look at it in the morning.";
    }
}

interface OfficeHours {
    public function isOfficeOpen() : bool;
}

class ClockOfficeHours implements OfficeHours
{
    public function isOfficeOpen() : bool
    {
        $hourOfDay = date('H');

        if ($hourOfDay > 9 && $hourOfDay < 17) {
            return true;
        }

        return false;
    }
}


class AlwaysOpenOfficeHours implements OfficeHours
{
    public function isOfficeOpen() : bool
    {
        return true;
    }
}


function createContactUs(OfficeHours $officeHours)
{
    // Discussion point, how can we test this?
    if ($officeHours->isOfficeOpen()) {
        return new DuringOfficeHoursContactUs();
    }

    return new OutOfOfficeHoursContactUs();
}


$injector = new Injector();
$injector->alias(OfficeHours::class, AlwaysOpenOfficeHours::class);
$injector->delegate(ContactUs::class, 'createContactUs');

function renderContactUsButton(ContactUs $contactUs)
{
    echo $contactUs->getMessage();
}

$injector->execute('renderContactUsButton');






