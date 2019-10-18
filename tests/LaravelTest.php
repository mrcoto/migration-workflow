<?php 

namespace MrCoto\MigrationWorkflow\Test;

use Orchestra\Testbench\TestCase;

abstract class LaravelTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return ['MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider'];
    }

    protected function getEnvironmentSetUp($app)
    {
        $default = 'pgsql';
        $app['config']->set("database.default", $default);
        $app['config']->set("database.connections.$default.host", '127.0.0.1');
        $app['config']->set("database.connections.$default.port", '5432');
        $app['config']->set("database.connections.$default.database", 'migration_workflow');
        $app['config']->set("database.connections.$default.username", 'postgres');
        $app['config']->set("database.connections.$default.password", 'postgres');
    }

}