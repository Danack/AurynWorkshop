<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

class Counter {

    private $count = 0;

    public function count()
    {
        $currentCount = $this->count;
        $this->count += 1;

        return $currentCount;
    }
}



$injector = new Injector();

// Task - make the counter object be shared so that it actually counts up when the code below is run.


for ($x=0; $x<5; $x++) {
    printf(
        "Count is %d\n",
        $injector->execute('Counter::count')
    );
}






