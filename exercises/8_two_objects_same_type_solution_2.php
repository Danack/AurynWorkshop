<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;






$injector = new Injector();



// Task

// The code below doesn't use dependency injection - this is intolerable!!!!!
// Please change the function transferData to take two parameters, one of which
// is the live database, the other is the archive database
//
// There is a revealing hint below
function createSqlitePDO_8()
{
    $dsn = sprintf("sqlite:%s", getConfig(['auryn_workshop', 'file_database', 'path']));
    $pdo = new \PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $createSQL = 'CREATE TABLE IF NOT EXISTS example_data (
                        foo VARCHAR (255),
                        bar VARCHAR (255)
                      )';
    $pdo->exec($createSQL);

    return $pdo;
}


/**
 * @return PDO
 */
function createPDO_8()
{
    $config = getConfig(['auryn_workshop', 'database']);
    $string = sprintf(
        'mysql:host=%s;dbname=%s',
        $config['host'],
        $config['schema']
    );

    $pdo = new PDO($string, $config['username'], $config['password'], array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ));

    return $pdo;
}

function transferData(PDO $liveDb)
{
    $archivePdo = createSqlitePDO_8();
    $readStatement = $liveDb->query("select * from example_data ");
    $rows = $readStatement->fetchAll(PDO::FETCH_ASSOC);
    $statement = $archivePdo->prepare("insert into example_data ('foo', 'bar') values (:foo, :bar)");

    foreach ($rows as $row) {
        $data = [
            'foo' => $row['foo'],
            'bar' => $row['bar']
        ];
        $statement->execute($data);
    }

    $result = $archivePdo->query("select count(1) as total_rows from example_data");

    $row = $result->fetch();

    printf("There are %d rows in the archive.\n", $row['total_rows']);
}


$injector->delegate(\PDO::class, 'createPDO_8');


$injector->execute('transferData');






// There is a revealing hint below
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
// There are at least two solutions:
// i) extend the logger type to two separate child types
// ii) use the functions be dependent on Logger factories not the logger directly

