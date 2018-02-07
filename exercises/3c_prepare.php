<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

$injector = new Injector();

class PdoConfig {

    /** @var string */
    private $host;

    /** @var string */
    private $dbname;

    /** @var string */
    private $user;

    /** @var string */
    private $password;

    /**
     * PdoConfig constructor.
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $dbname, string $user, string $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getDbname(): string
    {
        return $this->dbname;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}

function createPdoConfig()
{
    return new PdoConfig(
        getConfig(['auryn_workshop', 'database', 'host']),
        getConfig(['auryn_workshop', 'database', 'schema']),
        getConfig(['auryn_workshop', 'database', 'username']),
        getConfig(['auryn_workshop', 'database', 'password'])
    );
}

function createPdo(PdoConfig $pdoConfig)
{
    $dsn = sprintf(
        "mysql:host=%s; dbname=%s",
        $pdoConfig->getHost(),
        $pdoConfig->getDbname()
    );

    return new PDO($dsn, $pdoConfig->getUser(), $pdoConfig->getPassword());
}

function preparePdo(PDO $pdo)
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$injector->delegate(\PdoConfig::class, 'createPdoConfig');
$injector->delegate(\PDO::class, 'createPdo');
$injector->prepare(\PDO::class, 'preparePdo');


function testShit(Pdo $pdo)
{
    $statement = $pdo->query("select * from foo");
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    var_dump($rows);
}


$injector->execute('testShit');

