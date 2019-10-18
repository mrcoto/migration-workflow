<?php 

namespace MrCoto\MigrationWorkflow\Infrastructure\MigrationWorkflow\Handlers\Eloquent;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;

class Database
{

    private $capsule;

    public function __construct()
    {
        $capsule = new Manager();
        $config = $this->getConfig();
        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $this->capsule = $capsule;
    }

    /**
     * Get capsule connection
     *
     * @return Connection
     */
    public function connection() : Connection
    {
        return $this->capsule->connection();
    }

    /**
     * Get database configuration file
     *
     * @return array
     */
    private function getConfig() : array
    {
        return [
            'driver' => 'pgsql',
            'host' => '127.0.0.1',
            'port'=> '5432',
            'database' => 'agroplastic',
            'username' => 'postgres',
            'password' => 'secret'
        ];
        // return [
        //     'driver' => $default,
        //     'host' => Config::get("database.connections.$default.host", Env::get('DB_HOST')),
        //     'database' => Config::get("database.connections.$default.host", Env::get('DB_DATABASE')),
        //     'username' => Config::get("database.connections.$default.username", Env::get('DB_USERNAME')),
        //     'password' => Config::get("database.connections.$default.password", Env::get('DB_PASSWORD')),
        //     'charset' => Config::get("database.connections.$default.charset", 'utf8'),
        //     'collation' => Config::get("database.connections.$default.collation", 'utf8mb4_unicode_ci'),
        //     'prefix' => Config::get("database.connections.$default.prefix", ''),
        // ];
    }

}