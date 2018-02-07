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

function createPdoFromConfig(PdoConfig $pdoConfig)
{
    $dsn = sprintf(
        "mysql:host=%s; dbname=%s",
        $pdoConfig->getHost(),
        $pdoConfig->getDbname()
    );

    return new PDO($dsn, $pdoConfig->getUser(), $pdoConfig->getPassword());
}


$injector->delegate(\PdoConfig::class, 'createPdoConfig');
$injector->delegate(\PDO::class, 'createPdoFromConfig');


function testDataReading(Pdo $pdo)
{
    try {

        $statement = $pdo->query("select * from exampe_data");
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        // error in SQL, won't reach this.
    }
    catch (\PDOException $pdoException) {
        echo "\o/ : " . $pdoException->getMessage();
    }
}



// TASK
//
// Enable exception mode on the PDO connection through a prepare function that sets the exception error mode.
// HINT - the code for that would be `$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);`
// p.s. yes, I know it could be done in the delegate function, but I couldn't think of another
// prepare example.
//
// i.e. the exception should be caught in the catch PDOException block not a generic PHP error


// Solution

function preparePdo(PDO $pdo)
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$injector->prepare(\PDO::class, 'preparePdo');

$injector->execute('testDataReading');

