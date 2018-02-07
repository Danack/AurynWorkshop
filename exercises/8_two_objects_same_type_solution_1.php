<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

$injector = new Injector();

// Task

// The code below doesn't use dependency injection - this is intolerable!!!!!
// Please change the function transferData to take two parameters, one of which
// is the live database, the other is the archive database


// Solution 1
//
// Use context objects to be semantically clear.
//
// “The purpose of abstracting is not to be vague, but to create a new semantic level in which one can be
// absolutely precise. The intellectual effort needed to ... understand a program need not grow more than
// proportional to program length.” - Edsger W. Dijkstra
//
// This is possibly overkill for this problem right now.....but it's used in banking or other complicated
// schemes where you could have special rules needed for different types of operations, and also can't
// fully trust everyone working on the code base.


class ArchiveContext {

    /** @var \PDO */
    private $livePdo;

    /** @var \PDO */
    private $archivePdo;

    /**
     * ArchiveConext constructor.
     * @param PDO $livePdo
     * @param PDO $archivePdo
     */
    public function __construct(PDO $livePdo, PDO $archivePdo)
    {
        $this->livePdo = $livePdo;
        $this->archivePdo = $archivePdo;
    }

    /**
     * @return PDO
     */
    public function getLivePdo(): PDO
    {
        return $this->livePdo;
    }

    /**
     * @return PDO
     */
    public function getArchivePdo(): PDO
    {
        return $this->archivePdo;
    }
}

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


function transferData(ArchiveContext $archiveContext)
{
    $liveDb = $archiveContext->getLivePdo();
    $archivePdo = $archiveContext->getArchivePdo();

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

function createArchiveContext()
{
    return new ArchiveContext(
        createPDO_8(),
        createSqlitePDO_8()
    );
}

$injector->delegate(\ArchiveContext::class, 'createArchiveContext');
$injector->execute('transferData');
