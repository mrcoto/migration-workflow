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
    }

}