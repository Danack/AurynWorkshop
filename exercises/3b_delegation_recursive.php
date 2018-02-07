<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

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


// Task
//
// A directive has come down from head office, that says some offices must be staffed 24 hours a day,
// but others don't if they don't have enough staff. Some helper classes of ClockOfficeHours and AlwaysOpenOfficeHours
// that both implement OfficeHours have been provided.
//
// i) Choose if your office is staffed 24 hours a day, or is only open during the UK office hours.
//
// ii) Alias the OfficeHours interface to the appropriate implementation.
//
// iii) Modify the 'createContactUs' function to depend on an OfficeHours interface, and use it to decide
// what type of ContactUs should be created.


function createContactUs()
{
    // TODO - this code needs modifying.
    $hourOfDay = date('H');

    // Discussion point, how can we test this?
    if ($hourOfDay > 9 && $hourOfDay < 17) {
        return new DuringOfficeHoursContactUs();
    }

    return new OutOfOfficeHoursContactUs();
}








