<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;



$injector = new Injector();

$injector->delegate(\PDO::class, 'createPdo');


function transferData(PDO $liveDb /*, PDO $archiveDb */)
{
    $backupPdo = createSqlitePDO();
    $statement = $liveDb->query("select * from example_data ");
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        var_dump($row);
    }
}



// Task

$injector->execute('transferData');