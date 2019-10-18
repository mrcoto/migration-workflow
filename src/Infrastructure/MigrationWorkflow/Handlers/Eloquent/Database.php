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
        $default = config('database.default');
        return [
            'driver' => $default,
            'host' => config("database.connections.$default.host"),
            'port'=> config("database.connections.$default.port"),
            'database' => config("database.connections.$default.database"),
            'username' => config("database.connections.$default.username"),
            'password' => config("database.connections.$default.password"),
        ];
    }

}