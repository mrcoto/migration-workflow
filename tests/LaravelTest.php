<?php 

namespace MrCoto\MigrationWorkflow\Test;

use Orchestra\Testbench\TestCase;

abstract class LaravelTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            'MrCoto\MigrationWorkflow\Application\LaravelMigrationWorkflowServiceProvider',
            'MrCoto\MigrationWorkflow\Test\Stub\FakeServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('migration_workflow.workflows', [
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data2',
            'MrCoto\MigrationWorkflow\Test\Stub\Deploy\Data1',
        ]);
        $app['config']->set('migration_workflow.logger', 'MrCoto\MigrationWorkflow\Infrastructure\Logger\SilentLogger');
    }

}